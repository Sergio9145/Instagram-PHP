<?php
session_start();

$target_dir = "uploads/";

$target_file = $target_dir . basename($_FILES["userPhoto"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["userPhoto"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["userPhoto"]["size"] > 5000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}

function guidv4()
{
    if (function_exists('com_create_guid') === true)
        return trim(com_create_guid(), '{}');

    $data = openssl_random_pseudo_bytes(16);
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

$guid = guidv4();

$new_file_name = $target_dir . $guid . "." . $imageFileType;

// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["userPhoto"]["tmp_name"], $new_file_name)) {
        echo "The file ". basename( $_FILES["userPhoto"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}

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

    $db->exec("INSERT INTO Posts (" .
                "userID, PostID, image, comment, likeCount, feedbackCount ) VALUES ('" .
                $_SESSION['email'] . "', '" . guidv4() .
                "', 'server/" . $new_file_name .
                "', '', 0, 0" .
                ")"
                );
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