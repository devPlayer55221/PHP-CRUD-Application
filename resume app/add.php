<!DOCTYPE html>
<?php
	session_start();
	include 'pdo.php';
?>
<html>
	<head>
		<title>Mukund Kr Kedia</title>
		<style>
			body {
				font-family: arial;
			}
		</style>
	</head>
	<body>
		<?php
			
			if(!isset($_SESSION['user_id']))
			{
				die("ACCESS DENIED");
				return;
			}
			if(isset($_POST['cancel']))
			{
				header("Location: index.php");
				return;
			}

			if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
			{
				if(strlen($_POST["first_name"])==0 || strlen($_POST["last_name"])==0 || strlen($_POST["email"])==0 || strlen($_POST["headline"])==0 || strlen($_POST['summary'])==0)
				{
					$_SESSION['error']="All fields are required";
					header("Location: add.php");
					return;
				}
				if(strpos($_POST['email'], '@') === false)
				{
					$_SESSION['error']="Email address must contain @";
					header("Location: add.php");
					return;
				}
				
					$stmt = $pdo->prepare("INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)");
					$stmt->execute(array(
						':uid'=>$_SESSION['user_id'],
						':fn'=>$_POST['first_name'],
						':ln'=>$_POST['last_name'],
						':em'=>$_POST['email'],
						':he'=>$_POST['headline'],
						':su'=>$_POST['summary']
					));
					$_SESSION['success']="Profile added";
					header("Location: index.php");
					return;
				
			}
		?>

		<h1>Adding Profile for <?= $_SESSION["name"] ?></h1>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<form method="post">
			<p>First Name:
			<input type="text" name="first_name" size="60"/></p>
			<p>Last Name:
			<input type="text" name="last_name" size="60"/></p>
			<p>Email:
			<input type="text" name="email"/></p>
			<p>Headline:
			<input type="text" name="headline"/></p>
			<p>Summary:
			<input type="text" name="summary"/></p>
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>
			<input type="submit" name="add" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</body>
</html>