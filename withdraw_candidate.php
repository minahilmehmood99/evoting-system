<?php
include 'db.php';
session_start();

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $candidateID = $_POST['candidateID'];

    $stmt = $conn->prepare("DELETE FROM candidate WHERE candidateID = ?");
    $stmt->bind_param("i", $candidateID);

    if ($stmt->execute()) {
        $message = "✅ Candidate withdrawn successfully.";
    } else {
        $message = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Withdraw Candidacy - E-Voting System</title>
    <style>
        title {
            color: #000000;
            font-family: Arial, sans-serif;
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
            width: 500px;
            margin: 3em auto;
            background: white;
            padding: 2em;
            border-radius: 8px;
            text-align: center;
        }

        .container form button {
            margin-top: 1.5em;
            padding: 0.7em 1.5em;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin-top: 1em;
            color: green;
        }

        .error {
            color: red;
        }
    </style>
</head>
<body>

<header>
    <h1>Withdraw Candidacy</h1>
</header>

<div class="container">
    <?php if ($message): ?>
        <p class="message"><?= $message ?></p>
    <?php else: ?>
        <p>Are you sure you want to withdraw your candidacy?</p>
        <form method="POST" action="">
            <!-- You can use session or hidden input to store candidateID -->
            <input type="hidden" name="candidateID" value="<?php echo $_SESSION['candidateID'] ?? ''; ?>" required>
            <button type="submit">Yes, Withdraw</button>
        </form>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>
