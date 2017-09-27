<?php
    session_start();
    $_SESSION['email'] = $_POST['email'];
    
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    
    try {
        $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
        $db->exec('CREATE TABLE IF NOT EXISTS Users ( ' .
                  'ID INT AUTO_INCREMENT PRIMARY KEY, ' .
                  'userID TEXT, ' .
                  'password TEXT ' .
                  ')');
                  
        $db->exec("INSERT INTO Users (" .
                    "userID, password) VALUES ('" .
                    $_SESSION['email'] . "', '" . $_POST['password'] . "')"
                    );
    } catch(PDOException $e) {
        echo $e->getMessage();
    }

    echo "ok";
?>