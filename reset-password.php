<?php
require_once('header.php');
if(isset($_GET['email'])) {
  $email = $_GET['email'];
  $query = "SELECT * FROM user WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if($result) {
  ?>
    <div class="container">
      <form class="forgot-passw-form" method="post" action="">
        <h2 class="form-title">Paroles atiestatīšana</h2>
        <div class="form-item">
          <label class="form-item-label">Parole</label></br>
          <input id="password" class="form-item-input" type="password" name="password">
          <span id="togglePassword" class="toggle-password">
            <i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
          </span></br>
        </div>
        <div class="form-item">
          <label class="form-item-label">Parole atkārtoti</label></br>
          <input id="cpassword" class="form-item-input" type="password" name="cpassword">
          <span id="toggleCPassword" class="toggle-password">
            <i class="fa fa-eye-slash" onclick="togglePassword('cpassword', 'toggleCPassword')"></i>
          </span></br>
          <?php if (isset($errorConfirmPassword)) { ?>
            <span class="error"> <?php echo $errorConfirmPassword; ?></span>
          <?php } ?>
          <input type="hidden" name="email" value="<?php echo $email;?>">
        </div>
        <input class="forgot-passw-btn" type="submit" name="resetPassword">
        <?php if (isset($emptyError)) { ?>
          <div class="form-item">
            <span class="error"> <?php echo $emptyError; ?></span>
          </div>
        <?php } ?>
        <?php if (isset($errorConfirmPassword)) { ?>
          <div class="form-item">
            <span class="error"> <?php echo $errorConfirmPassword; ?></span>
          </div>
        <?php } ?>
      </form>
    </div>
    <?php
    } else {
      echo "Nepareizs links";
      echo "<a href='https://badmintonaveikals.shop/'>Uz sākumlapu</a>";
    }
  } 
  if(isset($_POST['resetPassword'])) {
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $email = $_POST['email'];
    if(empty($_POST['password']) || empty($_POST['cpassword'])) {
      $emptyError = "Lūdzu ievadiet paroli";
    }
    else if(strlen($password) < 8) {
      $errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
    }
    else if(!preg_match('/[A-Z]/', $password)){
      $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
    }
    else if($password != $cpassword) {
      $errorConfirmPassword = "Parolēm ir jāsakrīt";
    } else {
      $query = "UPDATE user SET password = MD5('$password') WHERE email = '$email'";
      $result = mysqli_query($conn, $query);
      if($result) {
        header("Location: https://badmintonaveikals.shop/");
      } else {
        echo "Parole netika atjauninata";
      }
    } 
  }
  ?>
<?php require_once('footer.php'); ?>