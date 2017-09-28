<?php
    session_start();
    
    $servername = getenv('IP');
    $username = getenv('C9_USER');
    
    $response = "fail";
    
    if (null !== $_POST['guid'] && !empty($_POST['guid']))
    {
       try {
            $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
            $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
            
            $d1 = $db->prepare("SELECT userID, guid, newpassword FROM Resets WHERE guid='" . $_POST['guid'] . "'");
            $d1->execute();
            $users = $d1->fetchAll(PDO::FETCH_ASSOC);

            $d1 = $db->prepare("UPDATE Users SET password=" . $users[0]['newpassword'] . " WHERE userID='" . $users[0]['userID'] . "'");
            $d1->execute();
            
            $_SESSION['email'] = $users[0]['userID'];
            
            $response = "ok";
        }
        catch(PDOException $e)
        {
            echo $e->getMessage();
        }
    }

    echo $response;
?>