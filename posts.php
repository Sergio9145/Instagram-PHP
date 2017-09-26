<?php
	session_start();
	if (isset($_SESSION['email'])) {
?>
	
<!-- Instagram Clone

Conestoga College, Mobile Solutions Development program
PROG8185 "Web Technologies"

Author: Sergiy Opryshko (#7759145) sopryshko9145@conestogac.on.ca
	
All copyrights reserved. -->
<!doctype html>
<html lang="en" ng-app>
<head>
	<title>Instagram Clone</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- COMMON STYLES -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="./css/main_style.css">
	<link rel="shortcut icon" href="./img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="./img/favicon.ico" type="image/x-icon">

	<!-- COMMON SCRIPTS -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
	<script src="./js/posts.js"></script>

	<script>
		function onPopulate() {
			Promise.resolve()
			.then(function(){
			    //jQuery function to request all the posts from the server
			    //the 'return' is required. Otherwise, the subsequent then will not wait for this to complete
			    return $.post('server/populate.php');
			})
			//when the server responds, we'll execute this code
			.then(function(posts){
			    return updateContent(posts);
			})
			.catch(function(err){
			    //always include a catch for exceptions
			    console.log(err);
			});
		}
		
		function onImageUpload() {
			var form = new FormData($("#uploadForm")[0]);

			$.ajax({
			    url: 'server/uploadImage.php',
			    method: "POST",
				enctype: 'multipart/form-data',
			    //the form object is the data
			    data: form,
			    //we want to send it untouched, so this needs to be false
			    processData: false,
			    contentType: false,
			    //add a message 
			    success: function(result){ updateContent(result); },
			    error: function(er){ }
			});
		} // onImageUpload()
		
	</script>
	
</head>

<body onload="onContentLoad()">
	<div id="headerContent">
		<script>
			$(function(){$("#headerContent").load("header.html");});
		</script>
	</div>
	
	<div id="bodyContent" style="margin: 30px;">
		<div class="modal fade" id="myModal" role="dialog">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal">&times;</button>
						<h4 class="modal-title">Add a Picture</h4>
					</div>
					<div class="modal-body">
						<form id="uploadForm" enctype="multipart/form-data" name="uploadForm" novalidate>
							<input type="file" name="userPhoto" id="userPhoto" />
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal" onclick="onImageUpload();">Upload</button>
					</div>
				</div>
			</div>
		</div>

		<div class="container-fluid" style="line-height:30px; margin:0px 0px 20px 0px;">
			<button type="button" class="btn btn-default navbar-right" style="margin:0px 0px 0px 0px;" onclick="onRemovePosts()">Clear all</button>
			<button type="button" class="btn btn-default navbar-right" data-toggle="modal" data-target="#myModal" style="margin:0px 10px 0px 0px;">Add post</button>
			<button type="button" class="btn btn-default navbar-right" style="margin:0px 10px 0px 0px;" onclick="onPopulate()">Populate</button>
		</div>
		
		<div id="postsContent"></div>
	</div>

	<div id="footerContent">
	  <script>
	    $(function(){$("#footerContent").load("footer.html");});
	  </script>
	</div>
</body>
</html>

<?php
	} else {
		header("Location: join.html");
	}
?>


