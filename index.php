<?php

require_once 'backend/CheckAuthorization.php';
use backend\CheckAuthorization;

$config = require_once 'config.php';

$checkAuthorization = new CheckAuthorization();
if ($checkAuthorization->checkAuthorization($config)) {
    $redirectPage = 'ProfileController.php';
} else {
    $redirectPage = 'LoginController.php';
}
header('Location: http://'.$_SERVER['HTTP_HOST'].'/frontend/controllers/'.$redirectPage);
