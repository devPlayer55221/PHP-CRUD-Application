<!DOCTYPE html>
<?php
	session_start();
	include 'pdo.php';
	include 'util.php';
	include 'head.php';
?>
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
			echo("<h1>User id=".$_SESSION['user_id'].", Profile id=".$_REQUEST['profile_id']."</h1>");
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
			if(isset($_POST['save']))
			{

				if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']))
				{
					$msg = validateProfile();
					if (is_string($msg)) {
						$_SESSION['error'] = $msg;
						header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
						return;
					}

					$msg = validatePos();
					if(is_string($msg)) {
						$_SESSION['error'] = $msg;
						header("Location: edit.php?profile_id=".$_REQUEST['profile_id']);
						return;
					}
					
						$stmt = $pdo->prepare("UPDATE profile SET first_name=:fn, last_name=:ln, email=:em, headline=:he , summary=:su WHERE profile_id=:pid AND user_id=:uid");
						$stmt->execute(array(
							':pid'=>$_REQUEST['profile_id'],
							':uid'=>$_SESSION['user_id'],
							':fn'=>$_POST['first_name'],
							':ln'=>$_POST['last_name'],
							':em'=>$_POST['email'],
							':he'=>$_POST['headline'],
							':su'=>$_POST['summary']
						));

						$stmt = $pdo->prepare('DELETE FROM position WHERE profile_id=:pid');
						$stmt->execute(array(':pid'=>$_REQUEST['profile_id']));

						$rank=1;
						for($i=1;$i<=9;$i++) {
							if(!isset($_POST['year'.$i])) continue;
							if(!isset($_POST['desc'.$i])) continue;
							$year = $_POST['year'.$i];
							$desc = $_POST['desc'.$i];

							$stmt = $pdo->prepare("INSERT INTO position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :desc)");
							$stmt->execute(array(
								':pid'=>$_REQUEST['profile_id'],
								':rank'=>$rank,
								':year'=>$year,
								':desc'=>$desc
							));
							$rank++;
						}

						$_SESSION['success']="Profile updated";
						header("Location: index.php");
						return;
					
				}
			}
			
		?>
		<h1>Editing Profile for <?= $_SESSION['name'] ?></h1>
		<?php
			flashMessages();
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
			$profile_id = htmlentities($row['profile_id']);

			$positions = loadPos($pdo, $_GET['profile_id']);

		?>
		<form method="post" action="edit.php">
			<p>First Name:
			<input type="text" name="first_name" size="60" value="<?= $fn ?>"/></p>
			<p>Last Name:
			<input type="text" name="last_name" size="60" value="<?= $ln ?>"/></p>
			<p>Email:
			<input type="text" name="email" size="30" value="<?= $em ?>"/></p>
			<p>Headline:<br/>
			<input type="text" name="headline" size="80" value="<?= $he ?>"/></p>
			<p>Summary:<br/>
			<textarea type="text" name="summary" rows="8" cols="80"><?= $su ?></textarea></p>
			<p>
				Position: <input type="submit" id="addPos" value="+">
				<div id="position_fields">
					<?php
						$countPos = 0;
						$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id=:pid");
						$stmt->execute(array(":pid"=>$profile_id));
						$pos=0;
						if($stmt->rowCount() > 0)
						{
							while($row = $stmt->fetch(PDO::FETCH_ASSOC))
							{
								echo('<p>Year: <input type="text" name="year'.$countPos.'" value='.$row['year'].' /><input type="button" value="-" onclick="$("#position'.$countPos.'").remove(); return false;"><br/><textarea name="desc'.$countPos.'" rows="8" cols="80">'.$row['description'].'</textarea></p>');
								$pos++;
							}
						}
					?>
				</div>
			</p>
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>"/>
			<input type="submit" name="save" value="Save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
		<!-- <script src="js/jquery-1.10.2.js"></script>
		<script src="js/jquery-ui-1.11.4.js"></script> -->
		<script>
			var countPos = <?= $pos ?>;
			$(document).ready(function() {
				window.console && console.log('Document ready called');
				$('#addPos').click(function(event) {
					event.preventDefault();
					if(countPos>=9)
					{
						alert("Maximum of nine position entries exceeded");
						return;
					}
					countPos++;
					window.console && console.log("Adding position "+countPos);
					$('#position_fields').append('<div id="position'+countPos+'"><p>Year: <input type="text" name="year'+countPos+'"value="" /><input type="button" value="-" onclick="$(\'#position'+countPos+'\').remove(); return false;"></p><textarea name="desc'+countPos+'" rows="8" cols="80"></textarea></div>');
				});
			});
		</script>
	</body>
</html>