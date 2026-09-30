<?php
session_start();
include 'db.php';
ini_set('display_errors', 1);
error_reporting(E_ALL);

$message = "";

// Get logged-in voter's ID
$voterID = $_SESSION['voterID'] ?? null;

if (!$voterID) {
    die("❌ Voter not logged in.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateID = $_POST['candidate'];

    // Check if the voter has already voted
    $check = $conn->prepare("SELECT * FROM vote WHERE voterID = ?");
    $check->bind_param("i", $voterID);
    $check->execute();
    $checkResult = $check->get_result();

    if ($checkResult->num_rows > 0) {
        $message = "❌ You have already cast your vote.";
    } else {
        // Insert into vote table
        $stmt = $conn->prepare("INSERT INTO vote (voterID, candidateID) VALUES (?, ?)");
        $stmt->bind_param("ii", $voterID, $candidateID);
        if ($stmt->execute()) {

            // ✅ Also insert into takesPart table
            $takesPart = $conn->prepare("INSERT INTO takesPart (voterID, candidateID) VALUES (?, ?)");
            $takesPart->bind_param("ii", $voterID, $candidateID);
            $takesPart->execute();

            $message = "✅ Your vote has been cast successfully!";
        } else {
            $message = "❌ Error casting vote: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote - E-Voting System</title>
    <style>
        title {
            color: #000000;
            font-family: arial, sans-serif;
            text-align: center;
        }

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
            margin-top: 10em;
            text-align: center;
        }

        .container {
            width: 600px;
            margin: 3em auto;
            background: #fff;
            padding: 2em;
            border-radius: 8px;
        }

        form label {
            display: block;
            margin-top: 1em;
        }

        form select {
            width: 100%;
            padding: 0.5em;
            margin-top: 0.5em;
        }

        form button {
            margin-top: 1.5em;
            padding: 0.8em 1.5em;
            background-color: #388e3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin-top: 1em;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header>
    <h1>Cast Your Vote</h1>
</header>

<div class="container">
    <?php if ($message): ?>
        <div class="message"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="candidate">Select a Candidate:</label>
        <select id="candidate" name="candidate" required>
            <option value="">-- Select --</option>
            <?php
            $result = $conn->query("SELECT candidateID, fullname, politicalparty FROM candidate");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='" . $row['candidateID'] . "'>" . $row['fullname'] . " (" . $row['politicalparty'] . ")</option>";
            }
            ?>
        </select>
        <button type="submit">Submit Vote</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>