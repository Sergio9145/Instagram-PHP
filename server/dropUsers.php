<?php
session_start();
session_unset();

$servername = getenv('IP');
$username = getenv('C9_USER');

try {
    $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
    $db->exec('DROP TABLE Users');
} catch(PDOException $e) {
    echo $e->getMessage();
}

echo "ok";
?>