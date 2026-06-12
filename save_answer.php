<?php
$data = json_decode(file_get_contents("php://input"), true);

$conn = new mysqli("localhost", "root", "", "results");

$stmt = $conn->prepare(
    "INSERT INTO questions (question, answer, input)
     VALUES (?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $data['question'],
    $data['answer'],
    $data['input']
);

$stmt->execute();

$stmt->close();
$conn->close();
?>