<?php

include 'connection.php';
include 'header.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $message = "<p class='error'>Username and password cannot be empty.</p>";
    } else {
        $check_sql = "SELECT id FROM admin_users LIMIT 1";
        $result = $conn->query($check_sql);
        $is_first_user = ($result->num_rows == 0);
        
        $role = $is_first_user ? 'superadmin' : 'admin';
        $is_approved_status = $is_first_user ? 1 : 0;

        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $insert_sql = "INSERT INTO admin_users (username, password, role, is_approved) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insert_sql);
        
        $stmt->bind_param("sssi", $username, $password_hash, $role, $is_approved_status);

        if ($stmt->execute()) {
            if ($is_first_user) {
                $message = "<p class='success'>Super-admin account created and automatically approved! You can now log in.</p>";
            } else {
                $message = "<p class='success'>Registration successful! Your account is now pending approval.</p>";
            }
        } else {
            $message = "<p class='error'>Error: This username might already exist.</p>";
        }
        $stmt->close();
    }
}
$conn->close();
?>

<h2>Create New Admin User</h2>
<?php echo $message; ?>

<form action="register_admin.php" method="POST">
    <label for="username">Admin Username:</label>
    <input type="text" id="username" name="username" required>

    <label for="password">Admin Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Register Admin Account">
</form>

<?php include 'footer.php'; ?>
