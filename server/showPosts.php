<?php
session_start();

$servername = getenv('IP');
$username = getenv('C9_USER');

try {
    $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
    $db->exec('CREATE TABLE IF NOT EXISTS Posts ( ' .
              'ID INT AUTO_INCREMENT PRIMARY KEY, ' .
              'userID TEXT, ' .
              'postID TEXT, ' .
              'image TEXT, ' .
              'comment TEXT, ' .
              'likeCount INT DEFAULT 0, ' .
              'feedbackCount INT DEFAULT 0 '.
              ')');
} catch(PDOException $e) {
    echo $e->getMessage();
}

$sth = $db->prepare("SELECT * FROM Posts");
$sth->execute();

/* Fetch all of the remaining rows in the result set */
$response = $sth->fetchAll();

echo json_encode($response);
header("Content-type:application/json");
?>