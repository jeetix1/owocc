<?php
session_start();

// Generate a random username
$words = array('troll', 'furry', 'monster', 'ai', 'owo');
$random_username = $words[array_rand($words)] . $words[array_rand($words)] . rand(1, 100);

// Set the random username in the session and redirect to index.php
$_SESSION['username'] = $random_username;
header('Location: index.php?user=' . $random_username . '');
exit;
?>
