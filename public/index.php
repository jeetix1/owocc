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

$username = $_SESSION['username'];

// Connect to the database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare the statement to check if the user exists
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

// If the cookie count is not set, initialize it to 0
if (!isset($_SESSION['cookieCount'])) {
    $_SESSION['cookieCount'] = 0;
}

// Increment the cookie count and update the database
$_SESSION['cookieCount']++;

$cookieCount = $_SESSION['cookieCount'];

// Prepare the statement to update the user's cookie count
$stmt = $conn->prepare("UPDATE users SET cookie_count = ? WHERE username = ?");
$stmt->bind_param("is", $cookieCount, $username);
$stmt->execute();
$stmt->close();

// Retrieve the top players from the database
$topPlayersQuery = "SELECT username, cookie_count, updated_at FROM users ORDER BY cookie_count DESC LIMIT 20";
$topPlayersResult = $conn->query($topPlayersQuery);
$topPlayers = array();

if ($topPlayersResult->num_rows > 0) {
    while ($row = $topPlayersResult->fetch_assoc()) {
        $now = new DateTime();
        $updated_at = new DateTime($row['updated_at']);
        $interval = $now->diff($updated_at);

        $totalMinutes = $interval->days * 24 * 60;
        $totalMinutes += $interval->h * 60;
        $totalMinutes += $interval->i;

        $active = ($totalMinutes < 480) ? true : false;

        $player = array(
            'username' => $row['username'],
            'cookieCount' => $row['cookie_count'],
            'active' => $active
        );
        $topPlayers[] = $player;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>OwO Cookie Clicker!</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
    <link rel="stylesheet" type="text/css" href="assets/css/flip_text_animation.css">
    <!-- <img id="CookieKing" src="./img/kingfluffy.png" alt="Ckookie King Fluffy! OwO"/> -->
    <div class="waviy">
        <h1>
            <span style="--i:1">O</span>
            <span style="--i:2">w</span>
            <span style="--i:3">O</span>
            <span style="--i:4"> </span>
            <span style="--i:5">C</span>
            <span style="--i:6">o</span>
            <span style="--i:7">o</span>
            <span style="--i:8">k</span>
            <span style="--i:9">i</span>
            <span style="--i:10">e</span>
            <span style="--i:11"> </span>
            <span style="--i:12">C</span>
            <span style="--i:13">l</span>
            <span style="--i:14">i</span>
            <span style="--i:15">c</span>
            <span style="--i:16">k</span>
            <span style="--i:17">e</span>
            <span style="--i:18">r</span>
            <span style="--i:19">!</span>
        </h1>
    </div>
    <!-- <p>The Epitome of Stable Code!<br> Developed in Production and Merged Straight to Master!<br> What Could Possibly Go Wrong? 😄</p> -->
    <p class="subtitle">
        Crafted in the Wild Jungles of Production, Merged Right into Master with a YOLO Spirit! 🚀<br>
        Because Who Needs Testing? Code is Our Canvas, and Bugs are Just Unexpected Features!<br>
        What Can Pawsibly Go Wrong? 🫠
    </p>
    <?php
    function getCookieTitle($cookieCount, $conn)
    {
        $stmt = $conn->prepare("SELECT title FROM cookie_titles WHERE ? BETWEEN min_count AND max_count");
        $stmt->bind_param("i", $cookieCount);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $conn->close();

        if ($row) {
            return $row['title'];
        }

        // Default title if count doesn't fall within any range
        return "Cookie Rookie!";
    }
    ?>

    <div class="cookie-count">
        <span class="username">
            <?= $username ?>
        </span>
        <?= getCookieTitle($_SESSION['cookieCount'], $conn) ?>
        <br><br><br>
        <div id="cookieContainer" class="count">
            <span id="cookieCount">
                <?= $_SESSION['cookieCount'] ?>
            </span>
            <span> Cookies!</span>
        </div>
    </div>

    <!-- Cookie image to click and add a cookie -->
    <div class="cookie-container" id="cookieBtn">
        <img src="cookie.png" alt="Cookie">
    </div>

    <!-- Small cookie image to display when the cookie image is clicked -->
    <img class="small-cookie" id="smallCookie" src="cookie-small.png" alt="Small Cookie">

    <!-- Scoreboard to display top 20 players and their scores -->
    <div class="scoreboard">
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
                    $activeClass = $player['active'] ? 'active-player' : '';
                    echo "<tr class='$activeClass'>";
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
        <script src="assets/vendor/jquery-3.6.0.min.js"></script>
        <script src="script.js"></script>
    </div>
</body>
</html>