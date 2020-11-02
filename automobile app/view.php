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
				die("Not logged in");
				return;
			}
		?>
		<h1>Profile information</h1>
		<?php
			if(isset($_SESSION['success'])) 
			{
			  echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
			  unset($_SESSION['success']);
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
		<p>First Name: <?= $fn ?></p>
		<p>Last Name: <?= $ln ?></p>
		<p>Email: <?= $em ?></p>
		<p>Headline: <?= $he ?></p>
		<p>Summary: <?= $su ?></p>
		<p>Position<br/>
			<ul>
				<?php
					$stmt = $pdo->prepare("SELECT * FROM position WHERE profile_id=:pid");
					$stmt->execute(array(":pid"=>$profile_id));
					if($stmt->rowCount() > 0)
						{
							while($row = $stmt->fetch(PDO::FETCH_ASSOC))
							{
								echo("<li>".$row['year'].": ".$row['description']."</li>");
							}
						}
				?>
			</ul>
		</p>
		<a href="index.php">Done</a>
	</body>
</html>