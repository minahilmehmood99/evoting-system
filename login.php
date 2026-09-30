<?php
include 'db.php';
session_start();

$loginError = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"]; // voter / candidate / admin

    if (!in_array($role, ["voter", "candidate", "admin"])) {
        echo "❌ Invalid role selected.";
        exit();
    }

    $stmt = $conn->prepare("SELECT * FROM $role WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();

        $_SESSION["role"] = $role;

        // Set role-specific session values
        if ($role === "voter") {
            $_SESSION["voterID"] = $user["voterID"];
            $_SESSION["voter_email"] = $user["email"];
        } elseif ($role === "candidate") {
            $_SESSION["candidateID"] = $user["candidateID"];
            $_SESSION["candidate_email"] = $user["email"];
        } elseif ($role === "admin") {
            $_SESSION["admin_email"] = $user["email"];
        }

        // Redirect to respective dashboard
        header("Location: " . $role . "_dashboard.php");
        exit();
    } else {
        $loginError = "❌ Invalid login credentials.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - E-Voting System</title>
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
            margin-top: 5em;
            text-align: center;
        }

        .container {
            width: 400px;
            margin: 3em auto;
            background: #fff;
            padding: 1em;
            border-radius: 8px;
            border: 1px solid;
        }

        form label {
            display: block;
            margin-top: 1em;
        }

        form input,
        form select {
            width: 100%;
            padding: 0.5em;
            margin-top: 0.5em;
        }

        form button {
            margin-top: 2em;
            padding: 1em 1.2em;
            background-color: #0066FF;
            color: #fff;
            border: solid;
            border-radius: 4px;
            cursor: pointer;
        }

        .back-link {
            text-align: center;
            margin-top: 2em;
        }

        .error {
            color: red;
            margin-top: 1em;
            text-align: center;
        }
    </style>
</head>

<body>
<header>
    <h1>Login</h1>
</header>

<div class="container">
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="role">Select your role:</label>
        <select id="role" name="role" required>
            <option value="">-- Select Role --</option>
            <option value="voter">Voter</option>
            <option value="candidate">Candidate</option>
            <option value="admin">Admin</option>
        </select>
        
        <button type="submit">Login</button>
    </form>

    <?php if (!empty($loginError)): ?>
        <div class="error"><?= $loginError ?></div>
    <?php endif; ?>
</div>

<div class="back-link">
    <a href="coverpage.html">Go back</a>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>
</body>
</html>
