<?php
include 'db.php';
$actionMessage = "";
$searchResults = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // ADD USER
    if (isset($_POST['add_user'])) {
        $fullname = trim($_POST['fullname'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');
        $role = trim($_POST['role'] ?? '');

        if ($fullname && $email && $password && in_array($role, ['voter', 'candidate'])) {
            $stmt = $conn->prepare("INSERT INTO $role (fullName, email, password) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $fullname, $email, $password);
                $stmt->execute();
                $actionMessage = "✅ $role added successfully.";
            } else {
                $actionMessage = "❌ Error preparing insert statement.";
            }
        } else {
            $actionMessage = "❌ Missing required fields or invalid role.";
        }
    }

    // SEARCH USER
    elseif (isset($_POST['search_user'])) {
        $search = trim($_POST['search_query'] ?? '');
        $searchTerm = "%$search%";

        $q1 = $conn->prepare("SELECT 'voter' AS role, voterID AS id, fullName, email FROM voter WHERE fullName LIKE ? OR voterID LIKE ?");
        $q1->bind_param("ss", $searchTerm, $search);
        $q1->execute();
        $voters = $q1->get_result();

        $q2 = $conn->prepare("SELECT 'candidate' AS role, candidateID AS id, fullName, email FROM candidate WHERE fullName LIKE ? OR candidateID LIKE ?");
        $q2->bind_param("ss", $searchTerm, $search);
        $q2->execute();
        $candidates = $q2->get_result();

        while ($row = $voters->fetch_assoc()) $searchResults[] = $row;
        while ($row = $candidates->fetch_assoc()) $searchResults[] = $row;

        $actionMessage = count($searchResults) . " result(s) found.";
    }

    // UPDATE USER
    elseif (isset($_POST['update_user'])) {
        $id = trim($_POST['update_id'] ?? '');
        $name = trim($_POST['update_name'] ?? '');
        $email = trim($_POST['update_email'] ?? '');
        $role = trim($_POST['update_role'] ?? '');

        if ($id && $name && $email && in_array($role, ['voter', 'candidate'])) {
            $idColumn = $role === 'voter' ? 'voterID' : 'candidateID';
            $stmt = $conn->prepare("UPDATE $role SET fullName=?, email=? WHERE $idColumn=?");
            $stmt->bind_param("ssi", $name, $email, $id);
            $stmt->execute();
            $actionMessage = $stmt->affected_rows > 0 ? "✅ $role updated successfully." : "❌ No records updated.";
        } else {
            $actionMessage = "❌ Invalid input for update.";
        }
    }

    // DELETE USER BY NAME + ROLE
    elseif (isset($_POST['delete_user'])) {
        $name = trim($_POST['delete_name'] ?? '');
        $role = trim($_POST['delete_role'] ?? '');

        if ($name && in_array($role, ['voter', 'candidate'])) {
            $stmt = $conn->prepare("DELETE FROM $role WHERE BINARY fullName = ?");
            $stmt->bind_param("s", $name);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                $actionMessage = "✅ $role deleted successfully.";
            } else {
                $actionMessage = "❌ No $role found with the given name (case-sensitive).";
            }
        } else {
            $actionMessage = "❌ Please provide a valid name and role to delete.";
        }
    }

    // ADD TO CANDIDATE LIST 
    elseif (isset($_POST['add_to_list'])) {
        $cname = trim($_POST['cname'] ?? '');
        $cparty = trim($_POST['cparty'] ?? '');
        $cbio = trim($_POST['cbio'] ?? '');

        if ($cname && $cparty && $cbio) {
            $email = trim($_POST['email'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($cname && $email && $password && $cparty && $cbio) {
    $stmt = $conn->prepare("INSERT INTO candidate (fullName, email, password, politicalparty, bio) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $cname, $email, $password, $cparty, $cbio);}

            $stmt->execute();
            $actionMessage = "✅ Candidate added to list.";
        } else {
            $actionMessage = "❌ Please fill out all required candidate fields.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Panel - E-Voting System</title>
  <style>
    body {
        font-family: "Times New Roman", Times;
        background-color: #99CCFF;
        margin: 0;
        padding: 2em;
    }
    header, footer {
        background-color: #000066;
        color: white;
        text-align: center;
        padding: 1em;
    }
    .container {
        max-width: 700px;
        background: white;
        padding: 2em;
        margin: 2em auto;
        border-radius: 8px;
    }
    section {
        margin-bottom: 2em;
    }
    input, select, textarea, button {
        display: block;
        width: 100%;
        margin: 0.5em 0;
        padding: 0.5em;
    }
    button {
        background-color: #0066cc;
        color: white;
        border: none;
        cursor: pointer;
    }
    button:hover {
        background-color: #004a99;
    }
    .results {
        background: #e6f7ff;
        padding: 1em;
        margin-top: 1em;
        border: 1px solid #b3e0ff;
        border-radius: 5px;
    }
  </style>
</head>
<body>
<header><h1>Admin Panel</h1></header>
<div class="container">
<?php if (!empty($actionMessage)): ?>
    <p><strong><?= htmlspecialchars($actionMessage) ?></strong></p>
<?php endif; ?>

<!-- ADD -->
<form method="POST">
  <section>
    <h2>Add Voter</h2>
    <input name="fullname" type="text" placeholder="Full Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="text" placeholder="Password" required>
    <select name="role" required>
      <option value="">Select Role</option>
      <option value="voter">Voter</option>
      <option value="candidate">Candidate</option>
    </select>
    <button name="add_user" type="submit">Add</button>
  </section>
</form>

<!-- SEARCH -->
<form method="POST">
  <section>
    <h2>Search Voter or Candidate</h2>
    <input name="search_query" type="text" placeholder="Enter name or ID">
    <button name="search_user">Search</button>
    <?php if (!empty($searchResults)): ?>
    <div class="results">
        <?php foreach ($searchResults as $user): ?>
            <p><strong><?= htmlspecialchars($user['role']) ?>:</strong> <?= htmlspecialchars($user['fullName']) ?> (<?= htmlspecialchars($user['email']) ?>) - ID: <?= htmlspecialchars($user['id']) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
  </section>
</form>

<!-- UPDATE -->
<form method="POST">
  <section>
    <h2>Update Voter or Candidate</h2>
    <input name="update_id" type="text" placeholder="Enter ID to update" required>
    <input name="update_name" type="text" placeholder="New Name" required>
    <input name="update_email" type="email" placeholder="New Email" required>
    <select name="update_role" required>
      <option value="">Select Role</option>
      <option value="voter">Voter</option>
      <option value="candidate">Candidate</option>
    </select>
    <button name="update_user" type="submit">Update</button>
  </section>
</form>

<!-- DELETE -->
<form method="POST">
  <section>
    <h2>Delete Voter or Candidate</h2>
    <input name="delete_name" type="text" placeholder="Enter Full Name to delete" required>
    <select name="delete_role" required>
      <option value="">Select Role</option>
      <option value="voter">Voter</option>
      <option value="candidate">Candidate</option>
    </select>
    <button name="delete_user" type="submit" style="background-color:#cc0000;">Delete</button>
  </section>
</form>

<!-- ADD TO CANDIDATE LIST -->
<form method="POST">
  <section>
    <h2>Add Candidate to Candidate List</h2>
    <input name="cname" type="text" placeholder="Candidate Name" required>
    <input name="email" type="email" placeholder="Email" required>
    <input name="password" type="text" placeholder="Password" required>
    <input name="cparty" type="text" placeholder="Political Party" required>
    <textarea name="cbio" placeholder="Short Bio" rows="4" required></textarea>
    <button name="add_to_list" type="submit">Add to List</button>
  </section>
</form>

</div>
<div class="back-link"><a href="coverpage.html">Go back to home</a></div>
<footer>&copy; 2025 E-Voting System</footer>
</body>
</html>
