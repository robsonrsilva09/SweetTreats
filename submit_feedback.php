<?php

include_once 'functions.php';
include 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Trim whitespace from the beginning and end of the inputs.
    $name = trim($_POST['customer_name']);
    $email = trim($_POST['customer_email']);
    $feedback = trim($_POST['feedback_text']);
  
    // Validate the email format using a standard PHP function.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {        
        set_flash_message('error', 'Invalid email format. Please enter a valid email address.');      
      
        $_SESSION['form_data'] = $_POST;
        
        header("Location: feedback.php");
        exit();
    }
    
    if (empty($name) || empty($feedback)) {
        set_flash_message('error', 'Please ensure all fields are filled in.');
        $_SESSION['form_data'] = $_POST;
        header("Location: feedback.php");
        exit();
    }    
   
    $sql = "INSERT INTO feedback (customer_name, customer_email, feedback_text) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        die("Error preparing the statement: " . $conn->error);
    }

    $stmt->bind_param("sss", $name, $email, $feedback);

    if ($stmt->execute()) {        
        set_flash_message('success', 'Thank you for your feedback! It has been received.');
        header("Location: feedback.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
