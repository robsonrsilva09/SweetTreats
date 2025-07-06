<?php
session_start();
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password_attempt = $_POST['password'];

    // Select the user's details, including their role and approval status.
    $sql = "SELECT id, username, password, role, is_approved FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // First, verify the submitted password against the hash in the database.
        if (password_verify($password_attempt, $user['password'])) {            
            if ($user['is_approved'] == 1) {                
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_role'] = $user['role'];
                header("Location: admin_dashboard.php");
                exit();
            } else {                
                header("Location: admin_login.php?error=Your account is awaiting approval.");
                exit();
            }
        }
    }
       
    header("Location: admin_login.php?error=Invalid username or password.");
    exit();
}
?>
