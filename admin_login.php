<?php
session_start();
$c = mysqli_connect("localhost", "zapal_portfolio", "FbWAxiw148MBOxG9b", "zapal_portfolio");

if(isset($_POST['login'])) {
    $q = mysqli_query($c, "SELECT * FROM `admin` WHERE `login` = '$_POST[login]' AND `password` = '$_POST[password]';");
    if (mysqli_num_rows($q)>0) {
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        Header ('Location: admin_stuff.php');
    }
    else
        echo 'złe dane';}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="?" method="post">
        <p>
            <input type="text" name="login">
        </p>
        <p>
            <input type="password" name="password">
        </p>
        <input type="submit" name="submit">
    </form>
</body>
</html>