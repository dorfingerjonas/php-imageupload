<?php
if (!isset($_POST["submit"]) || !isset($_FILES["fileToUpload"])) { exit; }

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $upload0k = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
        echo "Sorry file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
        echo "sorry, your fle is to large";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif") {
        echo "Sorry, only JPG, JPEG, PNG, & GIF files are allowed";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "sorry, your file was not uploaded.";
        // if erverything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file" . basename($_FILES["fileToUpload"]["name"]) . "has been uploaded.";

            $_db_host = "localhost";
            $_db_datenbank = "web";
            $_db_username = "web";
            $_db_passwort = "web";
            $conn = new mysqli($_db_host, $_db_username, $_db_passwort, $_db_datenbank);

            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            $insertStatement = "INSERT INTO images(id,path) VALUES ('','$target_file');";
            if ($_res = $conn->query($insertStatement)) {
                echo "<br>Image $target_file has been added to the database.";

            } else {
                echo "<br> NO insertion into database";
            }
            #close databse
            $conn->close();
        }
    }
}
?>