<?php

/// Librairies éventuelles (pour la connexion à la BDD, etc.)
include('config.php'); //pour deliver_response et linkpdo + getRole
include('jwt_utils.php'); //get_bearer_token
include('get_access.php'); //access
include('delete_access.php'); //delete

/// Paramétrage de l'entête HTTP (pour la réponse au Client)
header("Content-Type:application/json");

/// Identification du type de méthode HTTP envoyée par le client
$http_method = $_SERVER['REQUEST_METHOD'];

$bearer_token = get_bearer_token();

$role = getRoleFromToken($bearer_token);

switch ($http_method) {

   case "GET":

      switch ($role) {

         case "Anonyme":
            anonymous_access();
            break;

         case "Moderator":
            moderator_access();
            break;

         case "Publisher":
            publisher_access($bearer_token);
            break;

         default:
            deliver_response(401, "Unauthorized", null);
      }
      break;

   /// Cas de la méthode POST
   case "POST":
      /// Récupération des données envoyées par le Client
      $postedData = file_get_contents('php://input');
      $data = json_decode($postedData, true);
      $token = get_bearer_token();
      $tokenParts = explode('.', $token);
      $payload = base64_decode($tokenParts[1]);
      $role = json_decode($payload)["role"];

      if (is_jwt_valid($token) && $role == "Publisher") {
         if (isset($data["Auteur"]) && isset($data["Contenu"])) {

            $req = $linkpdo->prepare("insert into article(Auteur, Contenu, Identifiant) values(:auteur, :contenu, :identifiant)");


            $req->bindValue("auteur", $data["Auteur"]);
            $req->bindValue("contenu", $data["Contenu"]);
            $req->bindValue("identifiant", json_decode($payload)["username"]); //json_decode($payload)->username
            $req->execute();

            $dernierId = $linkpdo->lastInsertId();
            $requete = $linkpdo->prepare("select * from article where id = :id");
            $requete->bindValue('id', $dernierId);
            $requete->execute();
            $dataAEnvoyer = $requete->fetchAll(PDO::FETCH_ASSOC);

            /// Envoi de la réponse au Client
            deliver_response(201, "OK", $dataAEnvoyer);
         } else {
            deliver_response(400, "Bad request", null);
         }
      } else {
         deliver_response(401, "Unauthorized", null);
      }


      break;

   //   case "PUT" :

   case "DELETE":
      switch ($role) {
         case "Anonyme":
            deliver_response(401, "Unauthorized", null);
            break;

         case "Moderator":
            if (isset($_GET["id_article"])){
               moderator_delete($_GET["id_article"]);
            } else {
               deliver_response(400, "Bad request", null);
            }
            break;

         case "Publisher":
            if (isset($_GET["id_article"])){
               publisher_delete($Id_Article, $bearer_token);
            }
            break;

         default:
            deliver_response(401, "Unauthorized", null);
      }
      break;


   default:
      deliver_response(405, "Commande HTTP non supportée", NULL);
}


function getRoleFromToken($bearer_token)
{
   if (empty($bearer_token)) {
      $role = "Anonyme";
   } else {
      if (!is_jwt_valid($bearer_token)) {
         deliver_response(498, "Jeton invalide", null);
      } else {
         $tokenParts = explode('.', $bearer_token);
         $payload = base64_decode($tokenParts[1]);
         $role = json_decode($payload)->role;
      }
   }
   return $role;
}

?>