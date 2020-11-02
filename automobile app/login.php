<!DOCTYPE html>
<?php
	session_start();
?>
<html>
	<head>
		<title>Mukund Kr Kedia</title>
		<style>
			body {
				font-family: arial;
			}
			a {
				text-decoration: none;
			}
		</style>
	</head>
	<body>
			<h1>Please Log In</h1>
			<?php
				if(isset($_SESSION["error"]))
				{
					echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
					unset($_SESSION["error"]);
				}
			?>
			<form method="POST">
				User Name <input type="text" name="email"><br/><br/>
				Password <input type="text" name="pass"><br/><br/>
				<input type="submit" value="Log In">
				<a href="index.php">Cancel</a>
			</form>

			<p>
				For a password hint, view source and find a password hint
				in the HTML comments.
				<!-- Hint: The password is the three character name of the 
				programming language used in this class (all lower case) 
				followed by 123. -->
			</p>	
			<?php
			if(isset($_POST["email"]) && isset($_POST["pass"]))
			{
				$hashed = hash('md5',"php123");
				$inppass = hash('md5',$_POST["pass"]);
				$pos = strpos($_POST["email"], '@');
				if($_POST["email"] == '' || $_POST["pass"] == '')
				{
					//print "Email and password are required";
					$_SESSION['error']="Email and password are required";
					header("Location: login.php");
					return;
				}
				else if($pos=='')
				{
					//print "Email must have an at-sign (@)";
					$_SESSION["error"]="Email must have an at-sign (@)";
					header("Location: login.php");
					return;
				}
				else if($inppass != $hashed)
				{
					//print "Incorrect password";
					error_log("Login fail ".$_POST["email"]." $inppass");
					$_SESSION["error"]="Incorrect password";
					header("Location: login.php");
					return;
				}
				else
				{
					error_log("Login success ".$_POST["email"]);
					$_SESSION['name']=$_POST['email'];
					header("Location: index.php");
					return;
				}
			}
		?>	
	</body>
</html>