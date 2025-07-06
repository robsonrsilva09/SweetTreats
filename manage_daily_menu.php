<?php

include_once 'functions.php';
include 'connection.php';
require_login();

// Use the date from the URL if it exists, otherwise default to today.
$selected_date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$existing_specials = [];

$sql_fetch = "SELECT menu_item_id, discount_percentage FROM daily_specials WHERE special_date = ?";
$stmt_fetch = $conn->prepare($sql_fetch);
$stmt_fetch->bind_param("s", $selected_date);
$stmt_fetch->execute();
$result_fetch = $stmt_fetch->get_result();
while ($row = $result_fetch->fetch_assoc()) {
    $existing_specials[] = $row;
}
$stmt_fetch->close();


$products_sql = "SELECT id, item_name FROM menu_items ORDER BY item_name ASC";
$products_result = $conn->query($products_sql);

include 'header.php';
?>

<h2>Manage Daily Specials</h2>
<p>Select a date to view or edit specials. The form will pre-fill with any existing offers for that day.</p>

<form action="manage_daily_menu.php" method="GET" style="border:none; padding:0; margin-bottom: 20px;">
    <label for="date_selector">Select Date:</label>
    <input type="date" id="date_selector" name="date" value="<?php echo htmlspecialchars($selected_date); ?>" onchange="this.form.submit()">
</form>

<hr>

<form action="process_daily_menu.php" method="POST">
    
    <input type="hidden" name="special_date" value="<?php echo htmlspecialchars($selected_date); ?>">

    <?php for ($i = 1; $i <= 3; $i++): ?>
        <h4>Special Offer <?php echo $i; ?></h4>
        
        <?php
        
        $current_special = isset($existing_specials[$i-1]) ? $existing_specials[$i-1] : null;
        $selected_product_id = $current_special ? $current_special['menu_item_id'] : null;
        $discount_value = $current_special ? $current_special['discount_percentage'] : '';
        ?>

        <label for="product_<?php echo $i; ?>">Select Product:</label>
        <select id="product_<?php echo $i; ?>" name="specials[<?php echo $i; ?>][product_id]">
            <option value="">-- No Special --</option>
            <?php
            
            if ($products_result->num_rows > 0) {
                $products_result->data_seek(0); 
                while($product = $products_result->fetch_assoc()) {                
                    $is_selected = ($product['id'] == $selected_product_id);
                    echo "<option value='" . $product['id'] . "' " . ($is_selected ? 'selected' : '') . ">" . htmlspecialchars($product['item_name']) . "</option>";
                }
            }
            ?>
        </select>
        
        <label for="discount_<?php echo $i; ?>">Discount Percentage (%):</label>
       
        <input type="number" id="discount_<?php echo $i; ?>" name="specials[<?php echo $i; ?>][discount]" value="<?php echo htmlspecialchars($discount_value); ?>" min="0" max="100" placeholder="e.g., 15">
        
        <?php if ($i < 3) echo '<hr style="margin: 20px 0;">'; ?>
    <?php endfor; ?>
    
    <input type="submit" class="btn" value="Set/Update Specials for <?php echo date("d/m/Y", strtotime($selected_date)); ?>">
</form>

<a href="admin_dashboard.php" class="btn btn-secondary">Back to Admin Dashboard</a>

<?php
$conn->close();
include 'footer.php';
?>
