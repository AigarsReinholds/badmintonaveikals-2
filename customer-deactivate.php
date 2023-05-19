<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['deactivate'])) {
  if($_POST['confirmDeactivation'] == 'true') {
    $id = $_POST['id'];
    $sql = "UPDATE user SET status = 0 WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      header("Location: customer");
    } else { 
      echo "Radusies kļūda deaktivizējot lietotāju";
    }
  }
} 
?>