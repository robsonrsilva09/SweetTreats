<?php
// Approval of a pending admin account.
include_once 'functions.php';
include 'connection.php';
require_superadmin(); // Only a super-admin can access this.

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id_to_approve'])) {
    $user_id = $_POST['user_id_to_approve'];

    $sql = "UPDATE admin_users SET is_approved = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        set_flash_message('success', 'Admin account has been approved.');
    } else {
        set_flash_message('error', 'Error approving account: ' . $conn->error);
    }
    
    $stmt->close();
}
$conn->close();
header("Location: manage_admins.php");
exit();