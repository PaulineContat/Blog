<?php

include('config.php'); //$linkpdo

function moderator_delete($Id_Article) {
	global $linkpdo;
	$Id_Article = htmlspecialchars($Id_Article);
	$dataArticles = getArticlesFromIdArticle($Id_Article);

	if(!$dataArticles){

		deliver_response(404, "No data found", null);

	} else {

		$data = array();

		$dataTotalLikes = getLikesFromIdArticle($Id_Article);
		$nbLikes = $dataTotalLikes[0];
		$data['Id_Article'] = $Id_Article;
		$data['likes'] = $nbLikes;

		$dataTotalDislikes = getDislikesFromIdArticle($Id_Article);		
		$nbDislikes = $dataTotalDislikes[0];
		$data['dislikes']=$nbDislikes;

		$dataInfos = getInfosFromIdArticle($Id_Article);
		$auteur = $dataInfos['Identifiant'];
		$contenu = $dataInfos['Contenu'];
		$date_publi = $dataInfos['Date_Publication'];
		$data['Identifiant']=$auteur;
		$data['Contenu']=$contenu;
		$data['Date_Publication']=$date_publi;

		$dataListeLikes = getUsersWhoLikedFromIdArticle($Id_Article);
		if(!$dataListeLikes){
			$data['listeLikes']="No likes at the moment";
		} else {
			$listeLikes = $dataListeLikes;
			$data['listeLikes']=$listeLikes;
		}

		$dataListeDislikes = getUsersWhoDislikedFromIdArticle($Id_Article);
		if(!($dataListeDislikes)){
			$data['listeDislikes']="No dislikes at the moment";
		} else {
			$listeDislikes = $dataListeDislikes;
			$data['listeDislikes']=$listeDislikes;
		}

		$reqSuppr = $linkpdo->prepare("DELETE FROM article WHERE Id_Article = :id");
		$reqSuppr->bindValue(':id', $Id_Article, PDO::PARAM_INT);
		$reqSuppr->execute();
		deliver_response(200, "Article successfully deleted", $data);
	}
}

function publisher_delete($Id_Article, $bearer_token) {
	global $linkpdo;
	$Id_Article = htmlspecialchars($Id_Article);
	$bearer_token = htmlspecialchars($bearer_token);
	$tokenParts = explode('.', $bearer_token);
	$payload = base64_decode($tokenParts[1]);
	$Id_Utilisateur = json_decode($payload)->username;
	$dataArticles = getArticlesFromUtilisateur($Id_Utilisateur);
	
	getArticlesFromIdArticle($Id_Article);

	if(!$dataArticles){
		deliver_response(404, "No data found", null);

	} else {
		$data = array();

		$dataTotalLikes = getLikesFromIdArticle($Id_Article);

		if(!$dataTotalLikes){
			deliver_response(404, "No data found", null);

		} else {
			$nbLikes = $dataTotalLikes[0];
			$data['Id_Article'] = $Id_Article;
			$data['likes'] = $nbLikes;
		}

		$dataTotalDislikes = getDislikesFromIdArticle($Id_Article);

		if(!$dataTotalDislikes){
			deliver_response(404, "No data found", null);
		} else {
			$nbDislikes = $dataTotalDislikes[0];
			$data['dislikes']=$nbDislikes;
		}

		$dataInfos = getInfosFromIdArticle($Id_Article);

		if(!$dataInfos){
			deliver_response(404, "No data found", null);
		} else {
			$auteur = $dataInfos['Identifiant'];
			$contenu = $dataInfos['Contenu'];
			$date_publi = $dataInfos['Date_Publication'];
			$data['Identifiant']=$auteur;
			$data['Contenu']=$contenu;
			$data['Date_Publication']=$date_publi;
		}

		$dataListeLikes = getUsersWhoLikedFromIdArticle($Id_Article);

		if(!$dataListeLikes){
			$data['listeLikes']="Pas de likes pour le moment";
		} else {
			$listeLikes = $dataListeLikes;
			$data['listeLikes']=$listeLikes;
		}

		$dataListeDislikes = getUsersWhoDislikedFromIdArticle($Id_Article);

		if(!($dataListeDislikes)){
			$data['listeDislikes']="Pas de dislikes pour le moment";
		} else {
			$listeDislikes = $dataListeDislikes;
			$data['listeDislikes']=$listeDislikes;
		}

		$reqSuppr = $linkpdo->prepare("DELETE FROM article WHERE Id_Article = :id");
		$reqSuppr->bindValue(':id', $Id_Article, PDO::PARAM_INT);
		$reqSuppr->execute();
		deliver_response(200, "Article successfully deleted", $data);
	}
}

?>