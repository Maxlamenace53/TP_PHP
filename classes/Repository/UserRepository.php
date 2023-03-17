<?php
require_once('AbstractRepository.php');
class UserRepository extends AbstractRepository
{
    public function addUser(User $user)
    {
        $sql = "INSERT INTO user (prenom,nom,email,password)
            VALUES (:prenom,:nom,:email,:password)";
        $query = $this->db->prepare($sql);
        $query->execute([
            'prenom' => $user->getPrenom(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'password' => sha1($user->getPassword()),
        ]);
    }

    public function getByEmail($email)
    {
        $sql = "SELECT id, prenom, nom, email FROM user WHERE email = :email";
        $query = $this->db->prepare($sql);
        $query->execute([
            'email' => $email
        ]);
        return $query->fetchObject(User::class);
    }

    public function getByEmailAndPassword($email, $password)
    {
        $sql = "SELECT id, prenom, nom, email FROM user WHERE email = :email AND password = :password";
        $query = $this->db->prepare($sql);
        $query->execute([
            'email' => $email,
            'password' => sha1($password),
        ]);

        return $query->fetchObject(User::class);
    }

    public function getById($id)
    {
        $sql = "SELECT id, prenom, nom, email FROM user WHERE id = :id";
        $query = $this->db->prepare($sql);
        $query->execute([
            'id' => $id
        ]);
        return $query->fetchObject(User::class);
    }
}