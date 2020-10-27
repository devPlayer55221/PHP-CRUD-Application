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
			
			if(!isset($_SESSION['name']))
			{
				die("ACCESS DENIED");
			}
			if(isset($_POST['add']))
			{
				if($_POST["make"]=='' || $_POST["model"]=='' || $_POST["year"]=='' || $_POST["mileage"]=='')
				{
					$_SESSION['error']="All fields are required";
					header("Location: add.php");
					return;
				}
				else if(!(is_numeric($_POST["year"]) && is_numeric($_POST["mileage"])))
				{
					$_SESSION['error']="Mileage must be an integer";
					header("Location: add.php");
					return;
				}
				else if(!(is_numeric($_POST["mileage"])))
				{
					$_SESSION['error']="Year must be an integer";
					header("Location: add.php");
					return;
				}
				else
				{
					$stmt = $pdo->prepare("INSERT INTO autos (make, model, year, mileage) VALUES (:mk, :md, :yr, :mi)");
					$stmt->execute(array(
						':mk'=>$_POST['make'],
						':md'=>$_POST['model'],
						':yr'=>$_POST['year'],
						':mi'=>$_POST['mileage']
					));
					$_SESSION['success']="Record added";
					header("Location: index.php");
					return;
				}
			}
		?>
		<h1>Adding Autos for <?= $_SESSION["name"] ?></h1>
		<?php
			if(isset($_SESSION["error"]))
			{
				echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				unset($_SESSION["error"]);
			}
		?>
		<form method="post">
			<p>Make:
			<input type="text" name="make" size="60"/></p>
			<p>Model:
			<input type="text" name="model" size="60"/></p>
			<p>Year:
			<input type="text" name="year"/></p>
			<p>Mileage:
			<input type="text" name="mileage"/></p>
			<input type="submit" name="add" value="Add" formaction="add.php">
			<input type="submit" name="cancel" value="Cancel" formaction="index.php">
		</form>
	</body>
</html>