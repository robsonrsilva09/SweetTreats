<?php
// Deletion of an admin account.
include_once 'functions.php';
include 'connection.php';
require_superadmin();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id_to_delete'])) {
    
    $user_id_to_delete = $_POST['user_id_to_delete'];
    
    // Prevent a user from deleting their own account.
    if ($user_id_to_delete == $_SESSION['admin_id']) {
        set_flash_message('error', 'You cannot delete your own account.');
        header("Location: manage_admins.php");
        exit();
    }

    $sql = "DELETE FROM admin_users WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id_to_delete);

    if ($stmt->execute()) {
        set_flash_message('success', 'Admin account has been deleted.');
    } else {
        set_flash_message('error', 'Error deleting account: ' . $conn->error);
    }
    
    $stmt->close();
}
$conn->close();
header("Location: manage_admins.php");
exit();
