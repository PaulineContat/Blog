<?php 
	try {
		$server = "localhost";
		$db = "projetblog";
		$login = "root";
		$mdp = "";
		$linkpdo = new PDO("mysql:host=$server;dbname=$db", $login, $mdp);
	} catch (Exception $e) {
		die('Erreur : '.$e->getMessage());
	}
?>