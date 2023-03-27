<?php
session_start();
require_once('classes/Article.php');
require_once('classes/Repository/ArticleRepository.php');
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');
require_once('classes/Comment.php');
require_once('classes/Repository/CommentRepository.php');

$user = User::isLogged();

/*if($user === false) {
    header('Location: login.php');
}*/
const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_COMMENT_TOO_SHORT = 'Le commentaire est trop court';
const ERROR_COMMENT_TOO_LONG = 'Le commentaire est trop long';

$errors = [];
$articleRepository = new ArticleRepository();
$userRepository = new UserRepository();
$commentRepository = new CommentRepository();
$comment = new Comment();

$editCurrentComment = '';

/*echo '<pre>';
var_dump($commentRepository->findCommentById($_GET['comment']));
echo '</pre>';*/


if (isset($_GET["comment"]) && !empty($_GET["comment"])) {

    $commentToModify = $commentRepository->findCommentById($_GET['comment'])->getComment();
    $editCurrentComment = $commentToModify;
    $commentRepository->deleteComment($_GET['comment']);


}


//On récupère l'id de l'article qu'on a dans l'url
$articleId = $_GET['id'];

//On va chercher dans la liste des articles, l'article qui correspond à l'id qu'on a dans l'url
$articleToShow = $articleRepository->findArticle($articleId);
//Récupération des commentaires lié à l'article
$commentsToShow = $commentRepository->findCommentByArticle($articleId);


//Si aucun article ne correspond dans la liste
if ($articleToShow === false) {
    header('Location: show-article.php?id=' . $articleId);
}


$auteur = $userRepository->getById($articleToShow->getUserId());


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/show-article.css">
    <title>Article <?='de '.$auteur->getPrenom();?> </title>
    <script src="public/js/index.js"></script>
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
            <span>Rédigé par : <?= $auteur->getPrenom() . ' ' . $auteur->getNom() ?></span>
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
                    <form action="comment.php?commentId=" method="POST" method="get">
                    <textarea
                            wrap="hard"
                            rows="4" cols="80"
                            id="comment" name="comment" class="texarea-comment"
                            required <?= $user ? 'enabled' : 'disabled' ?>
                              placeholder="Veuillez écrire votre commentaire"><?php echo $editCurrentComment ?></textarea>
                        <input id="textarea-comment" type="hidden" name="articleId" value="<?= $articleId ?>">
                        <?php if ($user !== false): ?>
                            <button class="btn btn-primary" type="submit">Soumettre</button>
                        <?php else: ?>
                            <span>Veuillez vous connecter pour commenter !</span>
                        <?php endif; ?>

                    </form>


                </div>


                <?php foreach (array_reverse($commentsToShow) as $commentToShow):
                    $auteurComment = $commentRepository->findUserComment($commentToShow->getId());
                    ?>
                    <div class="article-comment"> <?= ucfirst($commentToShow->getComment()) ?></div>


                    <?php if ($user !== false && ($user->getEmail() === $auteurComment->getEmail())): ?>

                    <form action='show-article.php' method="get">
                        <div class="action">
                            <a class="btn btn-secondary"
                               href="/delete-comment.php?id=<?= $articleId ?>&comment=<?= $commentToShow->getId() ?>">Supprimer</a>
                            <a class="btn btn-primary"
                               href="/show-article.php?id=<?= $articleId ?>&comment=<?= $commentToShow->getId() ?>#comment">Editer
                                le commentaire</a>


                        </div>
                    </form>
                <?php endif; ?>
                    <span>Rédigé par
                        <?= strtoupper($auteurComment->getPrenom()) . ' ' . ucfirst($auteurComment->getNom()) ?>
                    </span>
                    </p>

                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>