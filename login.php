<?php
session_start();
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');

$userRepository = new UserRepository();
$errors = [];
$email = '';
$password = '';

if(!empty($_POST)) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //Je vérifie que le champ email n'est pas vide
    if(empty($email)) {
        $errors['email'] = "Ce champ est obligatoire";
    }

    //Je vérifie que le champ password n'est pas vide
    if(empty($password)) {
        $errors['password'] = "Ce champ est obligatoire";
    }

    //Est-ce que un utilisateur avec ces identifiants existe dans la base
    $user = $userRepository->getByEmailAndPassword($email, $password);
    if($user !== false) {
        $_SESSION['user'] = serialize($user);
        header('Location: index.php');
    }

    $errors['email'] = 'Identifiants incorrectes, <a href="register.php">créer un compte</a>';
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <title>Connexion</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="block p-20 form-container">
            <h1>Connexion</h1>
            <form action="#" , method="POST">
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= $email ?? '' ?>">
                    <?php if (isset($errors['email'])) : ?>
                        <p class="text-danger"><?= $errors['email'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-control">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password">
                    <?php if (isset($errors['password'])) : ?>
                        <p class="text-danger"><?= $errors['password'] ?></p>
                    <?php endif; ?>
                </div>
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                    <button class="btn btn-primary" type="submit">Connexion</button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>