<?php

namespace frontend\controllers;

require_once '../../backend/CheckAuthorization.php';

use backend\CheckAuthorization;

$config = require_once '../../config.php';

$checkAuthorization = new CheckAuthorization();
if (!$checkAuthorization->checkAuthorization($config)) {
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/frontend/controllers/LoginController.php');
}

require_once '../../backend/models/User.php';

use backend\models\User;

$id = explode('_', $_COOKIE['AuthorizationToken'])[0];
$user = new User($config, $id);

if ($_GET['out'] == 'true') {
    setcookie('AuthorizationToken', '', time() - 3600, '/');
    $user->removeAuthorizationToken();
    header('Location: http://' . $_SERVER['HTTP_HOST'] . '/frontend/controllers/LoginController.php');
}

require_once '../../backend/DatabaseController.php';
use backend\DatabaseController;

$dbController = new DatabaseController($config);

if (!$_POST['check-spam']) {
    if ($_POST['login'] != '') {
        $userPDO = $dbController->getUserPDOByLogin($_POST['login']);
        if ($userPDO->rowCount() == 0) {
            $user->updateLogin($_POST['login']);
            $_POST['login'] = '';
        } else {
            $loginError = 'Аккаунт с таким логином уже существует';
        }
    }
    if ($_POST['phone-number'] != '') {
        $userPDO = $dbController->getUserPDOByPhoneNumber($_POST['phone-number']);
        if ($userPDO->rowCount() == 0) {
            $user->updatePhoneNumber($_POST['phone-number']);
            $_POST['phone-number'] = '';
        } else {
            $phoneNumberError = 'Аккаунт с таким номером телефона уже существует';
        }
    }
    if ($_POST['email'] != '') {
        $email = addslashes(htmlspecialchars($_POST['email']));
        $userPDO = $dbController->getUserPDOByEmail($email);
        if ($userPDO->rowCount() == 0) {
            $user->updateEmail($email);
            $_POST['email'] = '';
        } else {
            $emailError = 'Аккаунт с таким email уже существует';
        }
    }
    if ($_POST['password'] != '') {
        if ($_POST['password'] == $_POST['password-again']) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $user->updatePassword($password);
            $_POST['password'] = '';
            $_POST['password-again'] = '';
        } else {
            $passwordAgainError = 'Пароли не совпадают';
        }
    }
}

require_once '../views/profile.php';
