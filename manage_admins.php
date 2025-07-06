<?php

include 'header.php';
include 'connection.php';

// Protect this page from non-superadmins.
if (!isset($_SESSION['admin_role']) || $_SESSION['admin_role'] != 'superadmin') {
    header("Location: admin_dashboard.php");
    exit();
}

$sql = "SELECT id, username, role, is_approved FROM admin_users ORDER BY username ASC";
$result = $conn->query($sql);
?>

<h2>Manage Administrators</h2>
<p>Approve new admin registrations or remove existing admin accounts.</p>

<?php
if (isset($_GET['status'])) {
    if ($_GET['status'] == 'approved') echo '<p class="success">User has been approved successfully.</p>';
    if ($_GET['status'] == 'deleted') echo '<p class="success">User has been deleted successfully.</p>';
}
if (isset($_GET['error'])) {
    echo '<p class="error">' . htmlspecialchars($_GET['error']) . '</p>';
}
?>

<table>
    <thead>
        <tr>
            <th>Username</th>
            <th>Role</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['username']) . "</td>";
                echo "<td>" . htmlspecialchars($row['role']) . "</td>";
                
                if ($row['is_approved'] == 1) {
                    echo "<td>Approved</td>";
                    if ($row['id'] != $_SESSION['admin_id']) {
                        echo "<td>
                                <form action='delete_admin.php' method='POST' onsubmit='return confirm(\"Are you sure? This action cannot be undone.\");' style='margin:0;'>
                                    <input type='hidden' name='user_id_to_delete' value='" . $row['id'] . "'>
                                    <input type='submit' value='Delete' style='background-color:#c0392b;'>
                                </form>
                              </td>";
                    } else {
                        echo "<td>(This is you)</td>";
                    }
                } else {
                    echo "<td>Pending Approval</td>";
                    echo "<td>
                            <form action='approve_admin.php' method='POST' style='margin:0;'>
                                <input type='hidden' name='user_id_to_approve' value='" . $row['id'] . "'>
                                <input type='submit' value='Approve'>
                            </form>
                          </td>";
                }
                echo "</tr>";
            }
        }
        ?>
    </tbody>
</table>
<a href="admin_dashboard.php" class="btn btn-secondary">Back to Admin Dashboard</a>

<?php
$conn->close();
include 'footer.php';
?>
