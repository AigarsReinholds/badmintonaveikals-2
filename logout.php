<?php
session_start();
include("admin/includes/config.php");
unset($_SESSION['user_id']);
session_destroy();
header("Location: login");
?>