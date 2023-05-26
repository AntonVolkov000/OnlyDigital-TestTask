<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <link rel="stylesheet" href="../views/css/main.css">
    <script src="https://captcha-api.yandex.ru/captcha.js" defer></script>
</head>
<body>
<main>
    <h2>Авторизация</h2>
    <form class="register-form" method="POST">
        <?php if ($loginOrEmailError) {echo $loginOrEmailError;} ?>
        <label>
            <input type="text" name="loginOrEmail" placeholder="Введите логин или email" required autocomplete="username" value="<?=$_POST['loginOrEmail']?>">
        </label>
        <?php if ($passwordError) {echo $passwordError;} ?>
        <label>
            <input type="password" name="password" placeholder="Введите пароль" pattern="[\w!@#$%\-]{6,255}" title="Allowed characters: A-z0-9_-!@#$% MinSize: 6" maxlength="255" required autocomplete="current-password" value="<?=$_POST['password']?>">
        </label>
        <label class="hidden-field">
            <input type="text" name="check-spam">
        </label>
        <?php if ($captchaError) {echo $captchaError;} ?>
        <div
            style="height: 100px"
            id="captcha-container"
            class="smart-captcha"
            data-sitekey="ysc1_2GaQryO9Go4XZhjsBMlbMUTIda4beTXRrbK1uiGX93def22b"
        ></div>
        <input type="submit">
    </form>
    <div class="link-redirect">
        <a href="http://127.0.0.1:8000/frontend/controllers/RegisterController.php">Регистрация</a>
    </div>
</main>
</body>
</html>