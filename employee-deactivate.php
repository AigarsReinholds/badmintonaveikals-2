<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) !== 'admin') {
  header("Location: login");
}
if(isset($_POST['deactivate'])) {
  if($_POST['confirmDeactivation'] == 'true') {
    $id = $_POST['id'];
    $sql = "UPDATE adminuser SET status = 0 WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      header("Location: employee");
    } else { 
      echo "Radusies kļūda deaktivizējot darbinieku";
    }
  }
} 
?>