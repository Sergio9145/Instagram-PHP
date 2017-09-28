<?php
    function guidv4()
    {
        if (function_exists('com_create_guid') === true)
            return trim(com_create_guid(), '{}');
    
        $data = openssl_random_pseudo_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
        return vsprintf('%s%s%s%s%s%s%s%s', str_split(bin2hex($data), 4));
    }
    
    $guid = guidv4();

    $servername = getenv('IP');
    $username = getenv('C9_USER');
    
    try
    {
        $db = new PDO("mysql:dbname=c9;host=$servername", $username, "" );
        $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION ); // Error Handling
        $db->exec('CREATE TABLE IF NOT EXISTS Resets ( ' .
                  'ID INT AUTO_INCREMENT PRIMARY KEY, ' .
                  'userID TEXT, ' .
                  'guid TEXT, ' .
                  'newpassword TEXT ' .
                  ')');
                  
        $d1 = $db->prepare("SELECT userID, password FROM Users WHERE userID='" . $_POST['email'] . "'");
        $d1->execute();
        $users = $d1->fetchAll(PDO::FETCH_ASSOC);
    
        if ($users[0]['userID'] == $_POST['email'])
        {
           $db->exec("INSERT INTO Resets (" .
                    "userID, guid, newpassword) VALUES ('" .
                    $_POST['email'] . "', '"
                    . $guid . "', '"
                    . $_POST['password'] . "')"
                    );
        }
    }
    catch(PDOException $e)
    {
        echo $e->getMessage();
    }

    echo $guid;
?>