<?php

namespace backend;

use PDO;
use PDOException;

class CheckAuthorization
{
    public function checkAuthorization($config): bool
    {
        try {
            $pdo = new PDO($config['dsn'], $config['username'], $config['password']);
            $idAndAuthorizationToken = $_COOKIE['AuthorizationToken'];
            if ($idAndAuthorizationToken != '') {
                $data = explode('_', $idAndAuthorizationToken);
                $id = $data[0];
                $authorizationTokenCookie = $data[1];
                $stmt = $pdo->prepare("SELECT authorization_token FROM users WHERE `id` = ?");
                $stmt->execute([$id]);
                if ($stmt->rowCount() > 0 && $authorizationTokenCookie != '') {
                    $authorizationToken = $stmt->fetch()['authorization_token'];
                    if ($authorizationToken == $authorizationTokenCookie) {
                        return true;
                    }
                }
            }
            return false;
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            die();
        }
    }
}
