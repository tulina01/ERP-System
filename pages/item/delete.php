<?php
include_once '../../config/database.php';

$database = new Database();
$db = $database->getConnection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "DELETE FROM item WHERE id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        header("Location: list.php?msg=deleted");
    } else {
        header("Location: list.php?msg=error");
    }
} else {
    header("Location: list.php");
}
exit();
?>