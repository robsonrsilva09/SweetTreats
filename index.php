<?php
include_once 'functions.php';
include 'connection.php';
include 'header.php';

$search_term = isset($_GET['search']) ? trim($_GET['search']) : '';
?>

<h2>Our Full Product Catalogue. </h2>
<h5>Hello, welcome to Sweet Treats Bakery! Here you can find all of our delicious, freshly-made treats.</h5>

<form action="index.php" method="GET" class="search-form">
    <input type="text" name="search" placeholder="Search for products by name..." value="<?php echo htmlspecialchars($search_term); ?>">
    <input type="submit" value="Search">
</form>

<?php
// Display a message and a "clear search" button only if a search is active.
if (!empty($search_term)) {
    echo "<p>Showing results for: <strong>" . htmlspecialchars($search_term) . "</strong></p>";
    echo "<a href='index.php' class='btn btn-secondary'>Clear Search / View All Products</a>";
} else {
    echo "<p>Browse all of our delicious, freshly-made treats.</p>";
}

// Prepare the SQL query
$sql = "SELECT item_name, description, price, image_path FROM menu_items";
if (!empty($search_term)) {
    $sql .= " WHERE item_name LIKE ?";
    $like_term = "%" . $search_term . "%";
}
$sql .= " ORDER BY display_order ASC, item_name ASC";

$stmt = $conn->prepare($sql);
if (!empty($search_term)) {
    $stmt->bind_param("s", $like_term);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="product-grid">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="product-card">';
            if (!empty($row['image_path'])) {
                echo '<img src="uploads/' . htmlspecialchars($row['image_path']) . '" alt="' . htmlspecialchars($row['item_name']) . '">';
            } else {
                echo '<img src="https://placehold.co/300x300/EFEFEF/AAAAAA?text=No+Image" alt="No image available">';
            }
            echo '<h3>' . htmlspecialchars($row['item_name']) . '</h3>';
            echo '<p class="description">' . htmlspecialchars($row['description']) . '</p>';
            echo '<p class="price">&pound;' . number_format($row['price'], 2) . '</p>';
            echo '</div>';
        }
    } else {
       
        echo "<div style='text-align:center; grid-column: 1 / -1;'>";
        echo "<p>Sorry, no products were found matching your search term.</p>";
        echo "</div>";
    }
    ?>
</div>

<?php
$stmt->close();
$conn->close();
include 'footer.php';
?>
