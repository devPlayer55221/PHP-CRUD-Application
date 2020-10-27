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
			}
			if(isset($_POST['delete']))
			{
				$stmt = $pdo->prepare("DELETE FROM autos WHERE autos_id=:autos_id");
				$stmt->execute(array(':autos_id'=>$_POST['autos_id']));
				$_SESSION['success'] = "Record deleted";
				header("Location: index.php");
				return;
			}

			$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id=:myid");
			$stmt->execute(array(":myid"=>$_GET['autos_id']));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if($row == false)
			{
				$_SESSION['error'] = "Bad value for autos_id";
				header("Location: index.php");
				return;
			}
			$ma = htmlentities($row['make']);
			$md = htmlentities($row['model']);
			$ye = htmlentities($row['year']);
			$mi = htmlentities($row['mileage']);
			$autos_id = $row['autos_id'];
		?>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<form method="post">
			Confirm: Deleting <span><?= $ma ?></span>
			<input type="hidden" name="autos_id" value="<?= $autos_id ?>"/>
			<p>
				<input type="submit" name="delete" value="Delete"/>
				<a href = "index.php">Cancel</a>
			</p>
		</form>
	</body>
</html>