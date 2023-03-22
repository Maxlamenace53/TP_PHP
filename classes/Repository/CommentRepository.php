<?php
require_once('AbstractRepository.php');

class CommentRepository extends AbstractRepository
{
    /**
     * @return Comment[]
     */
    public function getComments(): array
    {
        $sql = "SELECT * FROM commentaire";
        $statement = $this->db->prepare($sql);
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, Comment::class);

    }

    /**
     * @param int $articleId
     *
     * @return Comment[]
     */

    public function findCommentByArticle(int $articleId): array
    {
        $sql = "SELECT * FROM commentaire WHERE idArticle = :idArticle";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'idArticle' => $articleId
        ]);

        return @$statement->fetchAll(PDO::FETCH_CLASS, Comment::class);
    }


    /**
     * @param int $userId
     *
     * @return Comment|bool
     */
    public function findCommentByUser(int $userId): Comment|bool
    {
        $sql = "SELECT * FROM commentaire WHERE idUser = :id";
        $statement = $this->db->prepare($sql);
        $statement->execute([
            'id' => $userId
        ]);

        return $statement->fetchObject(Comment::class);
    }



    public function deleteComment(int $commentId): void
    {
        $sql = "DELETE FROM commentaire WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $commentId
        ]);
    }

    public function editComment(Comment $comment)
    {
        $sql = "UPDATE commentaire SET commentaire = :comment 
               WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id'=>$comment->getId(),
            'comment'=> $comment->getComment()

            ]);
    }

    public function addComment ( Comment $comment)
    {
        $sql = "INSERT INTO commentaire (commentaire, idUser,idArticle )
            VALUES (:commentaire, :idUser, :idArticle)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'commentaire'=>$comment->getComment(),
            'idUser'=>$comment->getUserId(),
            'idArticle' =>$comment->getArticleId()
            ]);
    }

}