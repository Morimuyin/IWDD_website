<?php
// query and return json
header("Content-Type: application/json");

$family = $_POST['family'];
$taxonomy = $_POST['taxonomy'];

$cmd = "./run_query.sh $family $taxonomy";
$output = shell_exec($cmd);

echo $output;
?>
