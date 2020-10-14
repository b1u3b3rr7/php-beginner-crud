<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read One Record - PHP CRUD Tutorial</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
</head>

<body>
    <div class="container">
        <div class="page-header">
            <h1>Read Product</h1>
        </div>
        <?php
        // get passed paramter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        // include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT `id`, `name`, `description`, `price` FROM `product` WHERE `id` = ? LIMIT 0, 1";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(1, $id);

            // execute query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $description = $row['description'];
            $price = $row['price'];
        } catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Name</td>
                <td><?php echo htmlspecialchars($name, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Description</td>
                <td><?php echo htmlspecialchars($description, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td>Price</td>
                <td><?php echo htmlspecialchars($price, ENT_QUOTES); ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <a href='index.php' class='btn btn-danger'>Back to read products</a>
                </td>
            </tr>
        </table>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>

</html>