<?php

	session_start();

	$diaryContent = "";
	$name = "Sam";

	if(array_key_exists('id',$_COOKIE)){
		$_SESSION['id'] = $_COOKIE['id'];
	}

	if(array_key_exists('id', $_SESSION)){
		echo "<p></p>";

		include ("connection.php");
		// echo $_POST['id'];

		//$query = "SELECT diary FROM `users` WHERE id= '".mysqli_real_escape_string($link,$_POST['id'])."' LIMIT 1";

		$query = "SELECT diary FROM `users` WHERE id= '".mysqli_real_escape_string($link,$_SESSION['id'])."' LIMIT 1";
		//$result = mysqli_query($link, $query);

		$row = mysqli_fetch_array(mysqli_query($link, $query));
		//printf ("(%s)\n",$row['id']);

		$diaryContent = $row['diary'];
		// echo $row['diary'];

	}else{
		header("Location: index.php");
	}



	include("header.php");
?>

	<nav class="navbar navbar-toggleable-md navbar-light bg-faded navbar-fixed-top">
	  <a class="navbar-brand" href="#">Secret Diary</a>
	    <div class="pull-xs-right">
	      <a href = 'index.php?logout=1'><button class="btn btn-outline-success my-2 my-sm-0" type="submit">Logout</button></a>
	    </div>
	</nav>

	<div class="container-fluid">

		<textarea class="form-control" id="diary"><?php echo $diaryContent; ?></textarea>

	</div> 

<?php
	
	include("footer.php");

?>