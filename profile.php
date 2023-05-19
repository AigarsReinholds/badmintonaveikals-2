<?php require_once('header.php');?> 
<?php
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
/*Parada lietotaja datus*/
if(isset($_SESSION['user_id'])) {
  $id = $_SESSION['user_id'];
  $sql = "SELECT * FROM user WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  if(isset($_POST['updateProfile'])) {
    $error = false;
    $passwordInput = false;
    $firstname = $_POST['firstName'];
    $lastname = $_POST['lastName'];
    $phone = $_POST['phone'];
    $adress = $_POST['adress'];
    $city = $_POST['city'];
    $country = $_POST['country'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if(!empty($password) && strlen($password) < 8) {
      $error = true;
      $errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
    }
    else if(!empty($password) && !preg_match('/[A-Z]/', $password)){
      $error = true;
      $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
    }
    if(!empty($password) && empty($cpassword)) { 
      $error = true;
      $emptyError = "Ievadiet paroli atkārtoti";
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
    }
    else {
    if(empty($password)) {
      if(!$error) {
        $sql = "UPDATE user SET firstName = '$firstname', lastName = '$lastname', phone = '$phone', adress = '$adress', city = '$city', country = '$country'
        WHERE id = '$id'";
        $result = mysqli_query($conn, $sql);
        if($result) {
          header("Location: profile");
        }
      }
    } else {
      if(!$error) {
        $sql = "UPDATE user SET firstName = '$firstname', lastName = '$lastname', phone = '$phone', adress = '$adress', city = '$city', country = '$country',
        password = MD5('$password') WHERE id = '$id'";
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
    <div class="row row-profile-data">
      <div class="col col-left">
        <div class="order-list">
          <h3><a href="profile">Profils</a></h3><hr>
          <h3><a href="order">Veiktie pasūtījumi</a></h3><hr>
          <h3><a href="wishlist">Vēlmju saraksts</a></h3><hr>
        </div> 
      </div>
      <div class="col col-right">
        <h3>Lietotāja dati</h3>
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
              <tr>
                <td class="profile-user-data-title">Tālrunis:</td>
                <td><?php echo $row['phone'];?></td>
              </tr>
              <tr>
                <td class="profile-user-data-title">Adrese:</td>
                <td><?php echo $row['adress'];?></td>
              </tr>
              <tr>
                <td class="profile-user-data-title">Pilsēta:</td>
                <td><?php echo $row['city'];?></td>
              </tr>
              <tr>
                <td class="profile-user-data-title">Valsts:</td>
                <td><?php echo $row['country'];?></td>
              </tr>
            </tbody>  
          </table>
        </div>
        <div class="profile-user-data-edit-btn">
          <a onclick="displayProfileUpdateForm();">Rediģēt</a>
        </div>
      </div>
      </div>
      <form class="update-profile-form" method="POST" action="">
        <div class="update-profile-form-inner">
        <div class="row row-profile-data-edit">
          <div class="col-left">
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
                <span class="error-message"> <?php echo $emptyError; ?></span></br>
              <?php } ?>
              <?php if(isset($errorPassword)) { ?>
                <span class="error-message"> <?php echo $errorPassword; ?></span></br>
              <?php } ?>
              <?php if(isset($errorConfirmPassword)) { ?>
                <span class="error-message"> <?php echo $errorConfirmPassword; ?></span></br>
              <?php } ?>
            </div>  
          </div>
          <hr>
          <div class="col-right">
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
              <?php if(isset($errorCount)) { ?>
                <span class="error-message"><?php echo $errorCount; ?></span></br>
              <?php } ?>
            </div>
            <div class="form-item">
              <label>Adrese</label>
              <input type="text" name="adress" value="<?php echo $row['adress'];?>"></br>
            </div>
            <div class="form-item">
              <label>Pilsēta</label>
              <input type="text" name="city" value="<?php echo $row['city'];?>"></br>
            </div>
            <div class="form-item">
              <label>Valsts</label>
              <input type="text" name="country" value="<?php echo $row['country'];?>"></br>
            </div>
            <div class="update-profile-form-controls">
              <input class="update-profile-btn" type="submit" name="updateProfile" value="Saglabāt">
              <a onclick="closeProfileUpdateForm()">Atcelt</a>
            </div>
          </div>
        </form>    
      </div>
    </div>  
  </div>  
<?php
}
  ?>
</div>
<?php require_once('footer.php');?> 