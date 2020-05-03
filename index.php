<!DOCTYPE html>
<html lang="en">
<body>
<h1>Dateiupload:</h1><br/>
<form action="index.php" method="post" enctype="multipart/form-data">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
</body>
</html>

<?php
if (!isset($_POST["submit"]) || !isset($_FILES["fileToUpload"])) {
    exit;
}

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;

$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

if (isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if ($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

if ($_FILES["fileToUpload"]["size"] > 500000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}

if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif") {

    echo "Sorry, only JPG, JPEG, ONG & GIF files are allowed.";
    $uploadOk = 0;
}

if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
} else {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";

        $_db_host = "mysql";
        $_db_datenbank = "IMAGE_UPLOAD";
        $_db_username = "root";
        $_db_password = "root";
        $conn = new mysqli($_db_host, $_db_username, $_db_password, $_db_datenbank);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        $insertStatement = "INSERT INTO IMAGE_UPLOAD.images (id, path) VALUES ('', '$target_file');";
        if ($_res = $conn->query($insertStatement)) {
            echo "<br>Image $target_file has been added to the database.";
        } else {
            echo "<br>NO insertion into database";
        }
        $conn->close();
    }
}
?>