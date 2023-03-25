<?php

include('fct_articles.php'); //getArticles

function deliver_response($status, $status_message, $data){

	/// Paramétrage de l'entête HTTP, suite
	header("HTTP/1.1 $status $status_message");

	/// Paramétrage de la réponse retournée
	$response['status'] = $status;
	$response['status_message'] = $status_message;
	$response['data'] = $data;

	/// Mapping de la réponse au format JSON
	$json_response = json_encode($response);
	echo $json_response;
}

function anonymous_access() {
	$dataArticles = getArticles();
	if (!$dataArticles) {
		deliver_response(404, "No data found", null);
	} else {
		$data = array();
		foreach($dataArticles as $index => $row){
			$dataInfos = getInfosFromIdArticle($row['Id_Article']);
			if (!$dataInfos) {
				deliver_response(404, "No data found", null);
			} else {
				$auteur = $dataInfos['Identifiant'];
				$contenu = $dataInfos['Contenu'];
				$date_publi = $dataInfos['Date_Publication'];
				$data[$index]['Identifiant'] = $auteur;
				$data[$index]['Contenu'] = $contenu;
				$data[$index]['Date_Publication'] = $date_publi;
			}
		}
		deliver_response(200, "Articles successfully displayed", $data);
	}
}

function moderator_access() {
	$dataArticles = getArticles();

	if(!$dataArticles){
		deliver_response(404, "No data found", null);

	} else {
		$data = array();

		foreach($dataArticles as $index => $row){

                    ////////////////Nombre de likes////////////////

			$dataTotalLikes = getLikesFromIdArticle($row['Id_Article']);

			if(!$dataTotalLikes){
				deliver_response(404, "No data found", null);

			} else {
				$nbLikes = $dataTotalLikes[0];
				$data[$index]['Id_Article'] = $row['Id_Article'];
				$data[$index]['likes'] = $nbLikes;
			}

                    ////////////////Nombre de dislikes////////////////

			$dataTotalDislikes = getDislikesFromIdArticle($row['Id_Article']);

			if(!$dataTotalDislikes){
				deliver_response(404, "No data found", null);
			} else {
				$nbDislikes = $dataTotalDislikes[0];
				$data[$index]['dislikes']=$nbDislikes;
			}

                    ////////////////Infos de l'article : auteur, contenu, date de publication////////////////

			$dataInfos = getInfosFromIdArticle($row['Id_Article']);

			if(!$dataInfos){
				deliver_response(404, "No data found", null);
			} else {
				$auteur = $dataInfos['Identifiant'];
				$contenu = $dataInfos['Contenu'];
				$date_publi = $dataInfos['Date_Publication'];
				$data[$index]['Identifiant']=$auteur;
				$data[$index]['Contenu']=$contenu;
				$data[$index]['Date_Publication']=$date_publi;
			}

                    ////////////////Liste des utilisateurs ayant liké l'article////////////////

			$dataListeLikes = getUsersWhoLikedFromIdArticle($row['Id_Article']);

			if(!$dataListeLikes){
				$data[$index]['listeLikes']="Pas de likes pour le moment";
			} else {
				$listeLikes = $dataListeLikes;
				$data[$index]['listeLikes']=$listeLikes;
			}

                    ////////////////Liste des utilisateurs ayant disliké l'article////////////////

			$dataListeDislikes = getUsersWhoDislikedFromIdArticle($row['Id_Article']);

			if(!($dataListeDislikes)){
				$data[$index]['listeDislikes']="Pas de dislikes pour le moment";
			} else {
				$listeDislikes = $dataListeDislikes;
				$data[$index]['listeDislikes']=$listeDislikes;
			}


		}

		deliver_response(200, "Articles successfully displayed", $data);  

	}

}

function publisher_access($bearer_token){
	if(isset($_GET["ownArticles"])){
		$tokenParts = explode('.', $bearer_token);
		$payload = base64_decode($tokenParts[1]);
		$Id_Utilisateur = json_decode($payload)->username;
		$dataArticles = getArticlesFromUtilisateur($Id_Utilisateur);

		if(!$dataArticles){
			deliver_response(404, "No data found, you did not published any articles yet.", null);

		} else {

			$data = array();

			foreach($dataArticles as $index => $row){

                        ////////////////Nombre de likes////////////////

				$dataTotalLikes = getLikesFromIdArticle($row['Id_Article']);

				if(!$dataTotalLikes){
					deliver_response(404, "No data found", null);

				} else {
					$nbLikes = $dataTotalLikes[0];
					$data[$index]['Id_Article'] = $row['Id_Article'];
					$data[$index]['likes'] = $nbLikes;
				}

                        ////////////////Nombre de dislikes////////////////

				$dataTotalDislikes = getDislikesFromIdArticle($row['Id_Article']);

				if(!$dataTotalDislikes){
					deliver_response(404, "No data found", null);
				} else {
					$nbDislikes = $dataTotalDislikes[0];
					$data[$index]['dislikes']=$nbDislikes;
				}

                        ////////////////Infos de l'article : auteur, contenu, date de publication////////////////

				$dataInfos = getInfosFromIdArticle($row['Id_Article']);

				if(!$dataInfos){
					deliver_response(404, "No data found", null);
				} else {
					$auteur = $dataInfos['Identifiant'];
					$contenu = $dataInfos['Contenu'];
					$date_publi = $dataInfos['Date_Publication'];
					$data[$index]['Identifiant']=$auteur;
					$data[$index]['Contenu']=$contenu;
					$data[$index]['Date_Publication']=$date_publi;
				}
			}
		}

	} else {
		$dataArticles = getArticles();

		if(!$dataArticles){
			deliver_response(404, "No data found", null);

		} else {
			$data = array();

			foreach($dataArticles as $index => $row){

                        ////////////////Nombre de likes////////////////

				$dataTotalLikes = getLikesFromIdArticle($row['Id_Article']);

				if(!$dataTotalLikes){
					deliver_response(404, "No data found", null);

				} else {
					$nbLikes = $dataTotalLikes[0];
					$data[$index]['Id_Article'] = $row['Id_Article'];
					$data[$index]['likes'] = $nbLikes;
				}

                        ////////////////Nombre de dislikes////////////////

				$dataTotalDislikes = getDislikesFromIdArticle($row['Id_Article']);

				if(!$dataTotalDislikes){
					deliver_response(404, "No data found", null);
				} else {
					$nbDislikes = $dataTotalDislikes[0];
					$data[$index]['dislikes']=$nbDislikes;
				}

                        ////////////////Infos de l'article : auteur, contenu, date de publication////////////////

				$dataInfos = getInfosFromIdArticle($row['Id_Article']);

				if(!$dataInfos){
					deliver_response(404, "No data found", null);
				} else {
					$auteur = $dataInfos['Identifiant'];
					$contenu = $dataInfos['Contenu'];
					$date_publi = $dataInfos['Date_Publication'];
					$data[$index]['Identifiant']=$auteur;
					$data[$index]['Contenu']=$contenu;
					$data[$index]['Date_Publication']=$date_publi;
				}
			}
		}
	}

	deliver_response(200, "Articles successfully displayed", $data);  
}

?>