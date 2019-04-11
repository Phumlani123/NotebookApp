<?

session_start();

 $error = "";
 if (array_key_exists("logout", $_GET)) {
 	unset($_SESSION);
 	setcookie("id", "", time() - 60*60);

 	$_COOKIE["id"] = "";
 	session_destroy();
 } elseif ((array_key_exists("id", $_SESSION) AND $_SESSION['id']) OR (array_key_exists("id", $_COOKIE) AND $_COOKIE['id'])) {
 	
 	header("location: loggedin.php");
 }

 if (array_key_exists("submit", $_POST) ){

 	include("connection.php");
   

    if (!$_POST['email']){

    	$error .= " An email address is required";

    }
    if (!$_POST['password']){

    	$error .= " A password is required";

    }

    if ($error != ""){

    	$error = "<p>There were error(s) in your form:</p>".$error;

    } else {

    	if ($_POST['signUp'] == '1') {
    		$query = "SELECT id FROM `Users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' LIMIT 1";

    	$result = mysqli_query($link, $query);

    	if (mysqli_num_rows($result) > 0){

    		$error = "That address is taken.";
    	}else {

    		$query = "INSERT INTO `Users` (`email`, `password`) VALUES ( '".mysqli_real_escape_string($link, $_POST['email'])."' , '".mysqli_real_escape_string($link, $_POST['password'])."' ) ";

    		if (!mysqli_query($link, $query)) {

    			$error = "<p>Could not sign you up - please try again later</p>";

    		} else {
    			$query = "UPDATE `Users` SET password = '".md5(md5(mysqli_insert_id($link)).$_POST['password'])."' WHERE id = ".mysqli_insert_id($link)." LIMIT 1 ";
    			mysqli_query($link, $query);

    			$_SESSION['id'] = mysqli_insert_id($link);

    			if ($_POST['stayLoggedIn'] == '1'){

    				setcookie("id", mysqli_insert_id($link), time() + 60*60*24*365);

    			}

    			header("location: loggedin.php");
    		}
    	}
    	} else {
    		$query = "SELECT * FROM `Users` WHERE email = '".mysqli_real_escape_string($link, $_POST['email'])."' ";
    		$result = mysqli_query($link, $query);
    		$row = mysqli_fetch_array($result);

    		if (isset($row)) {
    			$hashedPassword = md5(md5($row['id']).$_POST['password']);

    			if ($hashedPassword == $row['password']) {
    				$_SESSION['id'] = $row['id'];
    				if ($_POST['stayLoggedIn'] == '1'){

    				setcookie("id", $row['id'], time() + 60*60*24*365);

    			}

    			header("location: loggedin.php");
    			}

    			else {
    			$error = "That email/Password combination could not be found.";
    		}

    		} else {
    			$error = "That email/Password combination could not be found.";
    		}
    	}

    	
    }

}



?>

<?php include("header.php"); ?>
  
    <div class="container">

    	<div class="bg-color">

    		<h1>The Notebook</h1>
    	<p> <strong>A simple, secure, notebook application</strong> </p>
    	<div id="error"><?php  if ($error!="") {
            echo '<div class="alert alert-dark" role="alert">'.$error.'</div>';
                      } ?>
            
        </div>
			<form method="post" id="signUpForm">
				<p>If you're interested, sign up here</p>
				<div class="form-group">
					<input class="form-control" type="email" name="email" placeholder="Your email">
				</div>
			    <div class="form-group">
			    	<input class="form-control" type="password" name="password" placeholder="Password">
			    </div>
			    <div class="form-group form-check">
			    	<input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
			    	 <label class="form-check-label" for="exampleCheck1">Stay logged in</label>
			    </div>
		    	<div class="form-group">
			    	<input  class="form-control" type="hidden" name="signUp" value=1>
			    
			    
			    
			    	<input class="btn btn-primary" type="submit" name="submit" value="Sign Up!">
			    	
			    </div>

			    <p><a  class="toggleForms">Log in</a></p>
			    	
			    
			</form>

			<form id="logInForm" method="post" >
				<p>Login with your username and password</p>
				 <div class="form-group">
				    <input class="form-control" type="email" name="email" placeholder="Your email">
			    </div>
			    <div class="form-group">
				    <input class="form-control" type="password" name="password" placeholder="Password">
			     </div>
			    <div class="form-group form-check">

				    <input class="form-check-input" type="checkbox" name="stayLoggedIn" value=1>
				    <label class="form-check-label" for="exampleCheck1">Stay logged in</label>
			     </div>
			    <div class="form-group">
				    <input class="form-control" type="hidden" name="signUp" value=0>
			     
			    

			    	 <input class="btn btn-primary" type="submit" name="submit" value="Log In!">
			    	
			   </div>
				   
				   <p><a class="toggleForms">Sign Up</a></p>
			     
			  
			</form>
    		


    	</div>
    	
    	

		
    </div>

   <?php include("footer.php"); ?>