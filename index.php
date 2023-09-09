<?php
$c = mysqli_connect("localhost", "zapal_portfolio", "FbWAxiw148MBOxG9b", "zapal_portfolio");

if(isset($_POST['submit'])) {
    $q = mysqli_query($c, "SELECT email FROM contact;");
    $row = mysqli_fetch_row($q);
    mail("$row[0]", "Wiadomość od " . $_POST['email'], $_POST['content']);
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Porflolio</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="shortcut icon" href="#" />
</head>
<body>
    <div class="container">
        <section id="O mnie">
            <h2>O mnie</h2>
            <p>
                <?php
                if ($q = mysqli_query($c, "SELECT content FROM about;")) {
                $row = mysqli_fetch_row($q);
                echo $row[0];
                }
                ?>
            </p>        
        </section>
        <section id="Projekty">
            <h2>Projekty</h2>
                <ul>
                    <?php 
                    if ($q = mysqli_query($c, "SELECT * FROM projects;"))
                        while ($row = mysqli_fetch_row($q)) {
                    ?>
                    <li>
                        <?php echo $row[1];
                        if (!is_null($row[2]))
                            echo "<img src=\"<?= $row[2] ?>\">";
                        ?>
                    </li>
                    <?php
                        } ?>
                </ul>
        </section>
        <section id="Kontakt">
            <h2>Kontakt</h2>
            <form action="?" method="post">
                <p>
                    <input type="email" id="email" placeholder="Twój e-mail">
                </p>
                <p>
                    <input type="text" placeholder="Treść" id="content">
                </p>
                <p>
                    <input type="submit" id="submit">
                </p>
            </form>
        </section>
        <section id="Moje umiejętności">
            <h2>Moje umiejętności</h2>
                <ul>
                    <?php
                    $q = mysqli_query($c, "SELECT * FROM abilities;");
                    while($row = mysqli_fetch_row($q))
                    echo "
                    <li>
                        <p>
                            <label for=\"$row[1]\">$row[1]</label>
                            <progress id=\"$row[1]\" value=\"$row[2]\" max=\"100\"> $row[2]% </progress>
                        </p>
                    </li>"
                    ?>
                </ul>
        </section>
        <a href="admin_panel.php" class="btn btn-primary admin">admin</a>
    </div>
</body>
</html>