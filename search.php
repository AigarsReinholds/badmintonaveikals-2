<?php 
ob_start();
session_start();
include("includes/config.php");
?>
<?php
if(isset($_GET['searchWord'])) {
  $searchWord = strtolower(($_GET['searchWord']));
  $query = "SELECT * FROM product WHERE LOWER(name) LIKE '%$searchWord%'";
  $results = mysqli_query($conn, $query);
  if(!$results){
    echo mysqli_error($conn);
  }
  $products = mysqli_fetch_all($results, MYSQLI_ASSOC);
  echo trim(json_encode($products));
}
?>
