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
			a {
				text-decoration: none;
			}
		</style>
	</head>
	<body>
		<div class="container">
			<h1>Welcome to the Automobiles Database</h1>
			<?php
				if(!isset($_SESSION['name']))
				{
					echo("<p><a href='login.php'>Please log in</a></p>
					<p>Attempt to <a href='add.php'>add data</a> without logging in</p>");
				}
				if(isset($_SESSION['name']))
				{
					if(isset($_SESSION['success']))
					{
						echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
						unset($_SESSION['success']);
					}
					$stmt = $pdo->query("SELECT * FROM autos");
					if($stmt->rowCount() == 0)
					{
						echo("<p>No rows found</p>");
						echo("<a href='add.php'>Add New Entry</a><br/>");
						echo("<a href='logout.php'>Logout</a>");
					}
					else
					{
						echo('<table border="1">'."\n");
						echo("<tr><td>Make</td><td>Model</td><td>Year</td><td>Mileage</td><td>Action</td></tr>");
						while($row = $stmt->fetch(PDO::FETCH_ASSOC))
						{
							echo "<tr><td>";
						    echo(htmlentities($row['make']));
						    echo("</td><td>");
						    echo(htmlentities($row['model']));
						    echo("</td><td>");
						    echo(htmlentities($row['year']));
						    echo("</td><td>");
						    echo(htmlentities($row['mileage']));
						    echo("</td><td>");
						    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
						    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
						    echo("</td></tr>\n");
						}
						echo('</table><br/>');
						echo("<a href='add.php'>Add New Entry</a><br/>");
						echo("<a href='logout.php'>Logout</a>");
					}
				}
			?>
			
		</div>
	</body>
</html>
