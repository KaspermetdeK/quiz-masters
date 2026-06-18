<?php

$host = 'localhost';
$dbname = 'quizmaker';
$username = 'bit_academy';
$password = 'bit_academy';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database fout: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $titel = $_POST["title"];
    $beschrijving = $_POST["description"];
    $image = $_POST["image"];

    $stmt = $conn->prepare("INSERT INTO quizzes (titel, beschrijving, image_url) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $titel, $beschrijving, $image);
    $stmt->execute();

    $quiz_id = $stmt->insert_id;

    foreach ($_POST["questions"] as $q) {

        $stmtQ = $conn->prepare("INSERT INTO vragen (quiz_id, vraagtekst) VALUES (?, ?)");
        $stmtQ->bind_param("is", $quiz_id, $q["text"]);
        $stmtQ->execute();

        $vraag_id = $stmtQ->insert_id;

        foreach ($q["answers"] as $index => $answer) {
            $is_correct = ($index == $q["correct"]) ? 1 : 0;

            $stmtA = $conn->prepare("INSERT INTO antwoorden (vraag_id, antwoordtekst, is_correct) VALUES (?, ?, ?)");
            $stmtA->bind_param("isi", $vraag_id, $answer, $is_correct);
            $stmtA->execute();
        }
    }

    echo "<script>alert('Quiz succesvol aangemaakt!'); window.location='quiz.overzicht.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Maken</title>

    <style>
        body {
            margin: 0;
            background-color: #F4F7FA;
            font-family: Arial, sans-serif;
        }

        /* HEADER */
        header {
            background: #1A3A5F;
            color: white;
            padding: 20px 0;
            border-bottom: 4px solid #162F4D;
            text-align: center;
            font-size: 26px;
            font-weight: bold;
        }

        /* CONTAINER */
        .container {
            max-width: 900px;
            margin: 40px auto;
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }

        label {
            font-weight: bold;
            color: #1A3A5F;
        }

        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 10px 0 20px;
            border-radius: 10px;
            border: 1px solid #ccc;
            font-size: 16px;
            box-sizing: border-box;
        }

        .btn {
            background: #1A3A5F;
            color: white;
            padding: 14px 28px;
            border-radius: 10px;
            font-size: 18px;
            border: none;
            cursor: pointer;
            transition: 0.25s;
            width: 100%;
        }

        .btn:hover {
            background: #162F4D;
        }

        .btn-maken {
            display: block;
            width: fit-content;
            margin: 40px auto;
            padding: 15px 35px;
            background: #1A3A5F;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-size: 20px;
            font-weight: bold;
            transition: 0.25s;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
        }

        .btn-maken:hover {
            background: #162F4D;
            transform: scale(1.05);
        }

        .add-btn {
            background: #2E7D32;
            margin-bottom: 20px;
        }

        .add-btn:hover {
            background: #256628;
        }

        .remove-btn {
            background: #C62828;
            margin-top: 10px;
        }

        .remove-btn:hover {
            background: #A61E1E;
        }

        .question-box {
            background: #EEF3F8;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            border-left: 6px solid #1A3A5F;
        }

        .question-box h3 {
            margin-top: 0;
            color: #1A3A5F;
        }

        /* RESPONSIVE */
        @media (max-width: 600px) {
            header {
                font-size: 22px;
            }

            .container {
                padding: 20px;
            }

            input, textarea, select {
                font-size: 15px;
                padding: 10px;
            }

            .btn {
                font-size: 16px;
                padding: 12px;
            }

            .question-box {
                padding: 15px;
            }
        }
    </style>

    <script>
        let questionCount = 0;

        function addQuestion() {
            questionCount++;

            const container = document.getElementById("questions");

            const box = document.createElement("div");
            box.className = "question-box";
            box.innerHTML = `
                <h3>Vraag ${questionCount}</h3>

                <label>Vraagtekst:</label>
                <input type="text" name="questions[${questionCount}][text]" required>

                <label>Antwoord 1:</label>
                <input type="text" name="questions[${questionCount}][answers][]" required>

                <label>Antwoord 2:</label>
                <input type="text" name="questions[${questionCount}][answers][]" required>

                <label>Antwoord 3:</label>
                <input type="text" name="questions[${questionCount}][answers][]" required>

                <label>Antwoord 4:</label>
                <input type="text" name="questions[${questionCount}][answers][]" required>

                <label>Correct antwoord (1-4):</label>
                <select name="questions[${questionCount}][correct]" required>
                    <option value="0">1</option>
                    <option value="1">2</option>
                    <option value="2">3</option>
                    <option value="3">4</option>
                </select>

                <button type="button" class="btn remove-btn" onclick="this.parentElement.remove()">Verwijder vraag</button>
            `;

            container.appendChild(box);
        }
    </script>
</head>

<body>

<header>Maak je eigen Quiz</header>

<div class="container">

    <form method="POST">

        <label>Titel van de quiz:</label>
        <input type="text" name="title" required>

        <label>Beschrijving:</label>
        <textarea name="description" rows="3" required></textarea>

        <label>Afbeelding URL:</label>
        <input type="text" name="image" required>

        <h2 style="color:#1A3A5F;">Vragen</h2>

        <div id="questions"></div>

        <button type="button" class="btn add-btn" onclick="addQuestion()">+ Voeg vraag toe</button>

        <button type="submit" class="btn">Quiz Opslaan</button>

        <a href="quiz.overzicht.php" class="btn-maken">Annuleren</a>
    </form>

</div>

</body>
</html>
