<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Comment.php');
require_once('classes/Repository/CommentRepository.php');

$user = User::isLogged();
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_COMMENT_TOO_SHORT = 'Le commentaire est trop court';
const ERROR_COMMENT_TOO_LONG = 'Le commentaire est trop long';

if($user === false) {
    header('Location: login.php');
}


$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();
$commentRepository = new CommentRepository();
$erreurs = [];
$comment = new Comment();


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



//Si aucun article ne correspond dans la liste
if ($articleToShow === false) {
    header('Location: index.php');
}

$auteur = $userRepository->getById($articleToShow->getUserId());






$comment->setComment(htmlentities($_POST['comment']));
$comment->setUserId(htmlentities($user->getId()));
$comment->setArticleId(htmlentities($articleId));

echo '<pre>';
var_dump($comment);
echo '</pre>';


$commentRepository->addComment($comment);



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

            <?php endif; ?>


            <div class="user-comment">
                <div>
                    <form action="#"  method="POST" >
                    <textarea name="comment" class="texarea-comment" minlength="10" maxlength="500"
                              required <?= $user ? 'enabled' : 'disabled' ?>
                              placeholder="Veuillez écrire votre commentaire">

                     </textarea>
                        <?php if ($user !== false): ?>
                            <button type="submit">Soumettre</button>
                        <?php else: ?>
                            <span>Veuillez vous connecter pour commenter !</span>
                        <?php endif; ?>

                    </form>


                </div>


                <?php foreach ($commentsToShow as $commentToShow):
                    $auteurComment = $commentRepository->findUserComment($commentToShow->getId());
                    ?>
                    <p class="article-comment"> <?= ucfirst($commentToShow->getComment()) ?>


                    <?php if ($user !== false && ($user->getEmail()=== $auteurComment->getEmail())): ?>

                    <form action='#' method="post">
                        <div>
                            <button>Modifier</button>
                            <button>Supprimer</button>
                        </div>
                    </form>
                   <?php endif; ?>
                    </p>
                    <span>Rédigé par
                        <?= strtoupper($auteurComment->getNom()) . ' ' . ucfirst($auteurComment->getPrenom()) ?>

                    </span>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>