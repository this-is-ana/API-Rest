<?php

    require_once("TableBase.php");
	
	/*
    *   La classe TableFilm est la classe regroupant tous les films.
    */
	
    class TableFilm extends TableBase {
		
        public function getNomTable() {
            return "film";
        }

        public function getClePrimaire() {
            return "id";
        }

        //Création (CREATE)
        public function insere($titre, $duree, $annee, $idRealisateur, $genre) {
            $requete = "INSERT INTO film(titre, duree, annee, idRealisateur, genre) VALUES (:ti, :du, :an, :idR, :ge)";
            $requetePreparee = $this -> db -> prepare($requete);
            $requetePreparee -> bindParam(":ti", $titre);
            $requetePreparee -> bindParam(":du", $duree);
			$requetePreparee -> bindParam(":an", $annee);
			$requetePreparee -> bindParam(":idR", $idRealisateur);
			$requetePreparee -> bindParam(":ge", $genre);
            $requetePreparee -> execute();

            //Retour de l'identifiant de la dernière insertion
            if($requetePreparee -> rowCount() > 0)
                return $this -> db -> lastInsertId();
            else
                return false;
        }
       
        //Mise à jour (UPDATE)
        public function modifie($id, $titre, $duree, $annee, $idRealisateur, $genre) {
            $requete = "UPDATE film SET titre = :ti, duree = :du, annee = :an, idRealisateur = :idR, genre = :ge WHERE id = :id";
            $requetePreparee = $this -> db -> prepare($requete);
			$requetePreparee -> bindParam(":id", $id);
			$requetePreparee -> bindParam(":ti", $titre);
            $requetePreparee -> bindParam(":du", $duree);
			$requetePreparee -> bindParam(":an", $annee);
            $requetePreparee -> bindParam(":idR", $idRealisateur);
			$requetePreparee -> bindParam(":ge", $genre);
            $requetePreparee -> execute();

            //Retour du nombre de rangées affectées
			if($requetePreparee -> rowCount() > 0)
				return $requetePreparee -> rowCount();
			else
                return false;
        }
    }

?>