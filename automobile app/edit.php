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
			if(!isset($_SESSION['name']))
			{
				die("ACCESS DENIED");
			}
			if(isset($_POST['save']))
			{
				if($_POST["make"]=='' || $_POST["model"]=='' || $_POST["year"]=='' || $_POST["mileage"]=='')
				{
					$_SESSION['error']="All fields are required";
					header("Location: edit.php?autos_id=".$_POST['autos_id']);
					return;
				}
				else if(!(is_numeric($_POST["year"]) && is_numeric($_POST["mileage"])))
				{
					$_SESSION['error']="Mileage must be an integer";
					header("Location: edit.php?autos_id=".$_POST['autos_id']);
					return;
				}
				else if(!(is_numeric($_POST["mileage"])))
				{
					$_SESSION['error']="Year must be an integer";
					header("Location: edit.php?autos_id=".$_POST['autos_id']);
					return;
				}
				else
				{
					$stmt = $pdo->prepare("UPDATE autos SET make=:mk, model=:md, year=:yr, mileage=:mi WHERE autos_id=:autos_id");
					$stmt->execute(array(
						':mk'=>$_POST['make'],
						':md'=>$_POST['model'],
						':yr'=>$_POST['year'],
						':mi'=>$_POST['mileage'],
						':autos_id'=>$_POST['autos_id']
					));
					//print "Record inserted";
					$_SESSION['success']="Record updated";
					header("Location: index.php");
					return;
				}
			}
			else if(isset($_POST['cancel']))
			{
				header("Location: index.php");
				return;
			}
		?>
		<h1>Editing Automobile</h1>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<?php
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
			<p>Make:
			<input type="text" name="make" size="60" value="<?= $ma ?>"/></p>
			<p>Model:
			<input type="text" name="model" size="60" value="<?= $md ?>"/></p>
			<p>Year:
			<input type="text" name="year" value="<?= $ye ?>"/></p>
			<p>Mileage:
			<input type="text" name="mileage" value="<?= $mi ?>"/></p>
			<input type="hidden" name="autos_id" value="<?= $autos_id ?>"/>
			<input type="submit" name="save" value="Save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</body>
</html>