<?php

include_once 'functions.php';
include 'connection.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: admin_dashboard.php");
    exit();
}

$special_date = $_POST['special_date'];
$specials = $_POST['specials'];

// First clear any existing specials for the selected date to avoid conflicts.
$delete_sql = "DELETE FROM daily_specials WHERE special_date = ?";
$stmt_delete = $conn->prepare($delete_sql);
$stmt_delete->bind_param("s", $special_date);
$stmt_delete->execute();
$stmt_delete->close();

// Now insert the new specials submitted from the form.
$insert_sql = "INSERT INTO daily_specials (menu_item_id, special_date, discount_percentage) VALUES (?, ?, ?)";
$stmt_insert = $conn->prepare($insert_sql);

$specials_set_count = 0;
foreach ($specials as $special) {
    
    if (!empty($special['product_id'])) {
        $product_id = $special['product_id'];        
        $discount = !empty($special['discount']) ? (int)$special['discount'] : 0;
        $stmt_insert->bind_param("isi", $product_id, $special_date, $discount);
        $stmt_insert->execute();
        $specials_set_count++;
    }
}

$stmt_insert->close();
$conn->close();

set_flash_message('success', "Specials for " . date("d/m/Y", strtotime($special_date)) . " have been updated successfully. " . $specials_set_count . " special(s) set.");
header("Location: manage_daily_menu.php?date=" . $special_date);
exit();
