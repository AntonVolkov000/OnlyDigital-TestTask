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

const WEEK = 60 * 60 * 24 * 7;

function check_captcha($token): bool
{
    global $config;
    $ch = curl_init();
    $args = http_build_query([
        "secret" => $config['SMARTCAPTCHA_SERVER_KEY'],
        "token" => $token,
        "ip" => $_SERVER['REMOTE_ADDR'],
    ]);
    curl_setopt($ch, CURLOPT_URL, "https://captcha-api.yandex.ru/validate?$args");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);

    $server_output = curl_exec($ch);
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpcode !== 200) {
        echo "Allow access due to an error: code=$httpcode; message=$server_output\n";
        return true;
    }
    $resp = json_decode($server_output);
    return $resp->status === "ok";
}

if (!$_POST['check-spam'] && $_POST['loginOrEmail'] != '') {
    $user = null;
    if (strpos($_POST['loginOrEmail'], '@')) {
        $email = addslashes(htmlspecialchars($_POST['loginOrEmail']));
        $userPDO = $dbController->getUserPDOByEmail($email);
        if ($userPDO->rowCount() == 0) {
            $loginOrEmailError = 'Аккаунта с таким email не существует';
        } else {
            $user = $userPDO->fetch();
        }
    } else {
        $userPDO = $dbController->getUserPDOByLogin($_POST['loginOrEmail']);
        if ($userPDO->rowCount() == 0) {
            $loginOrEmailError = 'Аккаунта с таким логином не существует';
        } else {
            $user = $userPDO->fetch();
        }
    }
    if (check_captcha($_POST['smart-token'])) {
        if ($user != null) {
            if (password_verify($_POST['password'], $user['password'])) {
                try {
                    $authorizationToken = bin2hex(random_bytes(40));
                    setcookie('AuthorizationToken', $user['id'].'_'.$authorizationToken, time() + WEEK, '/');
                    $dbController->addAuthorizationToken($user['id'], $authorizationToken);
                header('Location: http://'.$_SERVER['HTTP_HOST'].'/frontend/controllers/ProfileController.php');
                } catch (\Exception $e) {
                    print "Error!: " . $e->getMessage();
                    die();
                }
            } else {
                $passwordError = 'Неверный пароль';
            }
        }
    } else {
        $captchaError = 'Captcha не решена';
    }
}

require_once '../views/login.php';
