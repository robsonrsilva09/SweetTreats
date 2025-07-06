<?php

include_once 'functions.php'; // This includes session_start() and other functions
include 'connection.php';
require_login(); // Ensures only logged-in admins can access this page


// This block must come before any HTML output to allow for header redirects.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = trim($_POST['item_name']);
    $description = trim($_POST['description']);
    $price = $_POST['price'];
    $image_path = '';

    // Basic server-side validation
    if (empty($item_name) || empty($description) || !is_numeric($price) || $price < 0) {
        set_flash_message('error', 'Please fill in all fields correctly.');
        header("Location: add_item.php");
        exit();
    }

    // Image Upload Logic
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $target_dir = "uploads/";
        // Create a unique filename to prevent overwriting existing files
        $image_name = time() . '_' . basename($_FILES["item_image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        // Move the uploaded file from the temporary directory to the 'uploads' folder
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            $image_path = $image_name; // If successful, save the filename to be stored in the database
        } else {
            set_flash_message('error', 'Sorry, there was an error uploading your file.');
            header("Location: add_item.php");
            exit();
        }
    }

    // Database
    $sql = "INSERT INTO menu_items (item_name, description, price, image_path, date_added) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $date_added = date('Y-m-d');    
    
    $stmt->bind_param("ssdss", $item_name, $description, $price, $image_path, $date_added);

    if ($stmt->execute()) {
        set_flash_message('success', 'New product has been added successfully!');
        header("Location: manage_products.php"); 
        exit();
    } else {
        set_flash_message('error', 'Error creating product: ' . $stmt->error);       
        header("Location: add_item.php");
        exit();
    }
    $stmt->close();
    $conn->close();
}

//HTML FORM DISPLAY
include 'header.php';
?>

<h2>Add New Product</h2>
<form action="add_item.php" method="POST" enctype="multipart/form-data">
    <label for="item_name">Product Name:</label>
    <input type="text" id="item_name" name="item_name" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" required></textarea>

    <label for="price">Price (&pound;):</label>
    <input type="number" step="0.01" id="price" name="price" required>
    
    <label for="item_image">Product Image (Square Recommended):</label>
    <input type="file" id="item_image" name="item_image" accept="image/png, image/jpeg, image/gif">

    <input type="submit" value="Add Product">
</form>
<a href="manage_products.php" class="btn btn-secondary">Back to Product Management</a>

<?php
include 'footer.php';
?>
