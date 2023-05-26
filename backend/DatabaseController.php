<?php

namespace backend;

use PDO;
use PDOException;
use PDOStatement;

class DatabaseController
{
    public PDO $pdo;

    public function __construct($config)
    {
        try {
            $this->pdo = new PDO($config['dsn'], $config['username'], $config['password']);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public function getUserPDOByLogin($login): PDOStatement
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE `login` = ?");
        $stmt->execute([$login]);
        return $stmt;
    }

    public function getUserPDOByPhoneNumber($phoneNumber): PDOStatement
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE `phone_number` = ?");
        $stmt->execute([$phoneNumber]);
        return $stmt;
    }

    public function getUserPDOByEmail($email): PDOStatement
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE `email` = ?");
        $stmt->execute([$email]);
        return $stmt;
    }

    public function createNewUser($login, $phoneNumber, $email, $password)
    {
        $query = "INSERT INTO users (login, phone_number, email, password) VALUES (:login, :phoneNumber, :email, :password)";
        $params = [
            'login' => $login,
            'phoneNumber' => $phoneNumber,
            'email' => $email,
            'password' => $password
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function addAuthorizationToken($id, $authorizationToken) {
        $query = "UPDATE users SET `authorization_token` = :authorizationToken WHERE `id` = :id";
        $params = [
            ':id' => $id,
            ':authorizationToken' => $authorizationToken
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }
}