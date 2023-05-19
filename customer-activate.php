<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) !== 'admin') {
  header("Location: login");
}
if(isset($_POST['activate'])) {
  if($_POST['confirmActivation'] == 'true') {
    $id = $_POST['id'];
    $sql = "UPDATE user SET status = 1 WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      header("Location: customer");
    } else { 
      echo "Radusies kļūda aktivizējot lietotāju";
    }
  }
} 
?>