<?php
session_start();
include("includes/config.php");
unset($_SESSION['admin_id']);
session_destroy();
header("Location: login");
?>