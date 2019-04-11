<?php

	session_start();

	$diaryContent = "";

	if (array_key_exists("id", $_COOKIE)) {

		$SESSSION['id'] = $_COOKIE['id'];
	}

	if (array_key_exists( "id", $_SESSION)){

		

		include("connection.php");

		$query = "SELECT diary FROM `Users` WHERE id = ".mysqli_real_escape_string($link, $_SESSION['id'])." LIMIT 1";
		$row = mysqli_fetch_array(mysqli_query($link, $query));

		$diaryContent = $row['diary'];

	} else {
		header("location: index.php");
	}

	include("header.php");

	

 ?>


<nav class="navbar navbar-expand-lg navbar-dark bg-dark navbar-fixed-top">
  <a class="navbar-brand" href="/library">The noteBook</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
     
     
    
   
    </ul>
    <div class="form-inline my-2 my-lg-0">
      
      <a href="index.php?logout=1"><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button></a>
    </div>
  </div>
</nav>
 

	<div class="diary-container" >
		<textarea id="diary" class="form-control"><?php echo $diaryContent; ?></textarea>
	</div>


	<?php


	include("footer.php");

	?>