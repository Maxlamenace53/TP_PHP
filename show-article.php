<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Comment.php');
require_once('classes/Repository/CommentRepository.php');

$user = User::isLogged();

$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();
$commentRepository = new CommentRepository();

//Si le paramètre id n'existe pas
if (!isset($_GET['id'])) {
    header('Location: index.php');
}

//On récupère l'id de l'article qu'on a dans l'url
$articleId = $_GET['id'];

//On va chercher dans la liste des articles, l'article qui correspond à l'id qu'on a dans l'url
$articleToShow = $articleRepository->findArticle($articleId);
//Récupération des commentaires lié à l'article
$commentsToShow = $commentRepository->findCommentByArticle($articleId);

// on vérifie avec un var-dump
//echo '<pre>';
//var_dump($commentsToShow);
//echo '</pre>';

//Si aucun article ne correspond dans la liste
if ($articleToShow === false) {
    header('Location: index.php');
}

$auteur = $userRepository->getById($articleToShow->getUserId());
/*$auteurComment = $commentRepository ->*/


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">
    <title>Article</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="article-container">
            <a class="article-back" href="/">Retour à la liste des articles</a>
            <div class="article-cover-img"
                 style="background-image:url(<?= $articleToShow->getImageFullPath() ?>)"></div>
            <h1 class="article-title"><?= $articleToShow->getTitle() ?></h1>
            <span>Rédigé par : <?= $auteur->getNom() . ' ' . $auteur->getPrenom() ?></span>
            <div class="separator"></div>
            <p class="article-content"><?= $articleToShow->getContent() ?></p>
            <h3 class="comment">Espaces commentaires</h3>

            <?php if ($user !== false && ($auteur->getId() === $user->getId())): ?>
                <div class="action">
                    <a class="btn btn-secondary" href="/delete-article.php?id=<?= $articleId ?>">Supprimer</a>
                    <a class="btn btn-primary" href="/form-article.php?id=<?= $articleId ?>">Editer l'article</a>
                </div>
                <div>
                    <form action="#" name="comment">
                        <label for="comment">Nouveau commentaire</label>
                        <textarea class="texarea-comment" minlength="10" maxlength="500" required enabled
                                  placeholder="Veuillez écrire votre commentaire">

                </textarea>
                        <button type="submit">Envoyé</button>
                    </form>


                </div>
            <?php endif; ?>


            <div class="user-comment">
                <?php foreach ($commentsToShow as $commentToShow):?>
                    <p class="article-comment"> <?= $commentToShow->getCommentaire()  ?> </p>
                    <span>Rédigé par : <?= $auteur->getNom() . ' ' . $auteur->getPrenom() ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>