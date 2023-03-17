<?php
require_once('AbstractRepository.php');
class ArticleRepository extends AbstractRepository
{
    /**
     * @return Article[]
     */
    public function getArticles(): array
    {
        $sql = "SELECT * FROM article";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Article::class);
    }

    /**
     * @param int $id
     *
     * @return Article|bool
     */
    public function findArticle(int $id): Article|bool
    {
        $sql = "SELECT * FROM article WHERE id = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $id
        ]);

        return $statement->fetchObject(Article::class);
    }

    public function deleteArticle(int $articleId): void
    {
        $sql = "DELETE FROM article WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $articleId
        ]);
    }

    public function editArticle(Article $article) {
        $sql = "UPDATE article SET title = :title, image = :image, category = :category, content = :content
               WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $article->getId(),
            'title' => $article->getTitle(),
            'image' => $article->getImage(),
            'category' => $article->getCategory(),
            'content' => $article->getContent(),
        ]);
    }

    public function addArticle(Article $article, User $user)
    {
        $sql = "INSERT INTO article (title,image,category,content,userId)
            VALUES (:title,:image,:category,:content,:userId)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'title' => $article->getTitle(),
            'image' => $article->getImage(),
            'category' => $article->getCategory(),
            'content' => $article->getContent(),
            'userId' => $user->getId()
        ]);
    }
}