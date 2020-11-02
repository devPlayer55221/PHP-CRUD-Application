<!DOCTYPE html>
<?php
	session_start();
	include 'pdo.php';
	include 'util.php';
	include 'head.php';
?>
<html>
	<head>
		<title>Mukund Kr Kedia's Resume Registry</title>
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
		<div class="container">
			<h1>Mukund Kr Kedia's Resume Registry</h1>
			<?php
				flashMessages();
				if(!isset($_SESSION['user_id']))
				{
					echo("<a href='login.php'>Please log in</a>");
					$stmt = $pdo->prepare("SELECT * FROM profile");
					if($stmt->rowCount() > 0)
					{
						echo('<table border="1">'."\n");
						echo("<tr><td>Name</td><td>Headline</td></tr>");
						while($row = $stmt->fetch(PDO::FETCH_ASSOC))
						{
							echo("<tr><td>");
							echo(htmlentities($row['first_name'])." ".htmlentities($row['last_name']));
							echo("</td><td>");
							echo(htmlentities($row['headline']));
							echo("</td></tr>"."\n");
						}
						echo("</table>");
					}
				}
				if(isset($_SESSION['user_id']))
				{
					//flashMessages();
					echo("<a href='logout.php'>Logout</a><br/>");
					$stmt = $pdo->prepare("SELECT * FROM profile WHERE user_id=:uid");
					$stmt->execute(array(':uid'=>$_SESSION['user_id']));
					if($stmt->rowCount() > 0)
					{
						echo('<table border="1">'."\n");
						echo("<tr><td>Name</td><td>Headline</td><td>Action</td></tr>");
						while($row = $stmt->fetch(PDO::FETCH_ASSOC))
						{
							echo("<tr><td>");
							echo("<a href='view.php?profile_id=".$row['profile_id']."'>".htmlentities($row['first_name'])." ".htmlentities($row['last_name'])."</a>");
							echo("</td><td>");
							echo(htmlentities($row['headline']));
							echo("</td><td>");
							echo("<a href='edit.php?profile_id=".$row['profile_id']."'>Edit</a>");
							echo(" <a href='delete.php?profile_id=".$row['profile_id']."'>Delete</a>");
							echo("</td></tr>"."\n");
						}
						echo("</table>");
					}
					echo("<a href='add.php'>Add New Entry</a>");
				}
			?>
			
		</div>
	</body>
</html>
