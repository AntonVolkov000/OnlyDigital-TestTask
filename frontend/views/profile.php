<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Профиль</title>
    <link rel="stylesheet" href="../views/css/main.css">
</head>
<body>
<main>
    <h2>Профиль</h2>
    <form class="register-form" method="POST">
        <?= 'Ваш логин: '.$user->login ?><br>
        <?= 'Ваш номер телефона: +7'.$user->phoneNumber ?><br>
        <?= 'Ваш email: '.$user->email ?>
        <h3>Изменить данные</h3>
        <?php if ($loginError) {echo $loginError;} ?>
        <label>
            <input type="text" name="login" placeholder="Введите логин" pattern="[\w\-]{2,64}" title="Allowed characters: A-z0-9_- MinSize: 2" maxlength="64" autocomplete="username" value="<?=$_POST['login']?>">
        </label>
        <?php if ($phoneNumberError) {echo $phoneNumberError;} ?>
        <label>
            <input type="tel" name="phone-number" placeholder="Введите номер телефона" pattern="\d{10}" title="Format: 9527405657" autocomplete="tel" value="<?=$_POST['phone-number']?>">
        </label>
        <?php if ($emailError) {echo $emailError;} ?>
        <label>
            <input type="email" name="email" placeholder="Введите email" autocomplete="email" value="<?=$_POST['email']?>">
        </label>
        <?php if ($passwordAgainError) {echo $passwordAgainError;} ?>
        <label>
            <input type="password" name="password" placeholder="Введите пароль" pattern="[\w!@#$%\-]{6,255}" title="Allowed characters: A-z0-9_-!@#$% MinSize: 6" maxlength="255" autocomplete="new-password" value="<?=$_POST['password']?>">
        </label>
        <label>
            <input type="password" name="password-again" placeholder="Введите пароль ещё раз" pattern="[\w!@#$%\-]{6,255}" title="Allowed characters: A-z0-9_-!@#$% MinSize: 6" maxlength="255" autocomplete="new-password" value="<?=$_POST['password-again']?>">
        </label>
        <label class="hidden-field">
            <input type="text" name="check-spam">
        </label>
        <input type="submit">
    </form>
    <div class="link-redirect">
        <a href="?out=true">Выйти</a>
    </div>
</main>
</body>
</html>