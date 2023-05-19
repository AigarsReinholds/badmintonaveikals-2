<?php require_once('header.php');
if(isset($_SESSION['user_id'])!="") {
  header("Location: profile");
} 
if($_SERVER["REQUEST_METHOD"] == "POST") {
  if(empty($_POST['email'])) {
    $emailErrorMessage = "Ievadiet e-pasta adresi";
  }
  else if(empty($_POST['password'])) {
    $passwordErrorMessage = "Ievadiet paroli";
  } else {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $sql = "SELECT * FROM `user` WHERE email='$email' AND password=MD5('$password')";
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
          $_SESSION['user_id'] = $row['id'];
          $_SESSION['user_role'] = $row['status'];
          $sqlUpdate = "UPDATE user SET lastLogin = CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00') WHERE id = ". $_SESSION['user_id'];
          $resultUpdate = mysqli_query($conn, $sqlUpdate);
          header("Location: https://badmintonaveikals.shop/");
        }
      }
    }
  }
}
?>
<div class="container">
  <form class="login-form" action="" method="post">
    <div class="login-form-inner">
      <h1>Pieslēgties</h1>
      <div class="form-item">
        <?php if (isset($errorMessage)) { ?>
          <span class="error-message"> <?php echo $errorMessage; ?></span></br>
        <?php } ?> 
        <?php if (isset($statusError)) { ?>
            <span class="error-message"> <?php echo $statusError; ?></span></br>
          <?php } ?> 
        <label>E-pasts</label></br>
        <input type="email" name="email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>"></br>
        <?php if (isset($emailErrorMessage)) { ?>
          <span class="error-message"> <?php echo $emailErrorMessage; ?></span>  
        <?php } ?>  
      </div>
      <div class="form-item">
        <label>Parole</label></br>
        <input id="password" type="password" name="password" value="<?php if(isset($_POST['password'])) {echo $_POST['password'];}?>">
        <span id="togglePassword" class="toggle-password">
          <i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
        </span></br>
        <?php if (isset($passwordErrorMessage)) { ?>
          <span class="error-message"> <?php echo $passwordErrorMessage; ?></span>
        <?php } ?> 
      </div>
      <div class="form-item">
        <input class="login-submit-btn" type="submit" name="loginbtn" value="Pieslēgties">
      </div>
      <div class="row row-links">
        <div class="login-forgot">
          <p><a href="forgot-password">Aizmirsi paroli?</a></p>
        </div>
        <div class="link-registration">
          <p><a href="registration">Reģistrēties</a></p>
        </div>
      </div>
    </div>     
  </form>
</div>
<?php require_once('footer.php'); ?>
