<?php

$filename = 'articles.json';

if (file_exists($filename)) {
    $articles = json_decode(file_get_contents($filename), true);
    if (!is_array($articles)) {
        $articles = [];
    }
}

$dsn = 'mysql:host=localhost;dbname=tp_blog';
$user = 'mflasquin2';
$password = 'mflasquin2';

try {
    $db = new PDO($dsn, $user, $password);
} catch (Exception $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
}

foreach ($articles as $article) {
    $sql = "INSERT INTO article (title,image,category,content)
            VALUES (:title,:image,:category,:content)";
    $query = $db->prepare($sql);

    $query->execute([
        'title' => $article['title'],
        'image' => $article['image'],
        'category' => $article['category'],
        'content' => $article['content'],
    ]);
}




