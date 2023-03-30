<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Comment.php');
require_once('classes/Repository/CommentRepository.php');


$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();
$commentRepository = new CommentRepository();


$user = User::isLogged();
$comment = new Comment();
$articleId = $_POST["articleId"];

if(isset($_POST['comment'])&&!empty($_POST['comment'])){

    $comment->setComment(htmlentities($_POST['comment']));
    $comment->setUserId(htmlentities($user->getId()));
    $comment->setArticleId(htmlentities($articleId));
}


$commentRepository->addComment($comment);
header('location:show-article.php?id=' . $articleId . '#comment');


