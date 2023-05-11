<?php
include './../config.php';
session_start();

// If the username is not set in the session, check if it is in the URL parameters
if (!isset($_SESSION['username'])) {
    if (isset($_GET['user'])) {
        $_SESSION['username'] = $_GET['user'];
    } else {
        // Redirect to login.php to generate a random username
        header('Location: login.php');
        exit;
    }
}

// Get the username from the session
$username = $_SESSION['username'];

// Connect to the database and check if the user exists
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // If the user does not exist, create a new entry with cookie_count set to 0
    $stmt = $conn->prepare("INSERT INTO users (username, cookie_count) VALUES (?, 0)");
    $stmt->bind_param("s", $username);
    $stmt->execute();
} else {
    // If the user exists, get their cookie count from the database and set it in the session
    $row = $result->fetch_assoc();
    $_SESSION['cookieCount'] = $row['cookie_count'];
}

$stmt->close();
$conn->close();

// If the cookie count is not set, initialize it to 0
if (!isset($_SESSION['cookieCount'])) {
    $_SESSION['cookieCount'] = 0;
}

// Increment the cookie count and update the database
$_SESSION['cookieCount']++;

$cookieCount = $_SESSION['cookieCount'];

$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("UPDATE users SET cookie_count = ? WHERE username = ?");
$stmt->bind_param("is", $cookieCount, $username);
$stmt->execute();
$stmt->close();
$conn->close();

// Retrieve the top 10 players from the database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$topPlayersQuery = "SELECT username, cookie_count FROM users ORDER BY cookie_count DESC LIMIT 10";
$topPlayersResult = $conn->query($topPlayersQuery);
$topPlayers = array();

if ($topPlayersResult->num_rows > 0) {
    while ($row = $topPlayersResult->fetch_assoc()) {
        $player = array(
            'username' => $row['username'],
            'cookieCount' => $row['cookie_count']
        );
        $topPlayers[] = $player;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cookie Clicker</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<h1>Cookie Clicker</h1>

<!-- Display the current number of cookies -->
<div class="cookie-count">Cookies: <span id="cookieCount"><?= $_SESSION['cookieCount'] ?></span>

</div>

<!-- Cookie image to click and add a cookie -->
<div class="cookie-container" id="cookieBtn">
    <img src="cookie.png" alt="Cookie">
</div>

<!-- Small cookie image to display when the cookie image is clicked -->
<img class="small-cookie" id="smallCookie" src="cookie-small.png" alt="Small Cookie">

<!-- Scoreboard to display top 10 players and their scores -->
<h2>Scoreboard</h2>
<table>
    <thead>
        <tr>
            <th>Rank</th>
            <th>Player</th>
            <th>Score</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $rank = 1;
        foreach ($topPlayers as $player) {
            echo "<tr>";
            echo "<td>" . $rank . "</td>";
            echo "<td>" . $player['username'] . "</td>";
            echo "<td>" . $player['cookieCount'] . "</td>";
            echo "</tr>";
            $rank++;
        }
        ?>
    </tbody>
</table>

<!-- JavaScript to send an AJAX request when the cookie image is clicked and to display a small cookie at a random location on the screen -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="script.js"></script>

</body>
</html>