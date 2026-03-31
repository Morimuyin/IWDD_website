<?php
header("Content-Type: application/json");

echo json_encode([
    "received_family" => $_POST["family"] ?? null,
    "received_taxonomy" => $_POST["taxonomy"] ?? null
]);
?>
