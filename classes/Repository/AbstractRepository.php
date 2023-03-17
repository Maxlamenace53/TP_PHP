<?php

abstract class AbstractRepository
{
    protected $db;

    public function __construct()
    {
        $dsn = 'mysql:host=localhost;dbname=tp_blog';
        $user = 'max';
        $password = '';

        try {
            $this->db = new PDO($dsn, $user, $password);
        } catch (Exception $e) {
            echo 'Connexion échouée : ' . $e->getMessage();
        }
    }
}