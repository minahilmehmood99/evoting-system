<!-- dashboard_voter.html -->
<style>
title{
color: 000000;
font-family: arial, sans-serif;
text-align: center;}

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
text-align: center;}

.container {
    width: 80%;
    margin: 2em auto;
    background: #fff;
    padding: 2em;
    border-radius: 8px;
}
a.button {
    display: inline-block;
    margin: 1em;
    padding: 0.7em 1.5em;
    background-color: #007bff;
    color: white;
    text-decoration: none;
    border-radius: 5px;
}
a.button:hover {
    background-color: #0056b3;
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voter Dashboard - E-Voting System</title>
</head>
<body>
<header>
    <h1>Voter Dashboard</h1>
</header>
<div class="container">
    <h2>Welcome, Voter! We sincerely hope you will exercise your voting rights properly! </h2>
    <a href="vote.php" class="button">Vote Now</a>
    <a href="candidate_list.php" class="button">View Candidates</a>
    <a href="voting_schedule.php" class="button">Voting Schedule</a>
    <a href="results.php" class="button">View Results</a>
</div>
<div>
<a href="coverpage.html">Go to home</a>
</div>

<footer>
    <p>&copy; 2025 E-Voting System</p>
</footer>
</body>
</html>
