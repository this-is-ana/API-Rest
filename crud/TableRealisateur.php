<?php

    require_once("TableBase.php");
	
	/*
    *   La classe TableRealisateur est la classe regroupant tous les réalisateurs.
    */

    class TableRealisateur extends TableBase {
        
		public function getNomTable() {
            return "realisateur";
        }

        public function getClePrimaire() {
            return "id";
        }

        //Création (CREATE)
        public function insere($prenom, $nom) {
            $requete = "INSERT INTO realisateur(prenom, nom) VALUES (:p, :n)";
            $requetePreparee = $this -> db -> prepare($requete);
            $requetePreparee -> bindParam(":p", $prenom);
            $requetePreparee -> bindParam(":n", $nom);
            $requetePreparee -> execute();

            //Retour de l'identifiant de la dernière insertion
            if($requetePreparee -> rowCount() > 0)
                return $this -> db -> lastInsertId();
            else
                return false;
        }
        
        //Mise à jour (UPDATE)
        public function modifie($id, $prenom, $nom) {
            $requete = "UPDATE realisateur SET prenom = :p, nom = :n WHERE id = :id";
            $requetePreparee = $this -> db -> prepare($requete);
            $requetePreparee -> bindParam(":id", $id);
            $requetePreparee -> bindParam(":p", $prenom);
            $requetePreparee -> bindParam(":n", $nom);
            $requetePreparee -> execute();

            //Retour du nombre de rangées affectées
			if($requetePreparee -> rowCount() > 0)
				return $requetePreparee -> rowCount();
			else
                return false;
        }
    }

?>