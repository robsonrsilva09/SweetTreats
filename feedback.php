<?php
// This file displays the form for customers to leave their feedback.

include_once 'functions.php';


$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : null;

unset($_SESSION['form_data']);

// The header must be included after the session logic to prevent errors.
include 'header.php';
?>

<h2>Leave Us Your Feedback</h2>
<h5>We'd love to hear what you think about our products! Your feedback helps us to improve.</h5>


<form action="submit_feedback.php" method="POST">
    <label for="customer_name">Your Name:</label>
    <input type="text" id="customer_name" name="customer_name" value="<?php echo htmlspecialchars($form_data['customer_name'] ?? ''); ?>" required>

    <label for="customer_email">Your Email:</label>
    <input type="email" id="customer_email" name="customer_email" value="<?php echo htmlspecialchars($form_data['customer_email'] ?? ''); ?>" required>

    <label for="feedback_text">Your Feedback:</label>
    <textarea id="feedback_text" name="feedback_text" rows="5" required><?php echo htmlspecialchars($form_data['feedback_text'] ?? ''); ?></textarea>

    <input type="submit" value="Submit Feedback">
</form>

<?php
include 'footer.php';
?>
