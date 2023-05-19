<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['delete']) && $_POST['delete'] == 'true' ) {
  if($_POST['confirmDelete'] == 'true') {
    $id = $_POST['id'];
    $sql = "DELETE FROM product_category WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      header("Location: category");
    } else { 
      echo "Radusies kļūda dzēšot kategoriju";
    }
  }
}
?>