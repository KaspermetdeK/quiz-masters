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
            font-size: 26px;
            font-weight: bold;
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
            word-break: break-word;
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

        @media (max-width: 650px) {
            header {
                font-size: 22px;
            }

            .container {
                padding: 20px;
            }

            button {
                width: 100%;
                font-size: 15px;
            }
        }

        @media (max-width: 550px) {
            th {
                display: none;
            }

            table, tbody, tr, td {
                display: block;
                width: 100%;
            }

            tr {
                margin-bottom: 15px;
                background: white;
                padding: 12px;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }

            td {
                display: flex;
                justify-content: space-between;
                padding: 8px 5px;
                font-size: 14px;
            }

            td::before {
                content: attr(data-label);
                font-weight: bold;
                color: #1A3A5F;
                margin-right: 10px;
            }
        }
    </style>
</head>

<body>
    <header>Leaderboard</header>

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

                    row.innerHTML = `
                        <td data-label="Positie">${index + 1}</td>
                        <td data-label="Naam">${entry.name.replace(/</g, "&lt;")}</td>
                        <td data-label="Score">${entry.score} / ${entry.total}</td>
                        <td data-label="Datum">${formatDate(entry.date)}</td>
                    `;

                    leaderboardBody.appendChild(row);
                });
            }
        }

        document.getElementById("playAgainBtn").onclick = () => {
            window.location.href = "quiz.overzicht.php";
        };

        document.getElementById("mainMenuBtn").onclick = () => {
            window.location.href = "quiz.overzicht.php";
        };

        document.getElementById("clearLeaderboardBtn").onclick = () => {
            localStorage.removeItem("quizLeaderboard");
            location.reload();
        };
    </script>

</body>
</html>

