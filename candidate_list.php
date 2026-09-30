<?php
include 'db.php';

$candidates = [];

$result = $conn->query("SELECT * FROM candidate");

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $candidates[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registered Candidates - E-Voting System</title>
    <style>
        body {
            font-family: "Times New Roman", Times;
            background-color: #99CCFF;
            margin: 0;
            padding: 2em;
        }

        header {
            background-color: #000066;
            color: #fff;
            padding: 1em;
            text-align: center;
        }

        footer {
            background-color: #000066;
            color: #fff;
            padding: 1em;
            text-align: center;
            margin-top: 5em;
        }

        .container {
            width: 80%;
            margin: 2em auto;
            background: white;
            padding: 2em;
            border-radius: 8px;
        }

        .candidate {
            margin-bottom: 2em;
            border-bottom: 1px solid #ccc;
            padding-bottom: 1em;
        }

        .candidate h3 {
            margin: 0.5em 0;
            color: #003366;
        }

        .candidate p {
            margin: 0.3em 0;
        }
    </style>
</head>
<body>

<header>
    <h1>Registered Candidates</h1>
</header>

<div class="container">
    <?php if (!empty($candidates)): ?>
        <?php foreach ($candidates as $cand): ?>
            <div class="candidate">
                <h3><?= htmlspecialchars($cand['fullName']) ?></h3>
                <p><strong>Party:</strong> <?= htmlspecialchars($cand['politicalParty']) ?></p>
                <p><strong>Bio:</strong> <?= nl2br(htmlspecialchars($cand['bio'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No candidates found in the database.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>
