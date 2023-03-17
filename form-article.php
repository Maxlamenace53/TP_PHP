<?php
session_start();

require_once('classes/Article.php');
require_once('classes/User.php');
require_once('classes/Repository/ArticleRepository.php');

$user = User::isLogged();

if($user === false) {
    header('Location: login.php');
}

const ERROR_REQUIRED = 'Veuillez renseigner ce champ';
const ERROR_TITLE_TOO_SHORT = 'Le titre est trop court';
const ERROR_CONTENT_TOO_SHORT = 'L\'article est trop court';
const ERROR_IMAGE_URL = 'L\'image doit être une url valide';
const ERROR_IMAGE_TYPE = 'L\'image doit être du type jpg, png ou gif';

$erreurs = [];
$articleRepository = new ArticleRepository();
$article = new Article();

//Est-ce que je suis dans le cas d'une modification
if (isset($_GET['id'])) {
    $articleId = $_GET['id'];
    $article = $articleRepository->findArticle($articleId);

    if($user->getId() !== $article->getUserId()) {
        header('Location: index.php');
    }
}

//J'ai soumis le formulaire de création ou d'édition
if (!empty($_POST)) {
    $fileName = '';
    if(!empty($_FILES)) {
        $targetDir = "images/";
        $fileName = basename($_FILES["image"]["name"]);
        $targetFile = $targetDir . $fileName;
        $imageFileType = strtolower(pathinfo($targetFile,PATHINFO_EXTENSION));
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif" ) {
            $errors['image'] = ERROR_IMAGE_TYPE;
        } else {
            move_uploaded_file($_FILES['image']['tmp_name'], $targetFile);
        }
    }

    $article->setTitle(htmlentities($_POST['title']));
    $article->setImage($fileName);
    $article->setCategory(htmlentities($_POST['category']));
    $article->setContent(htmlentities($_POST['content']));

    if (empty($article->getTitle())) {
        $errors['title'] = ERROR_REQUIRED;
    } elseif (strlen($article->getTitle()) < 5) {
        $errors['title'] = ERROR_TITLE_TOO_SHORT;
    }

    if (empty($article->getImage())) {
        $errors['image'] = ERROR_REQUIRED;
    }

    if (empty($article->getCategory())) {
        $errors['category'] = ERROR_REQUIRED;
    }

    if (empty($article->getContent())) {
        $errors['content'] = ERROR_REQUIRED;
    } elseif (mb_strlen($article->getContent()) < 50) {
        $errors['content'] = ERROR_CONTENT_TOO_SHORT;
    }

    if (empty($errors)) {
        if(isset($_GET['id'])) {
            $articleRepository->editArticle($article);
        } else {
            $articleRepository->addArticle($article, $user);
        }

        header('Location: index.php');
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <?php require_once 'includes/head.php' ?>
    <link rel="stylesheet" href="/public/css/form-article.css">
    <title>Créer un article</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="block p-20 form-container">
            <h1>Écrire un article</h1>
            <form action="#" , method="POST" enctype="multipart/form-data">
                <div class="form-control">
                    <label for="title">Titre</label>
                    <input type="text" name="title" id="title" value="<?= $article->getTitle() ?? '' ?>">
                </div>
                <?php if (isset($errors['title'])) : ?>
                    <p class="text-danger"><?= $errors['title'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" value="<?= $article->getImage() ?? '' ?>">
                </div>
                <?php if (isset($errors['image'])) : ?>
                    <p class="text-danger"><?= $errors['image'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="category">Catégorie</label>
                    <select name="category" id="category">
                        <option <?= $article->getCategory() === 'technologie' ? 'selected' : '' ?>
                                value="technologie">Technologie
                        </option>
                        <option <?= $article->getCategory() === 'nature' ? 'selected' : '' ?> value="nature">
                            Nature
                        </option>
                        <option <?= $article->getCategory() === 'politique' ? 'selected' : '' ?>
                                value="politique">Politique
                        </option>
                    </select>
                </div>
                <?php if (isset($errors['category'])) : ?>
                    <p class="text-danger"><?= $errors['category'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="content">Content</label>
                    <textarea name="content" id="content"><?= $article->getContent() ?? '' ?></textarea>
                </div>
                <?php if (isset($errors['content'])) : ?>
                    <p class="text-danger"><?= $errors['content'] ?></p>
                <?php endif; ?>
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                    <button class="btn btn-primary" type="submit">Sauvegarder</button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>