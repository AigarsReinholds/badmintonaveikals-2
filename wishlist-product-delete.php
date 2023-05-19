<?php
session_start();
include("admin/includes/config.php");
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
if(isset($_POST['delete'])) {
  $productId = $_POST['productId'];
  $userId = $_SESSION['user_id'];
  $sql = "DELETE FROM wishlist WHERE productId = '$productId' AND userId = '$userId'";
  $result = mysqli_query($conn, $sql);
  header("Location: wishlist");
} 
?>