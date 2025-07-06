<?php
include 'header.php';
include 'connection.php';

$today = date('Y-m-d');
$display_date = $today; 
$is_fallback = false;   // Flag to check if we are showing older specials

//  Try to find specials for today's date.
$sql_today = "SELECT mi.item_name, mi.description, mi.price, mi.image_path, ds.discount_percentage
              FROM daily_specials ds
              JOIN menu_items mi ON ds.menu_item_id = mi.id
              WHERE ds.special_date = ?";

$stmt_today = $conn->prepare($sql_today);
$stmt_today->bind_param("s", $today);
$stmt_today->execute();
$result = $stmt_today->get_result();

// If no specials are found for today, find the most recent day that had specials.
if ($result->num_rows == 0) {
    $is_fallback = true; 

    
    $sql_latest_date = "SELECT MAX(special_date) as latest_date FROM daily_specials";
    $date_result = $conn->query($sql_latest_date);
    $latest_date_row = $date_result->fetch_assoc();
    $latest_date = $latest_date_row['latest_date'];

    if ($latest_date) {
        $display_date = $latest_date; // Update the date we are displaying for
        
        
        $stmt_today->bind_param("s", $latest_date);
        $stmt_today->execute();
        $result = $stmt_today->get_result();
    }
}

$stmt_today->close();
?>

<h2>Daily Specials.</h2>
<h5>Every day specially selected products at a special price!</h5>

<?php 

if ($is_fallback && $result->num_rows > 0) {
    echo "<p><em>No new specials for today. Here are our most recent offers from " . date("d/m/Y", strtotime($display_date)) . ":</em></p>";
}
?>

<div class="specials-grid"> 
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $original_price = $row['price'];
            $discount = $row['discount_percentage'];
            $discounted_price = $original_price - ($original_price * $discount / 100);

            echo '<div class="product-card">';
            if (!empty($row['image_path'])) {
                echo '<img src="uploads/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['item_name']) . '">';
            } else {
                echo '<img src="https://placehold.co/300x300/EFEFEF/AAAAAA?text=No+Image">';
            }
            echo '<h3>' . htmlspecialchars($row['item_name']) . '</h3>';
            echo '<p class="description">' . htmlspecialchars($row['description']) . '</p>';
            echo '<p class="price special">';
            echo '<span>NOW: &pound;' . number_format($discounted_price, 2) . '</span>';
            echo '<del>Was: &pound;' . number_format($original_price, 2) . '</del>';
            echo '</p>';
            echo '</div>';
        }
    } else {
        echo "<p>There are no special offers today. Please check our full menu on the Home page!</p>";
    }
    ?>
</div>

<?php
$conn->close();
include 'footer.php';
?>
