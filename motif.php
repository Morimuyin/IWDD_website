<?php
// this php run motif analysis, return results in json and update MySQL `results`
session_start();
require_once 'login.php';
// query and return json
header("Content-Type: application/json");

$search_id = $_SESSION['search_id'];
$data = json_decode(file_get_contents("php://input"), true)['data'];

// directory and prefix for files
$prefix = "results/$search_id";

//if (!file_exists("results")) {
//    mkdir("results");
//}

// get the selected IDs array
$ids = array_map(fn($x) => $x['id'], $data);

// 1. write FASTA
$fasta = "";
foreach ($data as $item) {
	$fasta .= ">" . $item['id'] . " " . $item['name'] . "\n";
	$fasta .= $item['sequence'] . "\n";
}

file_put_contents("$prefix.fasta", $fasta);


// 2. run patmatmotifs
$motif_filename = "$prefix.patmatmotifs";
exec("patmatmotifs -sequence $prefix.fasta -outfile $motif_filename", $out1, $status1);


// 4. update database
$motif_json = json_encode([
    "ids" => $ids,
    "motif_filename" => $motif_filename
]);
try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     // insert search entry
    $stmt = $conn->prepare("
    INSERT INTO results (search_id, motif)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE
    motif = VALUES(motif)
    ");
    $stmt->execute([$search_id, $motif_json]);

    $stmt = null;
} catch(PDOException $e) {
          echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
}

// return json
echo $motif_json;
?>
