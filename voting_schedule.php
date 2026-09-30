<?php
include 'db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voting Schedule - E-Voting System</title>
    <style>
        title {
            color: #000000;
            font-family: arial, sans-serif;
            text-align: center;
        }

        body {
            font-family: Times New Roman, Times;
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
            margin: 10em;
            text-align: center;
        }

        .container {
            width: 600px;
            margin: 2em auto;
            background: white;
            padding: 2em;
            border-radius: 8px;
        }

        .election {
            margin-bottom: 1.5em;
            padding-bottom: 1em;
            border-bottom: 1px solid #ccc;
        }
    </style>
</head>
<body>

<header>
    <h1>Voting Schedule</h1>
</header>

<div class="container">
    <h2>Upcoming Elections</h2>
    <?php
    $result = $conn->query("SELECT * FROM election");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div class='election'>";
            echo "<p><strong>Start Date:</strong> " . htmlspecialchars($row['startDate']) . "</p>";
            echo "<p><strong>End Date:</strong> " . htmlspecialchars($row['endDate']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>No elections scheduled.</p>";
    }
    ?>

    <h3>Important Dates (Static)</h3>
    <p>Voting starts on: <strong>May 25, 2025</strong></p>
    <p>Voting ends on: <strong>May 30, 2025</strong></p>
    <p>Results announced on: <strong>June 1, 2025</strong></p>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>
