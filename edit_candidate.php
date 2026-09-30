<?php
session_start();
include 'db.php';

$message = "";

// Make sure candidate is logged in
$candidateEmail = $_SESSION['candidate_email'] ?? null;

if (!$candidateEmail) {
    die("❌ Candidate not logged in.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $party = trim($_POST['politicalparty']);
    $bio = trim($_POST['bio']);

    $stmt = $conn->prepare("UPDATE candidate SET fullname = ?, politicalparty = ?, bio = ? WHERE email = ?");
    $stmt->bind_param("ssss", $fullname, $party, $bio, $candidateEmail);

    if ($stmt->execute()) {
        $message = "✅ Profile updated successfully.";
    } else {
        $message = "❌ Update failed: " . $stmt->error;
    }
}

// Fetch candidate details to pre-fill form
$stmt = $conn->prepare("SELECT fullname, politicalparty, bio FROM candidate WHERE email = ?");
$stmt->bind_param("s", $candidateEmail);
$stmt->execute();
$stmt->bind_result($fullname, $party, $bio);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Candidate Profile - E-Voting System</title>
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
            margin-top: 10em;
            text-align: center;
        }

        .container {
            width: 500px;
            margin: 3em auto;
            background: white;
            padding: 2em;
            border-radius: 8px;
        }

        form label {
            display: block;
            margin-top: 1em;
        }

        form input,
        form textarea {
            width: 100%;
            padding: 0.5em;
            margin-top: 0.5em;
        }

        form button {
            margin-top: 1.5em;
            padding: 0.7em 1.2em;
            background-color: #17a2b8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .message {
            margin-top: 1em;
            font-weight: bold;
            color: green;
        }
    </style>
</head>
<body>

<header>
    <h1>Edit Your Profile</h1>
</header>

<div class="container">
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="fullname">Full Name:</label>
        <input type="text" id="fullname" name="fullname" value="<?= htmlspecialchars($fullname) ?>" required>

        <label for="party">Political Party:</label>
        <input type="text" id="party" name="politicalparty" value="<?= htmlspecialchars($party) ?>" required>

        <label for="bio">Bio:</label>
        <textarea id="bio" name="bio" rows="4" required><?= htmlspecialchars($bio) ?></textarea>

        <button type="submit">Update Profile</button>
    </form>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>
