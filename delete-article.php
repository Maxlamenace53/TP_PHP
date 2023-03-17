<?php
session_start();
require_once('classes/Article.php');
require_once('classes/User.php');
require_once('classes/Repository/ArticleRepository.php');

$user = User::isLogged();

if($user === false) {
    header('Location: login.php');
}

$articleRepository = new ArticleRepository();

//Je récupère l'id de l'article à supprimer
$articleId = $_GET['id'];
//Récupération de l'article et on vérifie si je suis bien l'auteur de l'article
$article = $articleRepository->findArticle($articleId);

if($user->getId() !== $article->getUserId()) {
    header('Location: index.php');
}
//Suppression de l'article
$articleRepository->deleteArticle($articleId);
//Je redirige vers l'accueil
header('Location: index.php');
