<?php
// include database connection
include 'config/database.php';

try {
    $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

    $query = "DELETE FROM product WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(1, $id);

    if ($stmt->execute()) {
        // redirect to read records page and tell the user record was deleted
        header('Location: index.php?action=deleted');
    } else {
        die('Unable to delete record.');
    }
} catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
?>