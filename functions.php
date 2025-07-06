<?php

// Start the session if it's not already started. This is needed for flash messages and login checks.
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function set_flash_message($name, $message) {
    $_SESSION['flash_messages'][$name] = $message;
}

function display_flash_messages() {
    if (isset($_SESSION['flash_messages'])) {
        foreach ($_SESSION['flash_messages'] as $name => $message) {
            
            echo "<div class='{$name}'>{$message}</div>";
        }
       
        unset($_SESSION['flash_messages']);
    }
}

/**
 * Checks if an admin is logged in. If not, redirects to the login page.
 */
function require_login() {
    if (!isset($_SESSION['admin_id'])) {
        header('Location: admin_login.php');
        exit();
    }
}

/**
 * Checks if the logged-in user is a super-admin. If not, redirects to the dashboard.
 */
function require_superadmin() {
    
    if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] !== 'superadmin') {
        set_flash_message('error', 'Access denied. You do not have permission to view this page.');
        header('Location: admin_dashboard.php');
        exit();
    }
}
