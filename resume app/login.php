
<!DOCTYPE html>
<?php
	include 'pdo.php';
	session_start();
	if(isset($_POST['cancel']))
	{
		header("Location: index.php");
		return;
	}
?>
<html>
	<head>
		<title>Mukund Kr Kedia's Login Page</title>
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
				Email <input type="text" name="email" id="email"><br/><br/>
				Password <input type="text" name="pass" id="id_1723"><br/><br/>
				<input type="submit" value="Log In" onclick="return doValidate();">
				<input type="submit" value="Cancel" name="cancel">
			</form>

			<p>
				For a password hint, view source and find a password hint
				in the HTML comments.
				<!-- Hint: 
					The account is umsi@umich.edu
					The password is the three character name of the 
					programming language used in this class (all lower case) 
					followed by 123. -->
			</p>	
			<script>
				function doValidate() {
				    console.log('Validating...');
				    try {
				        addr = document.getElementById('email').value;
				        pw = document.getElementById('id_1723').value;
				        console.log("Validating addr="+addr+" pw="+pw);
				        if (addr == null || addr == "" || pw == null || pw == "") {
				            alert("Both fields must be filled out");
				            return false;
				        }
				        if ( addr.indexOf('@') == -1 ) {
				            alert("Invalid email address");
				            return false;
				        }
				        return true;
				    } catch(e) {
				        return false;
				    }
				    return false;
				}
			</script>
			<?php
			if(isset($_POST["email"]) && isset($_POST["pass"]))
			{
				if(strlen($_POST['email']) < 1 || strlen($_POST['pass']) < 1)
				{
					$_SESSION['error']="Email and password are required";
					header("Location: login.php");
					return;
				}
				$salt="XyZzy12*_";
				$check = hash('md5',$salt.$_POST['pass']);
				$stmt = $pdo->prepare('SELECT user_id, name FROM users WHERE email= :em AND password= :pw');
				$stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
				$row = $stmt->fetch(PDO::FETCH_ASSOC);

				if($row !== false)
				{
					$_SESSION['name']=$row['name'];
					$_SESSION['user_id']=$row['user_id'];
					header("Location: index.php");
					return;
				}
				else
				{
					$_SESSION['error']="Incorrect password";
					header("Location: login.php");
					return;
				}
			}
		?>	
	</body>
</html>