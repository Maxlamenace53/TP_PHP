<?php
session_start();
require_once('classes/User.php');
require_once('classes/Repository/UserRepository.php');

$user = new User();
$userRepository = new UserRepository();
$errors = [];

if(!empty($_POST)) {
    $user->setEmail($_POST['email']);
    $user->setNom($_POST['lastname']);
    $user->setPrenom($_POST['firstname']);
    $user->setPassword($_POST['password']);

    //On vérifie que les champs ne sont pas vides
    if(empty($user->getEmail())) {
        $errors['email'] = 'Le champ est obligatoire';
    }
    if(empty($user->getNom())) {
        $errors['lastname'] = 'Le champ est obligatoire';
    }
    if(empty($user->getPrenom())) {
        $errors['firstname'] = 'Le champ est obligatoire';
    }
    if(empty($user->getPassword())) {
        $errors['password'] = 'Le champ est obligatoire';
    }
    if(empty($_POST['confirmpassword'])) {
        $errors['confirmPassword'] = 'Le champ est obligatoire';
    }

    //On vérifie que le champ mot de passe et le champ confirmation mot de passe sont identiques
    if($user->getPassword() !== $_POST['confirmpassword']) {
        $errors['confirmPassword'] = 'Le champ confirmation de mot de passe doit être identique au champ mot de passe';
    }

    //On vérifie que le champ email est un champ email valide
    if(filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL) === false) {
        $errors['email'] = 'Veuillez saisir un email valide';
    }

    //On vérifie que le champs mot de passe fait plus de 6 caractères
    if(strlen($user->getPassword()) < 6) {
        $errors['password'] = 'Le mot de passe doit faire plus de 6 caractères';
    }

    //On vérifie qu'un utilisateur avec cet email email n'existe pas déjà
    if($userRepository->getByEmail($user->getEmail()) !== false) {
        $errors['email'] = 'Cet email est déjà utilisé';
    }

    if(empty($errors)) {
        $userRepository->addUser($user);
        header('Location: index.php');
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/head.php' ?>
    <title>Inscription</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="block p-20 form-container">
            <h1>Inscription</h1>
            <form action="#" , method="POST">
                <div class="form-control">
                    <label for="firstname">Prénom</label>
                    <input type="text" name="firstname" id="firstname" value="<?= $user->getPrenom() ?>">
                </div>
                <?php if (isset($errors['firstname'])) : ?>
                    <p class="text-danger"><?= $errors['firstname'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="lastname">Nom</label>
                    <input type="text" name="lastname" id="lastname" value="<?= $user->getNom() ?>" >
                </div>
                <?php if (isset($errors['lastname'])) : ?>
                    <p class="text-danger"><?= $errors['lastname'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" value="<?= $user->getEmail() ?>" >
                </div>
                <?php if (isset($errors['email'])) : ?>
                    <p class="text-danger"><?= $errors['email'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password">
                </div>
                <?php if (isset($errors['password'])) : ?>
                    <p class="text-danger"><?= $errors['password'] ?></p>
                <?php endif; ?>
                <div class="form-control">
                    <label for="confirmpassword">Confirmation Mot de passe</label>
                    <input type="password" name="confirmpassword" id="confirmpassword">
                </div>
                <?php if (isset($errors['confirmPassword'])) : ?>
                    <p class="text-danger"><?= $errors['confirmPassword'] ?></p>
                <?php endif; ?>
                <div class="form-actions">
                    <a href="/" class="btn btn-secondary" type="button">Annuler</a>
                    <button class="btn btn-primary" type="submit">Valider</button>
                </div>
            </form>
        </div>
    </div>
    <?php require_once 'includes/footer.php' ?>
</div>

</body>

</html>