<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="assets/css/main.css">
	<title>Test de TableFilm</title>
</head>
<body>
	<h1>Test de TableFilm</h1>
	<?php
		require_once("TableFilm.php");
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
		
		//Création d'une table pour la clé étrangère
		$dbRealisateur = new TableRealisateur($connexion);
		
		//Insertion de données dans la table de la clé étrangère
		$idRealisateur = $dbRealisateur -> insere("Testing", "Test");
		
		$dbFilm = new TableFilm($connexion);
		
		//Insertion dans la table Film vide - Création (CREATE)
		$filmID1 = $dbFilm -> insere("Inception", "2h28", 2010, $idRealisateur, "Science-fiction");
        $filmID2 = $dbFilm -> insere("The Matrix", "2h16", 1999, $idRealisateur, "Action");
		$filmID3 = $dbFilm -> insere("Raiders of the Lost Ark", "1h55", 1981, $idRealisateur, "Aventure");
		
		echo "<h2>Films avant modification</h2>";

		//Lecture (READ)
        $films = $dbFilm -> obtenir_tous();
        
        echo "<table>";
        echo "<tr><th>ID</th><th>Titre</th><th>Durée</th><th>Année</th><th>Réalisateur</th><th>Genre</th></tr>";
        foreach($films as $film) {
            echo "<tr><td>{$film["id"]}</td><td>{$film["titre"]}</td><td>{$film["duree"]}</td><td>{$film["annee"]}</td><td>{$film["idRealisateur"]}</td><td>{$film["genre"]}</td></tr>";
        }
        echo "</table>";

		//Mise à jour (UPDATE)
        $dbFilm -> modifie($filmID1, "Inception!!!", "2h28", 2010, $idRealisateur, "Science-fiction");
		$dbFilm -> modifie($filmID2, "DAS Matrix", "2h16", 1999, $idRealisateur, "Action");
		$dbFilm -> modifie($filmID3, "Raiders of the Found Ark", "1h55", 1981, $idRealisateur, "Aventure");

        echo "<h2>Films après modification</h2>";

        $films = $dbFilm -> obtenir_tous();
		
		echo "<table>";
        echo "<tr><th>ID</th><th>Titre</th><th>Durée</th><th>Année de production</th><th>Réalisateur</th><th>Genre</th></tr>";
        foreach($films as $film) {
            echo "<tr><td>{$film["id"]}</td><td>{$film["titre"]}</td><td>{$film["duree"]}</td><td>{$film["annee"]}</td><td>{$film["idRealisateur"]}</td><td>{$film["genre"]}</td></tr>";
        }
        echo "</table>";
        
		//Suppression (DELETE)
		$film1 = $dbFilm -> supprime($filmID1);
		$film2 = $dbFilm -> supprime($filmID2);
        $film3 = $dbFilm -> supprime($filmID3);
		
		echo "<h2>Tableau après suppression des films</h2>";
		
		$films = $dbFilm -> obtenir_tous();
		
		echo "<table>";
        echo "<tr><th>ID</th><th>Titre</th><th>Durée</th><th>Année de production</th><th>Réalisateur</th><th>Genre</th></tr>";
        foreach($films as $film) {
            echo "<tr><td>{$film["id"]}</td><td>{$film["titre"]}</td><td>{$film["duree"]}</td><td>{$film["annee"]}</td><td>{$film["idRealisateur"]}</td><td>{$film["genre"]}</td></tr>";
        }
		echo "<tr><td colspan='6'>Nombre de films supprimés : " , $film1 + $film2 + $film3 , "</td></tr>";
		echo "</table>";
		
		//Test de conditions d'invalidité
		//Clé étrangère invalide
		if($filmTest = $dbFilm -> insere("Test", "0h00", 2020, 200, "Action")) //Test pour l'insertion
			echo "<p>L'ajout a fonctionné.</p>";
		else
			echo "<p style='color:red'>Une clé étrangère inexistante empêche l'ajout.</p>";
		
		$filmTest = $dbFilm -> insere("Test", "2h00", 2020, $idRealisateur, "Action"); //Insertion pour tester la modification
		
		if($dbFilm -> modifie($filmTest, "Test", "2h00", 2020, 200, "Action")) //Test pour la modification
			echo "<p>La modification a fonctionné.</p>";
		else
			echo "<p style='color:red'>Une clé étrangère inexistante empêche la modification.</p>";
		
		//Suppression du filmTest
		$dbFilm -> supprime($filmTest);
		
		//Suppression de la clé étrangère
		$dbRealisateur -> supprime($idRealisateur);
		
	?>
</body>
</html>