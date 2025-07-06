<?php
// Connection to the MySQL database.
$db_host = 'localhost';
$db_user = 'root';  
$db_pass = '';    
$db_name = 'sweet_treats_db';

// Create a new MySQLi object to connect to the database.
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check if the connection was successful.
if ($conn->connect_error) {    
    die("Database Connection failed: " . $conn->connect_error);
}
?>
