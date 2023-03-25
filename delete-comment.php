<?php
session_start();
require_once('classes/Article.php');
require_once('classes/User.php');
require_once('classes/Repository/ArticleRepository.php');
require_once ('classes/Comment.php');
require_once ('classes/Repository/CommentRepository.php');

$user = User::isLogged();
$articleId = $_GET['id'];
/*
echo '<pre>';
var_dump($articleId);
echo '</pre>';*/

$commentId = $_GET['comment'];

/*echo '<pre>';
var_dump($commentId);
echo '</pre>';*/

$commentRepository = new CommentRepository();

if($user === false) {
    header('location:show-article.php?id=' . $articleId);
}


$commentRepository->deleteComment($commentId);

header('location:show-article.php?id=' . $articleId);



