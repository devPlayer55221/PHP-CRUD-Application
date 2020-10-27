<!DOCTYPE html>
<html>
	<head>
		<title>
			Mukund Kr Kedia
		</title>
		<style>
			body {
				font-family: arial;
			}
		</style>
	</head>
	<body>
		<?php
			session_start();
			include 'pdo.php';
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
					header("Location: edit.php?profile_id=".$_POST['profile_id']);
					return;
				}
				if(strpos($_POST['email'], '@') === false)
				{
					$_SESSION['error']="Email address must contain @";
					header("Location: edit.php?profile_id=".$_POST['profile_id']);
					return;
				}
				
					$stmt = $pdo->prepare("UPDATE profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he , summary=:su WHERE user_id=:uid");
					$stmt->execute(array(
						':uid'=>$_SESSION['user_id'],
						':fn'=>$_POST['first_name'],
						':ln'=>$_POST['last_name'],
						':em'=>$_POST['email'],
						':he'=>$_POST['headline'],
						':su'=>$_POST['summary']
					));
					//print "Record inserted";
					$_SESSION['success']="Profile updated";
					header("Location: index.php");
					return;
				
			}
			else if(isset($_POST['cancel']))
			{
				header("Location: index.php");
				return;
			}
		?>
		<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<?php
			$stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id=:pid");
			$stmt->execute(array(":pid"=>$_GET['profile_id']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row == false)
			{
				$_SESSION['error'] = "Bad value for profile_id";
				header("Location: index.php");
				return;
			}
			$fn = htmlentities($row['first_name']);
			$ln = htmlentities($row['last_name']);
			$em = htmlentities($row['email']);
			$he = htmlentities($row['headline']);
			$su = htmlentities($row['summary']);
			$profile_id = $row['profile_id'];
		?>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<form method="post">
			<p>First Name:
			<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
			<p>Last Name:
			<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
			<p>Email:
			<input type="text" name="email" value="<?= $em ?>"/></p>
			<p>Headline:
			<input type="text" name="headline" value="<?= $he ?>"/></p>
			<p>Summary:
			<input type="text" name="summary" value="<?= $su ?>"/></p>
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>
			<input type="submit" name="save" value="Save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</body>
</html>