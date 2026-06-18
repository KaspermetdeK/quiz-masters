<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$host = 'localhost';
$dbname = 'quizmaker';
$username = 'bit_academy';
$password = 'bit_academy';

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Database fout: " . $conn->connect_error);
}

$user_id = intval($_SESSION['user_id']);


$user_stmt = $conn->prepare("
    SELECT username, aangemaakt_op
    FROM users 
    WHERE user_id = ?
");
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user = $user_stmt->get_result()->fetch_assoc();

if (!$user) {
    die("Gebruiker niet gevonden.");
}


$stats_stmt = $conn->prepare("
    SELECT 
        COUNT(*) AS quizzes_gespeeld,
        AVG(score) AS gemiddelde_score,
        MAX(score) AS beste_score
    FROM user_quiz_scores
    WHERE user_id = ?
");
$stats_stmt->bind_param("i", $user_id);
$stats_stmt->execute();
$stats = $stats_stmt->get_result()->fetch_assoc();


$badges_stmt = $conn->prepare("
    SELECT badge_name, badge_icon 
    FROM user_badges 
    WHERE user_id = ?
");
$badges_stmt->bind_param("i", $user_id);
$badges_stmt->execute();
$badges = $badges_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn account</title>

    <style>
        body {
            margin: 0;
            background-color: #F4F7FA;
            font-family: Arial, sans-serif;
            padding: 12px;
        }

        header {
            background: #1A3A5F;
            color: white;
            padding: 16px;
            text-align: center;
            font-size: 22px;
            font-weight: bold;
            border-bottom: 4px solid #162F4D;
            border-radius: 0 0 12px 12px;
        }

        .container {
            max-width: 900px;
            margin: 20px auto;
            background: white;
            padding: 18px;
            border-radius: 14px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.12);
        }

        .profile-header {
            margin-bottom: 20px;
        }

        .profile-info h2 {
            margin: 0;
            color: #1A3A5F;
        }

        .profile-info p {
            margin: 4px 0;
            color: #555;
        }

        .section {
            margin-top: 20px;
        }

        .section h3 {
            margin-bottom: 10px;
            color: #1A3A5F;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 12px;
        }

        .stat-box {
            background: #EEF3F8;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
        }

        .stat-label {
            font-size: 13px;
            color: #555;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1A3A5F;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .badge {
            background: #EEF3F8;
            padding: 8px 12px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 14px;
        }

        .btn-row {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #1A3A5F;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background: #162F4D;
        }

        .btn-secondary {
            background: #777;
        }

        .btn-secondary:hover {
            background: #555;
        }
    </style>
</head>

<body>

<header>Mijn account</header>

<div class="container">

    <div class="profile-header">
        <div class="profile-info">
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p>Lid sinds: <?= date('d-m-Y', strtotime($user['aangemaakt_op'])) ?></p>
        </div>
    </div>

    <div class="section">
        <h3>Statistieken</h3>
        <div class="stats-grid">
            <div class="stat-box">
                <div class="stat-label">Quizzes gespeeld</div>
                <div class="stat-value"><?= intval($stats['quizzes_gespeeld'] ?? 0) ?></div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Gemiddelde score</div>
                <div class="stat-value">
                    <?= $stats['gemiddelde_score'] ? round($stats['gemiddelde_score'], 1) : 0 ?>
                </div>
            </div>
            <div class="stat-box">
                <div class="stat-label">Beste score</div>
                <div class="stat-value"><?= intval($stats['beste_score'] ?? 0) ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Badges</h3>
        <div class="badges">
            <?php if ($badges->num_rows > 0): ?>
                <?php while ($badge = $badges->fetch_assoc()): ?>
                    <div class="badge">
                        <?= htmlspecialchars($badge['badge_name']) ?>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>Je hebt nog geen badges.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="btn-row">
        <a href="quiz.overzicht.php" class="btn">Naar quiz overzicht</a>
        <a href="logout.php" class="btn btn-secondary">Uitloggen</a>
    </div>

</div>

</body>
</html>
