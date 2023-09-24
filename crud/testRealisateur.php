<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/main.css">
	<title>Test de TableRealisateur</title>
</head>
<body>
	<h1>Test de TableRealisateur</h1>
	<?php
		require_once("TableRealisateur.php");
		
		//Dans le constructeur, création de la connexion à la BD qui sera utilisée par toutes les autres méthodes du CRUD
        try {
            //Pour l'encodage UTF-8
            $options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
            //Connexion à la BD
            $connexion = new PDO("mysql:host=localhost;dbname=films", "root", "", $options);
        }
        catch(PDOException $e) {
            trigger_error("Erreur lors de la connexion : " . $e -> getMessage());
        } 
		
		$dbRealisateur = new TableRealisateur($connexion);
	
		//Insertion dans la table Realisateur vide - Création (CREATE)
		$idRealisateur1 = $dbRealisateur -> insere("Christopher", "Nolan");
        $idRealisateur2 = $dbRealisateur -> insere("Steven", "Spielberg");
		$idRealisateur3 = $dbRealisateur -> insere("Lana", "Wachowski");
		
		echo "<h2>Réalisateurs avant modification</h2>";
		
		//Lecture (READ)
        $realisateurs = $dbRealisateur -> obtenir_tous();
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th></tr>";
        foreach($realisateurs as $realisateur) {
            echo "<tr><td>" . $realisateur["id"] . "</td><td>" . $realisateur["prenom"] . "</td><td>" . $realisateur["nom"] . "</td></tr>";
        }
        echo "</table>";

		//Mise à jour (UPDATE)
        $dbRealisateur -> modifie($idRealisateur1, "Christo", "Nolan");
		$dbRealisateur -> modifie($idRealisateur2, "Steevie", "Wonder");
		$dbRealisateur -> modifie($idRealisateur3, "LanD", "Wachowski");
		
        echo "<h2>Réalisateurs après modification</h2>";

        $realisateurs = $dbRealisateur -> obtenir_tous();
		
		echo "<table>";
        echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th></tr>";
        foreach($realisateurs as $realisateur) {
            echo "<tr><td>" . $realisateur["id"] . "</td><td>" . $realisateur["prenom"] . "</td><td>" . $realisateur["nom"] . "</td></tr>";
        }
        echo "</table>";
        
		//Suppression (DELETE)
		$realisateur1 = $dbRealisateur -> supprime($idRealisateur1);
		$realisateur2 = $dbRealisateur -> supprime($idRealisateur2);
		$realisateur3 = $dbRealisateur -> supprime($idRealisateur3);
		
		echo "<h2>Tableau après suppression</h2>";
		
		$realisateurs = $dbRealisateur -> obtenir_tous();
		
		echo "<table>";
        echo "<tr><th>ID</th><th>Prénom</th><th>Nom</th></tr>";
        foreach($realisateurs as $realisateur) {
            echo "<tr><td>" . $realisateur["id"] . "</td><td>" . $realisateur["prenom"] . "</td><td>" . $realisateur["nom"] . "</td></tr>";
        }
		echo "<tr><td colspan='3'>Nombre de réalisateurs supprimés : " , $realisateur1 + $realisateur2 + $realisateur3 , "</td></tr>";
        echo "</table>";
		
	?>
</body>
</html>