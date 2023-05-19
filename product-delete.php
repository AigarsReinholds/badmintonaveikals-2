<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['delete'])) {
  if($_POST['confirmDelete'] == 'true') {
    $productId = $_POST['id'];
    $sql = "DELETE FROM product WHERE id = '$productId'";
    $result = mysqli_query($conn, $sql);
    $gallerySql = "DELETE FROM product_image WHERE productId = '$productId'";
    $galleryResult = mysqli_query($conn, $gallerySql);
    if($result) {
      header("Location: product");
    } else { 
      echo "Radusies kļūda dzēšot produktu";
    }
  }
} 
?>


