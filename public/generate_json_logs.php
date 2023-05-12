<?php
include './../config.php';

// Connect to the database
$conn = new mysqli($servername, $username_db, $password_db, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all users and their scores
$query = "SELECT username, cookie_count FROM users";
$result = $conn->query($query);

$users = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $users[] = array(
            'username' => $row['username'],
            'cookieCount' => $row['cookie_count']
        );
    }
}

$conn->close();

// Create a JSON string
$json = json_encode($users, JSON_PRETTY_PRINT);

// Get the current date and hour
$date = new DateTime();
$filename = 'logs/' . $date->format('Y-m-d-H');

// Write the JSON string to a file
file_put_contents($filename . ".json", $json);
?>
