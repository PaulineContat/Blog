<?php

include('config.php'); //$linkpdo

function getArticles(){
	global $linkpdo;
	$reqArticles = $linkpdo->query("SELECT Id_Article FROM article;");
	return $reqArticles->fetchAll();
}

function getArticlesFromIdArticle($Id_Article){
	global $linkpdo;
	$reqArticles = $linkpdo->prepare("SELECT Id_Article FROM article WHERE Id_Article = :id;");
	$reqArticles->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqArticles->execute();
	return $reqArticles->fetchAll();
}

function getArticlesFromUtilisateur($Id_Utilisateur){
	global $linkpdo;
	$reqArticles = $linkpdo->prepare("SELECT Id_Article FROM article WHERE Identifiant = :id;");
	$reqArticles->bindValue(':id', $Id_Utilisateur, PDO::PARAM_STR);
	$reqArticles->execute();
	return $reqArticles->fetchAll();
}

function getLikesFromIdArticle($Id_Article){
	global $linkpdo;
	$reqLikes = $linkpdo->prepare("SELECT count(*) FROM article, liker, utilisateur WHERE article.Id_Article = liker.Id_Article
		AND liker.Identifiant = utilisateur.Identifiant
		AND liker.Est_Dislike = 0
		AND article.Id_Article = :id;");
	$reqLikes->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqLikes->execute();
	return $reqLikes->fetch();
}

function getDislikesFromIdArticle($Id_Article){
	global $linkpdo;
	$reqDislikes = $linkpdo->prepare("SELECT count(*) FROM article, liker, utilisateur WHERE article.Id_Article = liker.Id_Article
		AND liker.Identifiant = utilisateur.Identifiant
		AND liker.Est_Dislike = 1
		AND article.Id_Article = :id;");
	$reqDislikes->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqDislikes->execute();  
	return $reqDislikes->fetch();    
}     

function getInfosFromIdArticle($Id_Article){
	global $linkpdo;
	$reqInfos = $linkpdo->prepare("SELECT article.Identifiant, Contenu, Date_Publication FROM article, utilisateur WHERE article.Identifiant = utilisateur.Identifiant AND article.Id_Article = :id;");
	$reqInfos->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqInfos->execute();
	return $reqInfos->fetch();
}

function getUsersWhoLikedFromIdArticle($Id_Article){
	global $linkpdo;

	$reqListeLikes = $linkpdo->prepare("SELECT utilisateur.Identifiant FROM article, liker, utilisateur WHERE article.Id_Article = liker.Id_Article
		AND liker.Identifiant = utilisateur.Identifiant
		AND liker.Est_Dislike = 0
		AND article.Id_Article = :id;");
	$reqListeLikes->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqListeLikes->execute();
	return $reqListeLikes->fetchAll(PDO::FETCH_COLUMN);
}

function getUsersWhoDislikedFromIdArticle($Id_Article){
	global $linkpdo;
	$reqListeDislikes = $linkpdo->prepare("SELECT utilisateur.Identifiant FROM article, liker, utilisateur WHERE article.Id_Article = liker.Id_Article
		AND liker.Identifiant = utilisateur.Identifiant
		AND liker.Est_Dislike = 1
		AND article.Id_Article = :id;");
	$reqListeDislikes->bindValue(':id', $Id_Article, PDO::PARAM_INT);
	$reqListeDislikes->execute();
	return $reqListeDislikes->fetchAll(PDO::FETCH_COLUMN);
}

?>