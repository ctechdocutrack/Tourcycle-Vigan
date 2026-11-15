<?php
if (($_SESSION['admin_role'] ?? '') !== 'admin') {
    header("Location: access-management.php");
    exit();
}
?>