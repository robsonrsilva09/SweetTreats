<?php

include_once 'functions.php';
include 'connection.php';
require_login();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_order'])) {
    if (isset($_POST['order']) && is_array($_POST['order'])) {
        $update_sql = "UPDATE menu_items SET display_order = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);

        foreach ($_POST['order'] as $item_id => $display_order) {
          
            $stmt->bind_param("ii", $display_order, $item_id);
            $stmt->execute();
        }
        $stmt->close();
        set_flash_message('success', 'Display order has been saved successfully!');
       
        header("Location: manage_products.php");
        exit();
    }
}

include 'header.php';


$allowed_columns = ['display_order', 'item_name', 'price', 'date_added'];
$sort_column = isset($_GET['sort']) && in_array($_GET['sort'], $allowed_columns) ? $_GET['sort'] : 'display_order';
$sort_order = isset($_GET['order']) && in_array(strtoupper($_GET['order']), ['ASC', 'DESC']) ? strtoupper($_GET['order']) : 'ASC';

$sql = "SELECT id, item_name, price, date_added, display_order FROM menu_items ORDER BY $sort_column $sort_order";
$result = $conn->query($sql);

function create_sort_link($column, $display_name, $current_col, $current_order) {
    $next_order = ($column == $current_col && $current_order == 'ASC') ? 'DESC' : 'ASC';
    $class = ($column == $current_col) ? 'sort-' . strtolower($current_order) : 'sort-none';
    return "<a class=\"$class\" href=\"?sort=$column&order=$next_order\">$display_name</a>";
}
?>

<h2>Manage Products & Display Order</h2>
<p>Click on the table headers to sort this admin view.</p>
<p>Use the input boxes and click "Save Display Order" (bottom of the page) to change the public order on the homepage.</p>
<a href="add_item.php" class="btn">Add New Product</a>

<form action="manage_products.php" method="POST">
    <table>
        <thead>
            <tr>
                <th style="width: 80px;"><?php echo create_sort_link('display_order', 'Order', $sort_column, $sort_order); ?></th>
                <th><?php echo create_sort_link('item_name', 'Product Name', $sort_column, $sort_order); ?></th>
                <th><?php echo create_sort_link('price', 'Price', $sort_column, $sort_order); ?></th>
                <th><?php echo create_sort_link('date_added', 'Date Added', $sort_column, $sort_order); ?></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result && $result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td><input type='number' name='order[" . $row['id'] . "]' value='" . htmlspecialchars($row['display_order']) . "' style='width: 60px; text-align: center;'></td>";
                    echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                    echo "<td>&pound;" . htmlspecialchars($row['price']) . "</td>";
                    echo "<td>" . date("d/m/Y", strtotime($row['date_added'])) . "</td>";
                    echo "<td class='actions'>";
                    echo "<a href='edit_item.php?id=" . $row['id'] . "'>Edit</a>";
                    echo "<a href='delete_item.php?id=" . $row['id'] . "' onclick='return confirm(\"Are you sure you want to permanently delete this product?\");'>Delete</a>";
                    echo "</td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='5'>No products found.</td></tr>";
            }
            ?>
        </tbody>
    </table>
    <input type="submit" name="save_order" class="btn" value="Save Display Order">
</form>

<a href="admin_dashboard.php" class="btn btn-secondary" style="margin-top:20px;">Back to Admin Dashboard</a>

<?php
$conn->close();
include 'footer.php';
?>
