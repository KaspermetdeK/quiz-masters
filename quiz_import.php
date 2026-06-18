<?php
$host = 'localhost';
$dbname = 'quizmaker';
$username = 'bit_academy';
$password = 'bit_academy';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database fout: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["jsonfile"])) {

    $json = file_get_contents($_FILES["jsonfile"]["tmp_name"]);
    $data = json_decode($json, true);

    if (!$data) {
        die("Ongeldig JSON-bestand.");
    }

    $stmt = $conn->prepare("INSERT INTO quizzes (titel, beschrijving, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $data["titel"], $data["beschrijving"], $data["image_url"]);
    $stmt->execute();
    $quiz_id = $stmt->insert_id;

    foreach ($data["vragen"] as $vraag) {

        $stmt = $conn->prepare("INSERT INTO vragen (quiz_id, vraagtekst) VALUES (?, ?)");
        $stmt->bind_param("is", $quiz_id, $vraag["vraagtekst"]);
        $stmt->execute();
        $vraag_id = $stmt->insert_id;

        foreach ($vraag["antwoorden"] as $antwoord) {
            $stmt = $conn->prepare("INSERT INTO antwoorden (vraag_id, antwoordtekst, is_correct) VALUES (?, ?, ?)");
            $stmt->bind_param("isi", $vraag_id, $antwoord["antwoordtekst"], $antwoord["is_correct"]);
            $stmt->execute();
        }
    }

    echo "<h2>Quiz succesvol geïmporteerd!</h2>";
    echo "<a href='quiz.overzicht.php'>Terug naar overzicht</a>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quiz importeren</title>
</head>
<body>
    <h1>Quiz importeren</h1>
    <form method="POST" enctype="multipart/form-data">
        <input type="file" name="jsonfile" accept=".json" required>
        <button type="submit">Importeren</button>
    </form>
</body>
</html>
