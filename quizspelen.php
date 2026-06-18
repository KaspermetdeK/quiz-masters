<?php
$host = 'localhost';
$dbname = 'quizmaker';
$username = 'bit_academy';
$password = 'bit_academy';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database fout: " . $conn->connect_error);
}

if (!isset($_GET['id'])) {
    die("Geen quiz geselecteerd.");
}

$quiz_id = intval($_GET['id']);

$quiz = $conn->query("SELECT * FROM quizzes WHERE quiz_id = $quiz_id")->fetch_assoc();
if (!$quiz) {
    die("Quiz niet gevonden.");
}

$vragen = $conn->query("SELECT * FROM vragen WHERE quiz_id = $quiz_id ORDER BY vraag_id ASC");
$vragen_lijst = $vragen->fetch_all(MYSQLI_ASSOC);

$index = isset($_GET['q']) ? intval($_GET['q']) : 0;

if ($index >= count($vragen_lijst)) {

    $score = intval($_COOKIE["quiz_score"] ?? 0);
    $total = count($vragen_lijst);

    $totalTime = intval($_COOKIE["quiz_time"] ?? 0);

    setcookie("quiz_score", "", time() - 3600);
    setcookie("quiz_time", "", time() - 3600);
    ?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Resultaat</title>

<style>
    body {
        background-color: #e9eff6;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
    }

    .box {
        background: white;
        padding: 30px;
        max-width: 420px;
        width: 100%;
        border-radius: 16px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        text-align: center;
    }

    h1, h2 {
        color: #1A3A5F;
    }

    input {
        padding: 12px;
        width: 100%;
        border-radius: 10px;
        border: 1px solid #ccc;
        margin-top: 10px;
        font-size: 16px;
        box-sizing: border-box;
    }

    button {
        padding: 14px;
        width: 100%;
        background: #1A3A5F;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 16px;
        margin-top: 15px;
    }

    button:hover {
        background: #162F4D;
    }
</style>
</head>

<body>

<div class="box">
    <h1>Je bent klaar!</h1>
    <p>Je score:</p>
    <h2><?= $score ?> / <?= $total ?></h2>

    <p>Tijd bezig:</p>
    <h2>
        <?php
        $min = floor($totalTime / 60);
        $sec = $totalTime % 60;
        echo $min . ":" . str_pad($sec, 2, "0", STR_PAD_LEFT);
        ?>
    </h2>

    <p>Naam voor leaderboard:</p>
    <input type="text" id="playerName" placeholder="Jouw naam">

    <button onclick="saveScore()">Opslaan</button>
    <button onclick="window.location.href='quiz.overzicht.php'">Terug naar menu</button>
</div>

<script>
function saveScore() {
    const name = document.getElementById("playerName").value.trim();
    if (!name) return;

    const score = <?= $score ?>;
    const total = <?= $total ?>;

    const existing = localStorage.getItem("quizLeaderboard");
    const leaderboard = existing ? JSON.parse(existing) : [];

    leaderboard.push({
        name,
        score,
        total,
        date: new Date().toISOString()
    });

    leaderboard.sort((a, b) => b.score - a.score);

    localStorage.setItem("quizLeaderboard", JSON.stringify(leaderboard));

    window.location.href = "leaderboard.php";
}
</script>

</body>
</html>

<?php
exit;
}

$vraag = $vragen_lijst[$index];
$vraag_id = $vraag['vraag_id'];

$antwoorden = $conn->query("SELECT * FROM antwoorden WHERE vraag_id = $vraag_id")->fetch_all(MYSQLI_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $gekozen = intval($_POST["antwoord"]);
    $correct = $conn->query("SELECT is_correct FROM antwoorden WHERE antwoord_id = $gekozen")->fetch_assoc()['is_correct'];

    $huidige_score = intval($_COOKIE["quiz_score"] ?? 0);
    if ($correct == 1) {
        $huidige_score++;
    }

    setcookie("quiz_score", $huidige_score, time() + 3600);

    header("Location: quizspelen.php?id=$quiz_id&q=" . ($index + 1));
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($quiz['titel']) ?></title>

<style>
    body {
        background-color: #F4F7FA;
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 20px;
    }

    header {
        background: #1A3A5F;
        color: white;
        padding: 20px;
        text-align: center;
        font-size: 26px;
        font-weight: bold;
        border-bottom: 4px solid #162F4D;
        border-radius: 0 0 12px 12px;
    }

    .container {
        max-width: 700px;
        margin: 30px auto;
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 6px 14px rgba(0,0,0,0.12);
    }

    .time-spent {
        font-size: 18px;
        font-weight: bold;
        color: #1A3A5F;
        margin-bottom: 15px;
    }

    .progress-wrapper {
        width: 100%;
        background-color: #dce4ef;
        border-radius: 10px;
        height: 16px;
        margin-bottom: 20px;
        overflow: hidden;
    }

    .progress-bar {
        height: 100%;
        width: 0%;
        background-color: #1A3A5F;
        transition: width 0.4s ease;
    }

    h2, h3 {
        color: #1A3A5F;
    }

    label {
        display: block;
        background: #eef3fa;
        padding: 14px;
        border-radius: 10px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: 0.25s;
        font-size: 16px;
    }

    label:hover {
        background: #dce6f3;
    }

    input[type="radio"] {
        margin-right: 10px;
        transform: scale(1.2);
    }

    button {
        padding: 14px;
        width: 100%;
        background: #1A3A5F;
        color: white;
        border: none;
        border-radius: 10px;
        cursor: pointer;
        font-size: 17px;
        margin-top: 20px;
    }

    button:hover {
        background: #162F4D;
    }
</style>
</head>

<body>

<header><?= htmlspecialchars($quiz['titel']) ?></header>

<div class="container">

    <div class="time-tspen">
        Tijd bezig: <span id="timeSpent">0:00</span>
    </div>

    <div class="progress-wrapper">
        <div id="progressBar" class="progress-bar"></div>
    </div>

    <h2>Vraag <?= $index + 1 ?> van <?= count($vragen_lijst) ?></h2>
    <h3><?= htmlspecialchars($vraag['vraagtekst']) ?></h3>

    <form method="POST">
        <?php foreach ($antwoorden as $antwoord): ?>
            <label>
                <input type="radio" name="antwoord" value="<?= $antwoord['antwoord_id'] ?>" required>
                <?= htmlspecialchars($antwoord['antwoordtekst']) ?>
            </label>
        <?php endforeach; ?>

        <button type="submit">Volgende</button>
    </form>
</div>

<script>
let current = <?= $index + 1 ?>;
let total = <?= count($vragen_lijst) ?>;

function updateProgress() {
    const percent = (current / total) * 100;
    document.getElementById("progressBar").style.width = percent + "%";
}
updateProgress();

let timeSpent = <?= intval($_COOKIE["quiz_time"] ?? 0) ?>;

function updateStopwatch() {
    timeSpent++;
    document.cookie = "quiz_time=" + timeSpent + "; path=/";

    let minutes = Math.floor(timeSpent / 60);
    let seconds = timeSpent % 60;

    document.getElementById("timeSpent").textContent =
        minutes + ":" + (seconds < 10 ? "0" + seconds : seconds);
}

setInterval(updateStopwatch, 1000);
</script>

</body>
</html>
