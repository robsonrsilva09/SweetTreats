<?php
include_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sweet Treats Bakery</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
        <a href="index.php">
            <img src="assets/images/logo.png" alt="Sweet Treats Bakery Logo" class="logo">
        </a>
        <div class="header-text">
            <h1>Sweet Treats Bakery</h1>
            <p>Your daily dose of happiness!</p>
        </div>
    </header>
    <nav>
        <a href="index.php">Home (Full Catalogue)</a>
        <a href="daily_menu.php">Daily Specials</a>
        <a href="feedback.php">Leave Feedback</a>
        
        <?php if (isset($_SESSION['admin_id'])): ?>
            <a href="admin_dashboard.php">Admin Dashboard</a>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="admin_login.php">Admin Login</a>
        <?php endif; ?>
    </nav>
    <div class="container">
        <?php        
        display_flash_messages();
        ?>
