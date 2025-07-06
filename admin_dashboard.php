<?php
include_once 'functions.php';
require_login();
include 'header.php';
?>

<h2>Welcome to the Admin Dashboard</h2>
<p>
    Hello, <strong><?php echo htmlspecialchars($_SESSION['admin_username']); ?></strong>.
    Your assigned role is: <strong><?php echo htmlspecialchars($_SESSION['admin_role']); ?></strong>.
</p>
<p>Please select an option below to manage the website content.</p>

<div class="dashboard-links">
    <ul>
        <li>
            <a href="manage_products.php">
                <strong>Manage Products</strong>
                <span>Add, edit, or delete all products in the catalogue.</span>
            </a>
        </li>
        <li>
            <a href="manage_daily_menu.php">
                <strong>Manage Daily Specials</strong>
                <span>Set the daily offers and their discounts.</span>
            </a>
        </li>
        <li>
            <a href="view_feedback.php">
                <strong>View Customer Feedback</strong>
                <span>See what your customers are saying.</span>
            </a>
        </li>
        <?php
        // Only show the Manage Administrators link if the user is a superadmin.
        if (isset($_SESSION['admin_role']) && $_SESSION['admin_role'] == 'superadmin') {
            echo '<li>
                    <a href="manage_admins.php">
                        <strong>Manage Administrators</strong>
                        <span>Approve or delete admin accounts.</span>
                    </a>
                  </li>';
        }
        ?>
    </ul>
</div>

<?php include 'footer.php'; ?>
