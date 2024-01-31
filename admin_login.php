<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

  
    if ($username === 'admin' && $password === '123') {
        $_SESSION['admin_logged_in'] = true;
        header('Location: admin.php');
        exit;
    } else {
        echo 'Неверное имя пользователя или пароль';
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Вход для администратора</title>
</head>
<body>
    <h1>Вход для администратора</h1>
    <form method="post">
        <label for="username">Имя пользователя:</label>
        <input type="text" name="username" required><br>

        <label for="password">Пароль:</label>
        <input type="password" name="password" required><br>

        <input type="submit" value="Войти">
    </form>
</body>
</html>
