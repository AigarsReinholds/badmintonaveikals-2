<?php
ob_start();
session_start();
include("includes/config.php");
if(isset($_SESSION['admin_id'])!="") {
  header("Location: profile");
}
if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST['email'])) {
    $emailErrorMessage = "Ievadi e-pasta adresi";
  }
  else if(empty($_POST['password'])) {
    $passwordErrorMessage = "Ievadi paroli";
  } else {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM `adminuser` WHERE email='$email' AND password=MD5('$password')";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    if($row == null) {
      $errorMessage = "Nepareizs e-pasts un/vai parole";
    } else {
      $count = mysqli_num_rows($result);
      $status = $row['status'];
      if ($count == 1 && $status == 0) {
        $statusError = "Jūsu konts ir deaktivizēts";
      } else {  
        if ($count == 1) {
          if($row['role'] == 'admin') {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['user_role'] = 'admin';
            $sqlUpdate = "UPDATE adminuser SET lastLogin = CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00') WHERE id = ". $_SESSION['admin_id'];
            $resultUpdate = mysqli_query($conn, $sqlUpdate);
            header("Location: https://badmintonaveikals.shop/admin");
          }
          else if($row['role'] == 'employee') {
            $_SESSION['admin_id'] = $row['id'];
            $_SESSION['user_role'] = 'employee';
            $sqlUpdate = "UPDATE adminuser SET lastLogin = CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00') WHERE id = ". $_SESSION['admin_id'];
            $resultUpdate = mysqli_query($conn, $sqlUpdate);
            header("Location: https://badmintonaveikals.shop/admin");
          }
        }
      }
    }
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin login</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://fonts.cdnfonts.com/css/graphik" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/img/favicon-32x32.png">
  </head>
  <body>
    <div class="container container-login">
      <form class="login-form" action="" method="post">
        <h1 class="form-title">Pieslēgšanās admin panelim</h1>
        <div class="form-item">
          <?php if (isset($errorMessage)) { ?>
            <span class="error"> <?php echo $errorMessage; ?></span></br>
          <?php } ?>
          <?php if (isset($statusError)) { ?>
            <span class="error"> <?php echo $statusError; ?></span></br>
          <?php } ?>  
          <label class="form-item-label">E-pasts</label></br>
          <input class="form-item-input" type="email" name="email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>"></br>
          <?php if (isset($emailErrorMessage)) { ?>
            <span class="error"> <?php echo $emailErrorMessage; ?></span>
          <?php } ?>      
        </div>
        <div class="form-item">
          <label class="form-item-label">Parole</label></br>
          <input id="password" class="form-item-input" type="password" name="password" value="<?php if(isset($_POST['password'])) {echo $_POST['password'];}?>">
          <span id="togglePassword" class="toggle-password">
            <i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
          </span></br>
          <?php if (isset($passwordErrorMessage)) { ?>
            <span class="error"> <?php echo $passwordErrorMessage; ?></span>
          <?php } ?>  
        </div>
        <div class="form-item">
          <input class="login-submit-btn" type="submit" name="loginbtn" value="Pieslēgties">
        </div>
        <div class="form-item">
          <div class="login-forgot">
            <p><a href="forgot-password">Aizmirsi paroli?</a></p>
          </div>
        </div>
      </form>
    </div>
    <script src="assets/js/script.js"></script>
  </body>
</html>  