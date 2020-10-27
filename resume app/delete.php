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
			a {
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<?php
			session_start();
			include 'pdo.php';

			if(!isset($_SESSION['name']))
			{
				die("ACCESS DENIED");
				return;
			}
			if(isset($_POST['delete']))
			{
				$stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id=:pid");
				$stmt->execute(array(':pid'=>$_POST['profile_id']));
				$_SESSION['success'] = "Profile deleted";
				header("Location: index.php");
				return;
			}
			if(isset($_POST['cancel']))
			{
				header("Location: index.php");
				return;
			}
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
			$profile_id = $row['profile_id'];
		?>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<h1>Deleting Profile</h1>
		<form method="post">
			<p>First Name: <?= $fn ?></p>
			<p>Last Name: <?= $ln ?></p>
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>
			<input type="submit" name="delete" value="Delete"/>
			<input type="submit" name="cancel" value="Cancel"/>
		</form>
	</body>
</html>