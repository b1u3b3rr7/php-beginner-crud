<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Create Product</h1>
            <?php
            if ($_POST) {
                // include database connection
                include './config/database.php';
                try {
                    // insert query
                    $query = "INSERT INTO `product` SET `name`=:name, `description`=:description, `price`=:price, `image`=:image, `created`=:created";

                    // prepare query for execution
                    $stmt = $conn->prepare($query);

                    // posted values
                    $name = htmlspecialchars(strip_tags($_POST['name']));
                    $description = htmlspecialchars(strip_tags($_POST['description']));
                    $price = htmlspecialchars(strip_tags($_POST['price']));

                    $image = !empty($_FILES["image"]["name"])
                        ? sha1_file($_FILES["image"]["tmp_name"]) . "-" . basename($_FILES["image"]["name"])
                        : "";
                    $image = htmlspecialchars(strip_tags($image));

                    // bind the parameters
                    $stmt->bindParam(':name', $name);
                    $stmt->bindParam(':description', $description);
                    $stmt->bindParam(':price', $price);
                    $stmt->bindParam(':image', $image);

                    // specify when this record was inserted to the database
                    $created = date('Y-m-d H-i-s');
                    $stmt->bindParam(':created', $created);

                    // execute query
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Unable to save record.</div>";
                    }

                    if ($image) {
                        // sha1_file() function is used to make a unique file name
                        $target_directory = "uploads/";
                        $target_file = $target_directory . $image;
                        $file_type = pathinfo($target_file, PATHINFO_EXTENSION);
                        // error message
                        $file_upload_error_messages = "";
                        // verify file is valid or not
                        $check = getimagesize($_FILES["image"]["tmp_name"]);
                        if ($check !== false) {
                        } else {
                            $file_upload_error_messages = "<div>Submitted file is not an image.</div>";
                        }
                        // file extension validation
                        $allowed_file_types = array('jpg', 'jpeg', 'png', 'gif');
                        if (!in_array($file_type, $allowed_file_types)) {
                            $file_upload_error_messages = "<div>Only JPG, JPEG, PNG, GIF files are allowed.</div>";
                        }
                        // make sure file does not exist
                        if (file_exists($target_file)) {
                            $file_upload_error_messages = "<div>Image already exists. Try to change file name.</div>";
                        }
                        // make sure file size isn't too large
                        if ($_FILES['image']['size'] > (1024000)) {
                            $file_upload_error_messages = "<div>Image must be less than 1MB in size.</div>";
                        }
                        // make sure the 'uploads' folder exists
                        if (!is_dir($target_directory)) {
                            mkdir($target_directory, 0777, true);
                        }
                        // uploade file
                        if (empty($file_upload_error_messages)) {
                            if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
                            } else {
                                echo "<div class='alert alert-danger'>";
                                echo "<div>Unable to upload photo.</div>";
                                echo "<div>Update the record to upload photo.</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<div class='alert alert-danger'>";
                            echo "<div>{$file_upload_error_messages}</div>";
                            echo "<div>Update the record to upload photo.</div>";
                            echo "</div>";
                        }
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            }
            ?>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                <table class='table table-hover table-responsive table-bordered'>
                    <tr>
                        <td>Name</td>
                        <td><input type='text' name='name' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Description</td>
                        <td><textarea name='description' class='form-control'></textarea></td>
                    </tr>
                    <tr>
                        <td>Price</td>
                        <td><input type='text' name='price' class='form-control' /></td>
                    </tr>
                    <tr>
                        <td>Photo</td>
                        <td><input type="file" name="image" /></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <input type='submit' value='Save' class='btn btn-primary' />
                            <a href='index.php' class='btn btn-danger'>Back to read products</a>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>