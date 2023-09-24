<?php
    //Ne pas afficher les erreurs - Commenter pour afficher les erreurs
    //error_reporting(0);

    //Entêtes de réponse requises
    header("Access-Control-Allow-Origin: *");
    //Spécification que la réponse sera en JSON
    header("Content-Type: application/json; charset=UTF-8");

    //Connexion à la base de données des réalisateurs
    require_once("crud/TableRealisateur.php"); 

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

    //Structure décisionnelle qui observe la méthode utilisée dans la requête
    switch($_SERVER["REQUEST_METHOD"]) {
        case "POST":
            //CREATE - Création d'un réalisateur
            //Obtention du corps de la requête
            $dataJSON = file_get_contents("php://input");

            //Décodage du JSON pour donner un objet realisateur
            $realisateur = json_decode($dataJSON);
            if(isset($realisateur -> id, $realisateur -> prenom, $realisateur -> nom)) {
                //Insertion
                $retour = $dbRealisateur -> insere($realisateur -> prenom, $realisateur -> nom);

                if($retour !== false) {
                    //L'ajout a fonctionné - retour d'un code 201 ET affichage du JSON inséré - CREATED
                    http_response_code(201);
                    echo $dataJSON;
                } else {
                    //L'ajout n'a pas fonctionné, une clé étrangère n'est pas respectée ou la clé primaire est déjà utilisée, etc. - CONFLICT
                    http_response_code(409);
                }
            } else {
                //Le JSON envoyé en paramètres ne contient pas les attributs nécessaires - Mauvaise requête - BAD REQUEST
                http_response_code(400);
            }
			break;
        case "GET":
			//READ - Lecture
			//Réalisateur par ID
            if(isset($_GET["id"])) {
                $realisateur = $dbRealisateur -> obtenir_par_id($_GET["id"]);

                if($realisateur) {
                    echo json_encode($realisateur);
                    http_response_code(200); //Le réalisateur avec cet ID a été trouvé - OK
                }                
                else {
                    http_response_code(404); //Le réalisateur avec cet ID n'a pas été trouvé - NOT FOUND
                }
            }
            else {
                //READ - Lecture
				//Tous les réalisateurs de la table
                $resultat = $dbRealisateur -> obtenir_tous();
                $tableauRealisateurs = $resultat -> fetchAll(PDO::FETCH_ASSOC);
                echo json_encode($tableauRealisateurs);
                //Code de retour approprié - 200 - OK
                http_response_code(200);
            }
			break;
        case "PUT":
            //UPDATE - Mise à jour
			//Obtention du corps de la requête
            $dataJSON = file_get_contents("php://input");

            //Décodage du JSON pour donner un objet realisateur
            $realisateur = json_decode($dataJSON);
			
            if(isset($realisateur -> id, $realisateur -> prenom, $realisateur -> nom)) {
				$retour = $dbRealisateur -> modifie($realisateur -> id, $realisateur -> prenom, $realisateur -> nom);	
			
				if($retour !== false) {
					//La modification a fonctionné - retour d'un code 200 ET affichage du JSON modifié - OK
					http_response_code(200);
					echo $dataJSON;
				} else {
					//La modification n'a pas fonctionné, le réalisateur avec cet id n'a pas été trouvé - NOT FOUND
					http_response_code(404);
				}
			} else {
				//Le JSON envoyé en paramètres ne contient pas les attributs nécessaires - Mauvaise requête - BAD REQUEST
                http_response_code(400);
			}
			break;
        case "DELETE":
            //DELETE - Suppression
			if(isset($_GET["id"])) {
                $realisateur = $dbRealisateur -> supprime($_GET["id"]);

                if($realisateur) {
                    http_response_code(204); //Le réalisateur avec cet ID a été trouvé et supprimé - NO CONTENT
                }                
                else {
                    http_response_code(404); //Le réalisateur avec cet ID n'a pas été trouvé - NOT FOUND
                }
            }
            else {
				//Le JSON envoyé en paramètres ne contient pas les attributs nécessaires - Mauvaise requête - BAD REQUEST
                http_response_code(400);
            }
			break;
	}

?>