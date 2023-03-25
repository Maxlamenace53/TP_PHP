<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Comment.php');
require_once('classes/Repository/CommentRepository.php');

/*echo '<pre>';
var_dump($_POST["comment"]);
echo '</pre>';*/




/*
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';*/





$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();
$commentRepository = new CommentRepository();
$comment = new Comment();


$user = User::isLogged();
$articleId = $_POST["articleId"];



$comment->setComment(htmlentities($_POST['comment']));
$comment->setUserId(htmlentities($user->getId()));
$comment->setArticleId(htmlentities($articleId));

if (empty($_POST['comment'])) {

    header('location:show-article.php?id=' . $articleId);
} else {
    $commentRepository->addComment($comment);
    header('location:show-article.php?id=' . $articleId);
}

