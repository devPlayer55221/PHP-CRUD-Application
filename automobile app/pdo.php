<?php
	echo "<pre>\n";
	$pdo = new PDO('mysql:host=localhost;port=3306;dbname=misc','fred','zap');
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// $stmt = $pdo->query("SELECT * FROM album");
	// while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	// {
	// 	print_r($row);
	// } 
	echo "</pre>\n";
?>