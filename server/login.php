<?php
    session_start();
    
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    
    $response = "fail";

    $_SESSION['email'] = $_POST['email'];
    
    try
    {
        $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
        
        $d1 = $db->prepare("SELECT userID, password FROM Users WHERE userID='" . $_SESSION['email'] . "'");
        $d1->execute();
        $users = $d1->fetchAll(PDO::FETCH_ASSOC);
    
        if ($users[0]['userID'] == $_POST['email'] && $users[0]['password'] == $_POST['password'])
            $response = "ok";

    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }        

    echo $response;
?>