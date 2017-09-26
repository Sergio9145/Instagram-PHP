<?php
session_start();

$servername = getenv('IP');
$username = getenv('C9_USER');
        
function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

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
              
    for ($i = 1; $i <= 6; $i++) {

    $db->exec("INSERT INTO Posts (" .
              "userID, postID, image, comment, likeCount, feedbackCount ) VALUES ('" .
              $_SESSION['email'] . "', '" . guidv4() .
              "', 'img/el" . $i . ".png', '', 0, 0" .
              ")"
              );    
    }

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