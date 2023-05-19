<?php require_once('header.php');?> 
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
/*Parada lietotaja datus */
if(isset($_SESSION['admin_id'])) {
  $id = $_SESSION['admin_id'];
  $sql = "SELECT * FROM adminuser WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_array($result);
  if(isset($_POST['updateProfile'])) {
    $error = false;
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if(!empty($password) && empty($cpassword)) { 
      $error = true;
      $emptyError = "Ievadiet paroli atkārtoti";
    }
    else if(strlen($password) < 8) {
      $error = true;
      $errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
    } 
    else if(!preg_match('/[A-Z]/', $password)) {
      $error = true;
      $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
    }
    else if($password != $cpassword) {
      $error = true;
      $errorConfirmPassword = "Parolēm ir jāsakrīt";
    }
    else if (MD5($password) == $row['password']) {
      $error = true;
      $errorConfirmPassword = "Šāda parole jau pastāv";
    }
    if(strlen($phone) < 8) {
      $error = true;
      $errorCount = "Telefonam ir jāsatur 8 simboli";
    } else {
      if(empty($password)) {
        if(!$error) {
          $sql = "UPDATE adminuser SET firstName = '$firstname', lastName = '$lastname', phone = '$phone' WHERE id = '$id'";
          $result = mysqli_query($conn, $sql);
          if($result) {
            header("Location: profile");
          }
        }
      } else {
        if(!$error) {
          $sql = "UPDATE adminuser SET firstName = '$firstname', lastName = '$lastname', phone = '$phone', password = MD5('$password') WHERE id = '$id'";
          $result = mysqli_query($conn, $sql);
          if($result) {
            header("Location: profile");
          }
        }
      }
    }
  }
?>
<div class="container">
  <div class="profile-page">
    <h3>Jūsu profila dati</h3>
    <div class="profile-data">
      <table>
        <tbody>
          <tr>
            <td class="profile-user-data-title">E-pasts:</td>
            <td><?php echo $row['email'];?></td>
          </tr>
          <tr>
            <td class="profile-user-data-title">Vārds:</td>
            <td><?php echo $row['firstName'];?></td>
          </tr>
          <tr>
            <td class="profile-user-data-title">Uzvārds:</td>
            <td><?php echo $row['lastName'];?></td>
          </tr>  
        </tbody>
        <tr>
            <td class="profile-user-data-title">Tālrunis:</td>
            <td><?php echo $row['phone'];?></td>
          </tr>  
      </table>
    </div>
    <div class="profile-user-data-edit-btn">
      <a onclick="displayProfileUpdateForm();">Rediģēt</a>
    </div>
    <form class="update-profile-form" method="POST">
      <div class="update-profile-form-inner">
        <div class="form-item">
          <label>Jauna parole</label>
          <input id="password" type="password" name="password" value="<?php if(isset($_POST['password'])) {echo $_POST['password'];}?>">
          <span id="togglePassword" class="toggle-password">
            <i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
          </span></br>
        </div>
        <div class="form-item">
          <label>Jauna parole atkārtoti</label>
          <input id="cpassword" type="password" name="cpassword" value="<?php if(isset($_POST['cpassword'])) {echo $_POST['cpassword'];}?>">
          <span id="toggleCPassword" class="toggle-password">
            <i class="fa fa-eye-slash" onclick="togglePassword('cpassword', 'toggleCPassword')"></i>
          </span></br>
          <?php if(isset($emptyError)) { ?>
            <span class="error-message"> <?php echo $emptyError; ?></span>
          <?php } ?></br>
          <?php if(isset($errorPassword)) { ?>
            <span class="error-message"> <?php echo $errorPassword; ?></span>
          <?php } ?></br>
          <?php if(isset($errorConfirmPassword)) { ?>
            <span class="error-message"> <?php echo $errorConfirmPassword; ?></span>
          <?php } ?></br>
        </div>  
        <hr>
        <div class="form-item">      
          <label>Vārds</label>
          <input type="text" name="firstName" value="<?php echo $row['firstName'];?>"></br>
        </div>
        <div class="form-item">
          <label>Uzvārds</label>
          <input type="text" name="lastName" value="<?php echo $row['lastName'];?>"></br>
        </div>
        <div class="form-item">      
          <label>Tālrunis</label>
          <input type="tel" name="phone" value="<?php echo $row['phone'];?>"></br>
          <span class="error-message"> <?php if(isset($errorCount)) { echo $errorCount; } ?></span></br>
        </div>
        <div class="update-profile-form-controls">
          <input class="update-profile-btn save-btn" type="submit" name="updateProfile" value="Saglabāt">
          <a onclick="closeProfileUpdateForm()">Atcelt</a>
        </div>
      </div>
    </form>    
  </div>  
<?php
}
?>
</div>
<?php require_once('footer.php');?> 