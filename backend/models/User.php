<?php

namespace backend\models;

use PDO;
use PDOException;

class User
{
    public PDO $pdo;

    public string $id;
    public string $login;
    public string $phoneNumber;
    public string $email;
    public string $hashPassword;

    public function __construct($config, $id)
    {
        try {
            $this->pdo = new PDO($config['dsn'], $config['username'], $config['password']);
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE `id` = ?");
            $stmt->execute([$id]);
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch();
                $this->id = $user['id'];
                $this->login = $user['login'];
                $this->phoneNumber = $user['phone_number'];
                $this->email = $user['email'];
                $this->hashPassword = $user['password'];
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }

    public function removeAuthorizationToken() {
        $stmt = $this->pdo->prepare("UPDATE users SET `authorization_token` = '' WHERE `id` = ?");
        $stmt->execute([$this->id]);
    }

    public function updateLogin($login) {
        $this->login = $login;
        $query = "UPDATE users SET `login` = :login WHERE `id` = :id";
        $params = [
            ':id' => $this->id,
            ':login' => $login
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function updatePhoneNumber($phoneNumber) {
        $this->phoneNumber = $phoneNumber;
        $query = "UPDATE users SET `phone_number` = :phoneNumber WHERE `id` = :id";
        $params = [
            ':id' => $this->id,
            ':phoneNumber' => $phoneNumber
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function updateEmail($email) {
        $this->email = $email;
        $query = "UPDATE users SET `email` = :email WHERE `id` = :id";
        $params = [
            ':id' => $this->id,
            ':email' => $email
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }

    public function updatePassword($password) {
        $query = "UPDATE users SET `password` = :password WHERE `id` = :id";
        $params = [
            ':id' => $this->id,
            ':password' => $password
        ];
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($params);
    }
}