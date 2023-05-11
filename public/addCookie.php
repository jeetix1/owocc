<?php
include './../config.php';
session_start();

// If the username is not set in the session, redirect to login.php
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// If the cookie count is not set, initialize it to 0
if (!isset($_SESSION['cookieCount'])) {
    $_SESSION['cookieCount'] = 0;
}

// Increment the cookie count and update the database
$_SESSION['cookieCount']++;
$username = $_SESSION['username'];
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

// Return the updated cookie count to the client
echo $cookieCount;
?>
