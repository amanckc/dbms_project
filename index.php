<?php

	session_start();

	$error = "";

	if(array_key_exists('logout', $_GET)){
		unset($_SESSION);
		setcookie('id','',time()-60*60);
		$_COOKIE = '';
	}else if((array_key_exists('id', $_SESSION) AND $_SESSION['id']) OR (array_key_exists('id', $_COOKIE) AND $_COOKIE['id'])){
		header("Location: loggedInPage.php");
	}

	if(array_key_exists("submit", $_POST)){

		include("connection.php"); 

		if(!$_POST['email']){
			$error = "An email address is required<br>";
		}

		if(!$_POST['password']){
			$error = "A password is required<br>";
		}

		if($error != ""){
			$error = "<p>There were errors<p>".$error;
		}else{

			if($_POST['signup'] == '1'){

				$query = "SELECT id FROM `users` WHERE email = '".mysqli_real_escape_string($link,$_POST['email'])."' LIMIT 1";

				$result = mysqli_query($link, $query);

				if(mysqli_num_rows($result)>0){
					$error =  "That email address is taken";
				}else{
					$query = "INSERT into `users` (`email`,`password`) VALUES('".mysqli_real_escape_string($link,$_POST['email'])."','".mysqli_real_escape_string($link,$_POST['password'])."')";

					if(!mysqli_query($link, $query)){
						$error = "<p>Could not sign you up.Please try again later</p>";
					}else{
						$query = "UPDATE `users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1";
						mysqli_query($link,$query);

						$_SESSION['id'] = mysqli_insert_id($link);

						if($_POST['stayLoggedIn'] == 1){
							setcookie("id",mysqli_insert_id($link),time()+ 60*60*24*365); 
						}
					
						header("Location: loggedInPage.php");
					}
				}
			}else{
				$query = "SELECT * FROM `users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."'";

				$result = mysqli_query($link, $query);

				$row = mysqli_fetch_array($result);

				if(isset($row)){
					$hashedPassword = md5(md5($row['id']).$_POST['password']);
					if($hashedPassword == $row['password']){
						$_SESSION['id'] = $row['id'];

						if($_POST['stayLoggedIn'] == 1){
							setcookie("id",mysqli_insert_id($link),time()+ 60*60*24*365); 
						}
					
						header("Location: loggedInPage.php");
					}else{
						$error = "Incorrect Email/Password";
					}
				}else{
					$error = "Incorrect Email/Password";
				}
			}

		}
	}

?>

<?php include("header.php"); ?>

  	<div class="container" id="homePageContainer">

  		<h1>Secret Diary</h1>

  		<div id="error"><?php if($error!=""){
  				echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
  			} ?></div>

		<form method="POST" id="signUpForm">

			<p>Interested! Sign Up Here</p>

			<div class="form-group">
				<input class="form-control" type="email" name="email" placeholder="Your Email">
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password">
			</div>

			<div class="form-check">
    			<label class="form-check-label">
					<input type="checkbox" name="stayLoggedIn" value="1">
					Stay Logged In
				</label>
			</div>

				<input type="hidden" name="signup" value="1">

			<div class="form-group">
				<input class="btn btn-success" type="submit" name="submit" value="Sign Up!">
			</div>

			<p><a class="toggleForms"><u>Log In</u></a></p>

		</form>

		<form method="POST" id="logInForm">

			<p>Log in using your username and password</p>

			<div class="form-group">
				<input class="form-control" type="email" name="email" placeholder="Your Email">
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="password" placeholder="Password">
			</div>

			<div class="form-check">
    			<label class="form-check-label">
					<input type="checkbox" name="stayLoggedIn" value="1">
					Stay Logged In
				</label>
			</div>

				<input type="hidden" name="signup" value="0">

			<div class="form-group">
				<input class="btn btn-success" type="submit" name="submit" value="Log In!">
			</div>

			<p><a class="toggleForms"><u>Sign Up</u></a></p>

		</form>
  	</div>

	<?php include("footer.php"); ?>