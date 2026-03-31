<?php
// this php run conservation analysis, return results in json and update MySQL `results`
session_start();
require_once 'login.php';
// query and return json
header("Content-Type: application/json");

$search_id = $_SESSION['search_id'];
$data = json_decode(file_get_contents("php://input"), true)['data'];

// directory and prefix for files
$prefix = "results/$search_id"

if (!file_exists("results")) {
    mkdir("results");
}

// get the selected IDs array
$ids = array_map(fn($x) => $x['id'], $data);

// 1. write FASTA
$fasta = "";
foreach ($data as $item) {
	$fasta .= ">" . $item['id'] . " " . $item['name'] . "\n";
	$fasta .= $item['sequence'] . "\n";
}

file_put_contents("$prefix.fasta", $fasta);


// 2. run Clustal Omega, force rewrite
$aln_filename = "$prefix.aln";
exec("clustalo -i $prefix.fasta -o $aln_filename --force", $out1, $status1);


// 3. run plotcon
$png_filename = "$prefix.png";
exec("plotcon -sequences $aln_filename -graph png -goutfile $prefix -winsize 4", $out2, $status2);


// 4. update database
$conservation_json = json_encode([
    "ids" => $ids,
    "aln_filename" => basename($aln_filename),
    "png_filename" => basename($png_filename)
]);
try {
    $dsn = "mysql:host=$hostname;dbname=$database;charset=utf8mb4";
    $conn = new PDO($dsn, $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

     // insert search entry
    $stmt = $conn->prepare("
    INSERT INTO results (search_id, conservation)
    VALUES (?, ?)
    ON DUPLICATE KEY UPDATE
    conversation = VALUES(conversation)
    ");
    $stmt->execute([$search_id, $conservation_json]);

    $stmt = null;
} catch(PDOException $e) {
          echo "<b><font color=\"red\">Connection failed</font></b>:<br/>" . $e->getMessage();
}

// return json
echo json_encode([
    "ids" => $ids,
    "alignment" => file_get_contents($aln_filename),
    "image" => $png_filename
]);
?>
