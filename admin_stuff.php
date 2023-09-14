<?php
session_start();
$c = mysqli_connect("localhost", "zapal_portfolio", "FbWAxiw148MBOxG9b", "zapal_portfolio");
$q = mysqli_query($c, "SELECT * FROM `admin` WHERE `login` = '$_SESSION[login]' AND `password` = '$_SESSION[password]';");
if(!isset($_SESSION['login']) || mysqli_num_rows($q) == 0)
    header('location: admin_login.php');
if(isset($_POST['content']))
    mysqli_query($c, "UPDATE `about` SET `content` = '$_POST[content]';");
if(isset($_POST['proj_submit'])) {
    $allowed_file_types = array('.doc','.docx','.zip','.7z','.pdf','.ppt','.pptx','.jpg','.png','.jpeg','.odt','.odp');	
    if (!empty($_FILES["zdj"]["name"]))
    {
        echo "elo";
        $filename = $_FILES["zdj"]["name"];
        $file_basename = substr($filename, 0, strripos($filename, '.')); // get file extention
        $file_ext = substr($filename, strripos($filename, '.')); // get file name
        $filesize = $_FILES["zdj"]["size"];

        $filename = iconv('UTF-8', 'ASCII//TRANSLIT', $filename);
        // konwersja znakow utf do znakow podstawowych
        
        if (in_array($file_ext,$allowed_file_types) && ($filesize < 20*1024*1024))
        {	
            // Rename file
            $data = date("Ymd_His");
            $rand = rand(100000,999999);
            $filename = str_replace(' ', '-', $filename);
            $newfilename = "$data" . "_" . "$rand" . "$file_ext";
            if (file_exists("/img/" . $newfilename))
            {
                // file already exists error
                echo "Plik z taka sama nazwa juz zostal dodany wczesniej.";
            }
            else
            {
                move_uploaded_file($_FILES["zdj"]["tmp_name"], __DIR__ . "/img/" . $newfilename);
                $img_url = "http://zapal.promienna.eu/portfolio/img/$newfilename";
            }
        }
        else 
            if ($filesize >20*1024*1024)
            {	
                // file size error
                echo "<div class='alert alert-danger' role='danger'>twoj plik nie zostal przeslany.<br>Niestety, mozesz przeslac plik o maksymalnym rozmiarze 10 MB!</div>";
            }
            else
            {
                // file type error
                echo "<div class='alert alert-danger' role='danger'>twoj plik nie zostal przeslany.<br>Akceptowane rozszerzenia: " . implode(', ',$allowed_file_types) . "</div>";
                unlink($_FILES["zdj"]["tmp_name"]);
            }
        }
        mysqli_query($c, "INSERT INTO `projects` SET `content` = '$_POST[proj_content]', `image_source` = '$img_url';");
    }
if(isset($_POST['email']))
    mysqli_query($c, "UPDATE `contact` SET `email` = '$_POST[email]';");
if(isset($_POST['ability_edit']))
    mysqli_query($c, "UPDATE `abilities` SET `name` = '$_POST[ability_edit]', `percent` = '$_POST[percent_edit]' WHERE `id` = '$_POST[ab_id]';");
if(isset($_POST['ability']))
    mysqli_query($c, "INSERT INTO `abilities` SET `name` = '$_POST[ability]', `percent` = '$_POST[percent]';");
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel admina</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link rel="icon" type="image/x-icon" href="favicon.png">
</head>
<body>
    <div class="container">
        <div>
            <h3>Panel admina <a href="../portfolio">(cofnij)</a></h3>
        </div>
        <?php
        $q = mysqli_query($c, "SELECT content FROM about;");
        $row = mysqli_fetch_row($q);
        ?>
        <section id="O mnie">
            <h2>
                O mnie
            </h2>
            <p>
                Aktualne:
                <br>
                <?= $row[0] ?>
            </p>
            <form method="post" action="?" >
                <label for="content">Treść</label>
                <br>
                <textarea name="content" id="content"></textarea>
                <input type="submit" id="submit">
            </form>
        </section>
        <?php
        ?>
        <section id="projekty">
            <div>
                <h2>Dodaj projekt</h2>
                <form action="?" method="post">
                    <label for="proj_content" enctype="multipart/form-data">Treść projektu</label>
                    <input type="text" name="proj_content" id="proj_content">
                    <input type="file" name="zdj" id="zdj">
                    <input type="submit" name="proj_submit">
                </form>
            </div>
            <div>
                <h2>Edytuj projekty</h2>
                <?php
                $q = mysqli_query($c, "SELECT * FROM projects;");
                while($row = mysqli_fetch_row($q)) {
                ?>
                <div>
                    <form action="?" method="post">
                        <input type="hidden" name="proj_id" value="<?= $row[0] ?>">
                        <label for="proj_content">Treść projektu</label>
                        <input type="text" name="proj_content_ed" value="<?= $row[1] ?>">
                        <label for="url">URL zdjęcia</label>
                        <input type="text" name="url_ed" id="url_ed" value="<?= $row[2] ?>">
                        <input type="submit">
                    </form>
                </div>
                <?php 
                }
                ?>
            </div>
        </section>
        <?php
        $q = mysqli_query($c, "SELECT `email` FROM `contact`");
        $row = mysqli_fetch_row($q);
        ?>
        <section id="Kontakt">
            <h2>Kontakt</h2>
            <form action="?" method="post">
                <label for="email">E-mail kontaktowy</label>
                <input type="email" name="email" id="email" value="<?=$row[0]?>">
                <input type="submit">
            </form>
        </section>
        <section id="Umiejetnosci">
            <h2>Umiejętności</h2>
            <div>
                <h3>Edytuj</h3>
                <?php
                $q = mysqli_query($c, "SELECT * FROM `abilities`;");
                while($row = mysqli_fetch_row($q)) {
                ?>
                <div>
                    <form action="?" method="post">
                        <input type="hidden" name="ab_id" id="ab_id" value="<?= $row[0] ?>">
                        <label for="ability">Umiejętność</label>
                        <input type="text" name="ability_edit" id="ability" value="<?= $row[1] ?>">
                        <input type="number" name="percent_edit" min="0" max="100" placeholder="procent" value="<?= $row[2] ?>">
                        <input type="submit">
                    </form>
                </div>
                <?php
                }
                ?>
            </div>
            <div>
                <h3>Dodaj</h3>
                <form action="?" method="post">
                    <label for="ability">Umiejętność</label>
                    <input type="text" name="ability" id="ability">
                    <input type="number" name="percent" min="0" max="100" placeholder="procent">
                    <input type="submit">
                </form>
            </div>
        </section>
    </div>
</body>
</html>