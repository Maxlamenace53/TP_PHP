<?php
    session_start();
    require_once('classes/Article.php');
    require_once('classes/Repository/ArticleRepository.php');
    require_once('classes/User.php');
    require_once('classes/Repository/UserRepository.php');

    $userRepository = new UserRepository();
    $articleRepository = new ArticleRepository();
    $articles = $articleRepository->getArticles();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="public/css/index.css">
    <title>Blog</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="newsfeed-container">
            <div class="newsfeed-content">
                <div class="articles-container">
                    <?php foreach ($articles as $article): ?>
                    <?php $auteur = $userRepository->getById($article->getUserId()) ?>
                    <a href="/show-article.php?id=<?= $article->getId() ?>" class="article block">
                        <div class="overflow">
                            <div class="img-container" style="background-image:url('<?= $article->getImageFullPath() ?>')"></div>
                        </div>
                        <h3><?= $article->getTitle() ?></h3>
                        <span>Rédigé par : <?= $auteur->getNom() . ' ' . $auteur->getPrenom() ?></span>
                    </a>
                    <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php require_once 'includes/footer.php' ?>
</div>

</body>
</html>