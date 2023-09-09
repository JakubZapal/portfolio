<?php
session_start();
$c = mysqli_connect("localhost", "zapal_portfolio", "FbWAxiw148MBOxG9b", "zapal_portfolio");
$q = mysqli_query($c, "SELECT * FROM `admin` WHERE `login` = '$_POST[login]' AND `password` = '$_POST[password]';");
if(!isset($_SESSION['login']) || mysqli_num_rows($q) == 0)
    header('location: admin_login.php');

if(isset($_POST['content'])) {
    mysqli_query($c, "UPDATE `about` SET `content` = '$_POST[content]';");
    mysqli_query($c, "INSERT INTO `projects` SET content = '$_POST[proj_content]', image_source = '$_POST[url]';");
    mysqli_query($c, "UPDATE contact SET email = '$_POST[email]';");
    mysqli_query($c, "INSERT INTO abilities SET `name` = $_POST[ability], SET percent = $_POST[percent];");
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel admina</title>
</head>
<body>
    <div>
        <h3>Panel admina <a href="logout.php">(wyloguj)</a></h3>
    </div>
    <?php
    $q = mysqli_query($c, "SELECT content FROM about;");
    $row = mysqli_fetch_row($q);
    ?>
    <section id="O mnie">
        <h2>
            O mnie
        </h2>
        <form method="post" action="?" >
            <label for="content">Treść</label>
            <input type="text" name="content" value=" <?= $row[0] ?>">
            <input type="submit" id="submit">
        </form>
    </section>
    <?php
    ?>
    <section id="Projekty">
        <h2>Projekty</h2>
        <form action="?" method="post">
            <label for="proj_content">Treść projektu</label>
            <input type="text" name="proj_content">
            <label for="url">URL zdjęcia</label>
            <input type="text" name="url">
            <input type="submit" >
        </form>
    </section>
    <?php
    $q = mysqli_query($c, "SELECT `email` FROM `contact`");
    $row = mysqli_fetch_row($q);
    ?>
    <section id="Kontakt">
        <h2>Kontakt</h2>
        <form action="?" method="post">
            <label for="email">E-mail kontaktowy</label>
            <input type="email" name="email" value="<?=$row[0]?>">
            <input type="submit" >
        </form>
    </section>
    <section id="Moje umiejetnosci">
        <h2>Moje umiejętności</h2>
        <form action="?" method="post">
            <label for="ability">Umiejętność</label>
            <input type="text" name="ability">
            <input type="number" name="percent" min="0" max="100">
            <input type="submit" >
        </form>
    </section>
</body>
</html>
