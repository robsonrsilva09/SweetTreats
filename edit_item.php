<?php
// Allows an admin to edit an existing product's details.
include 'connection.php';
include 'header.php';

if (!isset($_SESSION['admin_id'])) { header("Location: admin_login.php"); exit(); }
if (!isset($_GET['id'])) { header("Location: manage_products.php"); exit(); }

$item_id = $_GET['id'];
$message = '';

// Handle form submission to update the product.
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $current_image = $_POST['current_image'];
    $image_path = $current_image;

    // Logic for uploading a new image.
    if (isset($_FILES['item_image']) && $_FILES['item_image']['error'] == 0) {
        $target_dir = "uploads/";
        $image_name = time() . '_' . basename($_FILES["item_image"]["name"]);
        $target_file = $target_dir . $image_name;
        
        if (move_uploaded_file($_FILES["item_image"]["tmp_name"], $target_file)) {
            
            if(!empty($current_image) && file_exists($target_dir . $current_image)) {
                unlink($target_dir . $current_image);
            }
            $image_path = $image_name;
        } else {
            $message = "<p class='error'>Sorry, there was an error uploading your new file.</p>";
        }
    }

    if (empty($message)) {
        $sql = "UPDATE menu_items SET item_name = ?, description = ?, price = ?, image_path = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsi", $item_name, $description, $price, $image_path, $item_id);

        if ($stmt->execute()) {
            header("Location: manage_products.php?status=updated");
            exit();
        } else {
            $message = "<p class='error'>Error updating record: " . $stmt->error . "</p>";
        }
    }
}

// Fetch the current product data to pre-populate the form fields.
$sql_select = "SELECT * FROM menu_items WHERE id = ?";
$stmt_select = $conn->prepare($sql_select);
$stmt_select->bind_param("i", $item_id);
$stmt_select->execute();
$result = $stmt_select->get_result();
$item = $result->fetch_assoc();
?>

<h2>Edit Product</h2>
<?php echo $message; ?>
<form action="edit_item.php?id=<?php echo $item_id; ?>" method="POST" enctype="multipart/form-data">
    <label for="item_name">Product Name:</label>
    <input type="text" id="item_name" name="item_name" value="<?php echo htmlspecialchars($item['item_name']); ?>" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required><?php echo htmlspecialchars($item['description']); ?></textarea>

    <label for="price">Price (&pound;):</label>
    <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($item['price']); ?>" required>

    <label>Current Image:</label>
    <?php if (!empty($item['image_path'])): ?>
        <img src="uploads/<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['item_name']); ?>" width="100">
    <?php else: ?>
        <p>No image currently set.</p>
    <?php endif; ?>
    
    <label for="item_image" style="margin-top:10px;">Upload New Image (optional):</label>
    <input type="file" id="item_image" name="item_image">
    
    <input type="hidden" name="current_image" value="<?php echo htmlspecialchars($item['image_path']); ?>">

    <input type="submit" value="Update Product">
</form>
<a href="manage_products.php" class="btn btn-secondary">Back to Product Management</a>

<?php
$conn->close();
include 'footer.php';
?>
