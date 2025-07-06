<?php
include_once 'functions.php';
include 'connection.php';

require_login();

if (isset($_GET['id'])) {
    $item_id = $_GET['id'];

    // Get the image path from the database so we can delete the file
    $sql_select = "SELECT image_path FROM menu_items WHERE id = ?";
    $stmt_select = $conn->prepare($sql_select);
    $stmt_select->bind_param("i", $item_id);
    $stmt_select->execute();
    $result = $stmt_select->get_result();
    $item = $result->fetch_assoc();

    if ($item) {
        $image_to_delete = "uploads/" . $item['image_path'];
    }
    $stmt_select->close();

    // Delete the record from the database
    $sql_delete = "DELETE FROM menu_items WHERE id = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $item_id);

    if ($stmt_delete->execute()) {
        // If the database record was deleted, also delete the image file from the server
        if (!empty($item['image_path']) && file_exists($image_to_delete)) {
            unlink($image_to_delete);
        }
        set_flash_message('success', 'Product has been deleted successfully.');
    } else {
        set_flash_message('error', 'Error deleting product.');
    }
    $stmt_delete->close();
}
$conn->close();
header("Location: manage_products.php");
exit();
