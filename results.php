<?php
include 'db.php';

$results = [];

$sql = "SELECT c.fullname, c.politicalparty, COUNT(*) AS total_votes
        FROM candidate c
        LEFT JOIN vote v ON c.candidateID = v.candidateID
        GROUP BY c.candidateID
        ORDER BY total_votes DESC";

$query = $conn->query($sql);

if ($query && $query->num_rows > 0) {
    while ($row = $query->fetch_assoc()) {
        $results[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Election Results</title>
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

        .container {
            max-width: 800px;
            background: white;
            margin: 2em auto;
            padding: 2em;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1em;
        }

        th, td {
            padding: 1em;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #0066cc;
            color: white;
        }

        footer {
            background-color: #000066;
            color: white;
            text-align: center;
            padding: 1em;
            margin-top: 4em;
        }
    </style>
</head>
<body>

<header>
    <h1>Election Results</h1>
</header>

<div class="container">
    <?php if (!empty($results)): ?>
        <table>
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Party</th>
                    <th>Total Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['fullname']) ?></td>
                        <td><?= htmlspecialchars($row['politicalparty']) ?></td>
                        <td><?= htmlspecialchars($row['total_votes']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No votes have been cast yet.</p>
    <?php endif; ?>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>

</body>
</html>
