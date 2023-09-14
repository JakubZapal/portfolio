<?php
$allowed_file_types = array('.doc','.docx','.zip','.7z','.pdf','.ppt','.pptx','.jpg','.png','.jpeg','.odt','.odp');	
if (isset($_POST['proj_submit']))
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
    ?>