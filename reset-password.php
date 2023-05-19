<?php
ob_start();
session_start();
include("includes/config.php");
if(isset($_GET['email'])) {
  $email = $_GET['email'];
  $query = "SELECT * FROM adminuser WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if($result) {
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paroles atiestatīšana</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://fonts.cdnfonts.com/css/graphik" rel="stylesheet">
  </head>
  <body>
    <div class="container">
      <form class="forgot-passw-form" method="post" action="">
        <h2 class="form-title">Paroles atiestatīšana</h2>
        <div class="form-item">
          <label class="form-item-label">Parole</label></br>
          <input class="form-item-input" type="password" name="password">
        </div>
        <div class="form-item">
          <label class="form-item-label">Parole atkārtoti</label></br>
          <input class="form-item-input" type="password" name="cpassword">
          <?php if (isset($errorConfirmPassword)) { ?>
            <span class="error"> <?php echo $errorConfirmPassword; ?></span>
          <?php } ?>
          <input type="hidden" name="email" value="<?php echo $email;?>">
        </div>
        <input class="forgot-passw-btn" type="submit" name="resetPassword">
        <div class="form-item">
          <?php if (isset($emptyError)) { ?>
            <span class="error"> <?php echo $emptyError; ?></span>
          <?php } ?>
        </div>
      </form>
    </div>
    <?php
    } else {
      echo "Nepareizs links";
      echo "<a href='index'>Uz sākumlapu</a>";
    }
  }
  if(isset($_POST['resetPassword'])) {
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];
    if(empty($_POST['password']) || empty($_POST['cpassword'])) {
      $emptyError = "Lūdzu ievadiet paroli";
    }
    else if($password != $cpassword) {
      $errorConfirmPassword = "Parolēm ir jāsakrīt";
    } else {
      $query = "UPDATE adminuser SET password = MD5('$password') WHERE email = '$email'";
      $result = mysqli_query($conn, $query);
      if($result) {
        header("Location: index");
      } else {
        echo "Parole netika atjauninata";
      }
    } 
  }
    ?>
  </body>
</html>