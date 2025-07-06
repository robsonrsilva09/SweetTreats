<?php

include 'header.php';
include 'connection.php';

//Only logged-in admins can view this page.
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

$sql = "SELECT customer_name, feedback_text, submitted_at FROM feedback ORDER BY submitted_at DESC";
$result = $conn->query($sql);
?>

<h2>Customer Feedback</h2>

<a href="admin_dashboard.php" class="btn btn-secondary">Back to Admin Dashboard</a>

<?php

if ($result && $result->num_rows > 0) {   
    while($row = $result->fetch_assoc()) {        
        echo "<div class='feedback-item'>";
        echo "<p><strong>" . htmlspecialchars($row['customer_name']) . "</strong> wrote:</p>";       
        echo "<p>" . nl2br(htmlspecialchars($row['feedback_text'])) . "</p>";        
        echo "<p class='meta'>Submitted on: " . date("d F Y, H:i", strtotime($row['submitted_at'])) . "</p>";
        echo "</div>";
    }
} else {    
    echo "<p style='margin-top: 20px;'>No feedback has been submitted yet.</p>";
}

$conn->close();
include 'footer.php';
?>
