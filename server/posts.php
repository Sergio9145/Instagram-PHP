<?php
//   userId: String, //_id from the user table
//   image: String, //url to image file
//   comment: String, //poster's comment
//   likeCount: Number, //number of likes (convenience value)
//   feedbackCount: Number //number of comments from others (convenience value)
$response = array("userID"=>"id", "image"=>"img", "comment"=>"comment");
echo json_encode($response);
header("Content-type:application/json");
?>