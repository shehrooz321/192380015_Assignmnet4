<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <title>Assignment</title>
</head>
<body>
<?php
    require_once "database/connection.php";
    if(isset($_POST["submit"])){
        $errors=[];
        if(empty($_POST["movietitle"])){
            $errors[]="Movie Title must not empty";
        }
        else{
            $movietitle=trim($_POST["movietitle"]);
        }
        if(empty($_POST["movierating"])){
            $errors[]="Movie Rating must not empty.";
        }
        else{
            $movierating=trim($_POST["movierating"]);
        }
        if(empty($_POST["releasedate"])){
            $errors[]="Release date must not be empty.";
        }
        else{
            $releasedate=trim($_POST["releasedate"]);
        }
        if(isset($_FILES["upload"])){
            $target_directory = "images/";
            $file_tmp_name = $_FILES['upload']['tmp_name'];
            $file_name = $_FILES['upload']['name'];
            $file_size = $_FILES['upload']['size'];
            $file_type = $_FILES['upload']['type'];
            $target_file = $target_directory . $file_name;
            $allowed_types = ['image/jpeg', 'image/pjpeg', 'image/png', 'image/PNG', 'image/JPG','image/jpg'];
            $uploadError = 0;
            //Check if file type is allowed
            if(in_array($file_type, $allowed_types)){
                //Check Size
                if($file_size > 5000000){
                    exit("Too large file size. File size cannot exceed 5MB");
                }
                else{
                    //Check if file already exists
                    if(file_exists($target_file)){
                        $errors[] = "File Already Exists!";
                        $uploadError = 1;
                    }
                    //now move the file to the directory
                    move_uploaded_file($file_tmp_name,$target_file);
                    if($_FILES['upload']['error']>0){
                        $errors[] = "File cannot be uploaded due to error";
                        $uploadError = 1;
                    }
                }
            }
            else{
                exit("<div class = 'alert alert-danger'> Invalid File Type </div>");
            }
        }
        else{
            $error[] = "Please Select an image file";
        }

        if(empty($errors)){
           //insert record in the database
           $dbc= db_connect();
           $sql= "INSERT INTO movies VALUES(NULL,'$movietitle', '$movierating', '$releasedate', '$target_file')";
            $result= mysqli_query($dbc,$sql);
            if($result){
                echo "<div class='alert alert-success'> Data Entered Successfully </div>";
            }
            else{
                echo "<div class='alert alert-danger'> Data can not be entered</div>"; 
            }
            db_close($dbc);
        }
    else{
        foreach($errors as $error){
        echo "<div class='alert alert-danger'> {$error} </div>";
        }
    }
    }
    else{
        echo "<div class = 'alert alert-danger'>Form is not submitted!</div>";        
}
?>
</body>
</html>
