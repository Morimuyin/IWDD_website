<?php
// query and return json
header("Content-Type: application/json");

$family = $_POST['family'];
$taxonomy = $_POST['taxonomy'];

$cmd = "./run_query.sh $family $taxonomy";
$output = shell_exec($cmd);

// debug
//file_put_contents("debug_cmd.txt", $cmd);
//$output = shell_exec($cmd . " 2>&1");
//file_put_contents("debug_out.txt", $output);

echo $output;
/* fake data (replace later with shell_exec)
$data = [
    [
        "id" => "XP_001",
        "name" => "glucose-6-phosphatase isoform 1",
        "length" => 357,
        "sequence" => "MEEPQSDPSVEPPLSQETFSDLWKLLPEN..."
    ],
    [
        "id" => "XP_002",
        "name" => "glucose-6-phosphatase isoform 2",
        "length" => 310,
        "sequence" => "MSSGSSSSSSGSSGSGSAAAVVVVVVAAA..."
    ],
    [
        "id" => "XP_003",
        "name" => "glucose-6-phosphate translocase",
        "length" => 512,
        "sequence" => "MKTVRQERLKSIVRILERSKEPVSGAQLAE..."
    ]
];

echo json_encode($data);
*/

/*
echo json_encode([
    [
        "id" => "DEBUG_1",
        "name" => $family . " in " . $taxonomy,
        "length" => 123,
        "sequence" => "TESTSEQ"
    ]
]);
 */

exit;
?>
