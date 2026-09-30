<?php
include 'db.php';
session_start();

$registrationSuccess = false;
$errorMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("INSERT INTO voter (fullname, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $fullname, $email, $password);

    if ($stmt->execute()) {
        $registrationSuccess = true;
        header("Location: voter_dashboard.html"); // Or .html if you're not using a PHP dashboard
        exit();
    } else {
        $errorMessage = "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>VOTER REGISTRATION</title>
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
            border: 1px solid;
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
            background: #fff;
            padding: 2em;
            border-radius: 8px;
        }

        form label {
            display: block;
            margin-top: 1em;
        }

        form input {
            width: 100%;
            padding: 0.5em;
            margin-top: 0.5em;
        }

        form button {
            margin-top: 1em;
            padding: 0.7em 1.2em;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-link {
            display: block;
            margin-top: 1em;
            text-align: center;
        }

        .error {
            color: red;
            margin-top: 1em;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 1em;
            text-align: center;
        }
    </style>
</head>
<body>

<header>
    <h1>VOTER REGISTRATION</h1>
</header>

<div class="container">
    <?php if ($errorMessage): ?>
        <div class="error"><?= $errorMessage ?></div>
    <?php endif; ?>

    <form id="voterForm" method="POST" action="">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Create Password:</label>
        <input type="password" id="password" name="password" required>

        <label for="confirm-password">Confirm Password:</label>
        <input type="password" id="confirm-password" required>

        <button type="submit">Register</button>
    </form>

    <a class="back-link" href="coverpage.html">Go Back</a>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

<script>
    document.getElementById('voterForm').addEventListener('submit', function (event) {
        const password = document.getElementById('password').value;
        const confirmPassword = document.getElementById('confirm-password').value;

        if (password !== confirmPassword) {
            event.preventDefault();
            alert("Passwords do not match. Please try again.");
        }
    });
</script>

</body>
</html>
