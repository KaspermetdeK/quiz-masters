<?php

session_start();

$host = 'localhost';
$dbname = 'netland';
$username = 'bit_academy';
$password = 'bit_academy';

$db = new PDO("mysql:host=localhost;dbname=netland", "bit_academy", "bit_academy");

$query = $db->prepare("SELECT * FROM teams ORDER BY title ASC");
$query->execute();
$teams = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard</title>

    <style>
        body {
            background-color: #e9eff6;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            padding: 20px;
            font-family: Arial, sans-serif;
            color: #1A3A5F;
        }

        header {
            width: 100%;
            max-width: 800px;
            background-color: #1A3A5F;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 4px solid #162F4D;
            border-radius: 12px 12px 0 0;
        }

        .container {
            width: 100%;
            max-width: 800px;
            background-color: #F4F7FA;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            padding: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px 15px;
            border-bottom: 1px solid #d9e2ef;
            text-align: left;
        }

        th {
            background-color: #dde7f1;
            font-weight: 700;
        }

        .actions {
            margin-top: 25px;
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
        }

        button {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            background-color: #1A3A5F;
            color: white;
            font-size: 16px;
        }

        button:hover {
            background-color: #162F4D;
        }
    </style>
</head>

<body>
    <header>
        <h1>Leaderboard</h1>
    </header>

    <div class="container">
        <table>
            <thead>
                <tr>
                    <th>Positie</th>
                    <th>Naam</th>
                    <th>Score</th>
                    <th>Datum</th>
                </tr>
            </thead>
            <tbody id="leaderboardBody"></tbody>
        </table>

        <p id="emptyState">Geen scores gevonden.</p>

        <div class="actions">
            <button id="playAgainBtn">Opnieuw spelen</button>
            <button id="mainMenuBtn">Main Menu</button>
            <button id="clearLeaderboardBtn">Leaderboard leegmaken</button>
        </div>
    </div>

    <script>
        const leaderboardBody = document.getElementById("leaderboardBody");
        const emptyState = document.getElementById("emptyState");

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString("nl-NL");
        }

        const raw = localStorage.getItem("quizLeaderboard");
        if (!raw) {
            emptyState.style.display = "block";
        } else {
            const list = JSON.parse(raw);
            if (list.length === 0) {
                emptyState.style.display = "block";
            } else {
                emptyState.style.display = "none";

                list.forEach((entry, index) => {
                    const row = document.createElement("tr");

                    const pos = document.createElement("td");
                    pos.textContent = index + 1;

                    const name = document.createElement("td");
                    name.textContent = entry.name; // ← toont letterlijk wat je typt

                    const score = document.createElement("td");
                    score.textContent = `${entry.score} / ${entry.total}`;

                    const date = document.createElement("td");
                    date.textContent = formatDate(entry.date);

                    row.appendChild(pos);
                    row.appendChild(name);
                    row.appendChild(score);
                    row.appendChild(date);

                    leaderboardBody.appendChild(row);
                });
            }
        }

        // ⭐ Buttons werkend maken
        document.getElementById("playAgainBtn").onclick = () => {
            window.location.href = "quizanswer.html";
        };

        document.getElementById("mainMenuBtn").onclick = () => {
            window.location.href = "quiz.overzicht.php"; // pas aan naar jouw main menu pagina
        };

        document.getElementById("clearLeaderboardBtn").onclick = () => {
            localStorage.removeItem("quizLeaderboard");
            location.reload();
        };
    </script>

</body>
</html>
