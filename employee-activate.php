<?php include_once('includes/config.php');
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_POST['activate'])) {
  if($_POST['confirmActivation'] == 'true') {
    $id = $_POST['id'];
    $sql = "UPDATE adminuser SET status = 1 WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      header("Location: employee");
    } else { 
      echo "Radusies kļūda aktivizējot darbinieku";
    }
  }
} 
?>