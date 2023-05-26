<?php

namespace frontend\controllers;

require_once '../../backend/CheckAuthorization.php';
use backend\CheckAuthorization;

$config = require_once '../../config.php';

$checkAuthorization = new CheckAuthorization();
if ($checkAuthorization->checkAuthorization($config)) {
    header('Location: http://'.$_SERVER['HTTP_HOST'].'/frontend/controllers/ProfileController.php');
}

require_once '../../backend/DatabaseController.php';
use backend\DatabaseController;

$dbController = new DatabaseController($config);

function printT($text): void
{
    echo '<pre>';
    print_r($text);
    echo '</pre>';
}

if (!$_POST['check-spam'] && $_POST['login'] != '') {
    $checkSuccess = true;
    $userPDO = $dbController->getUserPDOByLogin($_POST['login']);
    if ($userPDO->rowCount() > 0) {
        $checkSuccess = false;
        $loginError = 'Аккаунт с таким логином уже существует';
    }
    $userPDO = $dbController->getUserPDOByPhoneNumber($_POST['phone-number']);
    if ($userPDO->rowCount() > 0) {
        $checkSuccess = false;
        $phoneNumberError = 'Аккаунт с таким номером телефона уже существует';
    }
    $email = addslashes(htmlspecialchars($_POST['email']));
    $userPDO = $dbController->getUserPDOByEmail($email);
    if ($userPDO->rowCount() > 0) {
        $checkSuccess = false;
        $emailError = 'Аккаунт с таким email уже существует';
    }
    if ($_POST['password'] != $_POST['password-again']) {
        $checkSuccess = false;
        $passwordAgainError = 'Пароли не совпадают';
    }
    if ($checkSuccess) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $dbController->createNewUser($_POST['login'], $_POST['phone-number'], $email, $password);
        header('Location: http://'.$_SERVER['HTTP_HOST'].'/frontend/controllers/LoginController.php');
    }
}

require_once '../views/register.php';
