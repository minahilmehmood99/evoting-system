<?php

include 'db.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST["fullname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $politicalparty = $_POST["politicalparty"];
    $bio = $_POST["bio"];

    // Insert into database
    $stmt = $conn->prepare("INSERT INTO candidate (fullname, email, password, politicalparty, bio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $fullname, $email, $password, $politicalparty, $bio);

    if ($stmt->execute()) {
        echo "✅ Candidate registered successfully.<br>";
        // Optionally redirect
        // header("Location: dashboard_candidate.html");
        // exit();
    } else {
        echo "❌ Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register as Candidate - E-Voting System</title>
    <style>
        body {
            font-family: Times New Roman, Times;
            background-color: #99CCFF;
            margin: 0;
            padding: 2em;
        }
        header{
            background-color: #000066;
            color: #fff;
            padding: 1em;
            text-align: center;
        }
        footer{
            background-color: #000066;
            color: #fff;
            padding: 1em;
            margin: 10em;
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
        form input, form textarea {
            width: 100%;
            padding: 0.5em;
            margin-top: 0.5em;
        }
        form button {
            margin-top: 1em;
            padding: 0.7em 1.2em;
            background-color: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <h1>Apply as Candidate</h1>
    </header>

    <div class="container">
        <form id="candidateForm" method="POST" action="">
            <label for="fullname">Full Name:</label>
            <input type="text" id="fullname" name="fullname" required>

            <label for="party">Political Party:</label>
            <input type="text" id="party" name="politicalparty" required>

            <label for="bio">Short Bio:</label>
            <textarea id="bio" name="bio" rows="4" required></textarea>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Create Password:</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm-password">Confirm Password:</label>
            <input type="password" id="confirm-password" name="confirm-password" required>

            <button type="submit">Register</button>
        </form>

        <div>
                       <a href="coverpage.html">Go back</a>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 E-Voting System</p>
    </footer>

    <script>
        document.getElementById('candidateForm').addEventListener('submit', function (event) {
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (password !== confirmPassword) {
                event.preventDefault();
                alert("Passwords do not match!");
            }
        });
    </script>
</body>
</html>
