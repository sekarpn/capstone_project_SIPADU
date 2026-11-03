<?php
session_start();
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_role'] === 'ADMIN') {
        header("Location: admin/dashboard.php");
    } else {
        header("Location: user/dashboard.php");
    }
    exit();
} else {
    header("Location: login.php");
    exit();
}
?>