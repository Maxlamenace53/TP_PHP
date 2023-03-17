<header>
    <a href="/" class="logo">Blog</a>
    <ul class="header-menu">
        <?php if(isset($_SESSION['user'])): ?>
        <li class=<?= $_SERVER['REQUEST_URI'] === '/form-article.php' ? 'active' : '' ?>>
            <a href="/form-article.php">Écrire un article</a>
        </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/logout.php' ? 'active' : '' ?>>
                <a href="/logout.php">Se déconnecter</a>
            </li>
        <?php endif ?>
        <?php if(!isset($_SESSION['user'])): ?>
        <li class=<?= $_SERVER['REQUEST_URI'] === '/register.php' ? 'active' : '' ?>>
            <a href="/register.php">S'inscrire</a>
        </li>
        <li class=<?= $_SERVER['REQUEST_URI'] === '/login.php' ? 'active' : '' ?>>
            <a href="/login.php">Se connecter</a>
        </li>
        <?php endif ?>
    </ul>
</header>