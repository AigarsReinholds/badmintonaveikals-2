<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) != 'admin') {
  header("Location: login");
}
if(isset($_POST['employeeAdd'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $cpassword = $_POST['cpassword'];
  $phone = $_POST['phone'];
  $sqlSelect = "SELECT * FROM adminuser WHERE email = '$email'";
	$resultSelect = mysqli_query($conn, $sqlSelect);
  if(empty($firstName)) {
    $errorFirstName = "Vārds ir obligāts lauks";
  }
  else if(empty($lastName)) {
    $errorLastName = "Uzvārds ir obligāts lauks";
  }
  else if(empty($email)) {
    $errorEmail = "E-pasts ir obligāts lauks";
  }
  else if(empty($password)) {
    $errorPassword = "Parole ir obligāts lauks";
  }
  else if(strlen($password) < 8) {
    $errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
  }
  else if(!preg_match('/[A-Z]/', $password)){
    $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
  }
  else if (empty($cpassword)) {
		$error = true;
		$errorConfirmPassword = "Ievadi paroli atkārtoti";
	}
	else if($password != $cpassword) {
		$error = true;
		$errorConfirmPassword = "Parolēm ir jāsakrīt";
	}
  else if (mysqli_num_rows($resultSelect) > 0) {
		$rowUser = mysqli_fetch_assoc($resultSelect);
		$error = true;
			$errorEmailExists = "Darbinieks ar šādu e-pastu jau pastāv";
	} else {
    $sql = "INSERT INTO adminuser(firstName, lastName, email, password, phone, role, lastLogin, status) VALUES
    ('$firstName', '$lastName', '$email', MD5('$password'), '$phone', 'employee', CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00'), 1)";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $_SESSION['successMessageEmployee'] = 'Darbinieks veiksmīgi pievienots';
      header("Location: employee");
      exit;
    } else {
      echo "Radusies kļūda pievienojot darbinieku";
    }
  }
}
?>
<div class="container">
  <div class="container-inner">
    <form class="employee-add-form employee-form" action="" method="post">
      <h1>Darbinieka pievienošana</h1>
      <?php if(isset($errorEmailExists)) {?>
      <div class="form-item">  
				<span class="error-message"> <?php echo $errorEmailExists; ?></span></br>
      </div>
			<?php } ?>
      <div class="form-item">
        <label>Vārds</label>
        <input type="text" name="firstName" value="<?php if(isset($firstName)) { echo $firstName; }?>"></br>
        <?php if(isset($errorFirstName)) { ?>
          <span class="error-message"><?php echo $errorFirstName; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Uzvārds</label>
        <input type="text" name="lastName" value="<?php if(isset($lastName)) { echo $lastName; }?>"></br>
        <?php if(isset($errorLastName)) { ?>
          <span class="error-message"><?php echo $errorLastName; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>E-pasts</label>
        <input type="email" name="email" value="<?php if(isset($email)) { echo $email; }?>"></br>
        <?php if(isset($errorEmail)) { ?>
          <span class="error-message"><?php echo $errorEmail; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Parole</label>
        <input type="password" name="password" value="<?php if(isset($password)) { echo $password; }?>"></br>
        <?php if(isset($errorPassword)) {?>
          <span class="error-message"><?php echo $errorPassword; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
				<label>Parole atkārtoti</label>
				<input type="password" name="cpassword" value="<?php if(isset($_POST['cpassword'])) {echo $_POST['cpassword'];}?>"></br>
				<?php if(isset($errorConfirmPassword)) {  ?>
					<span class="error-message"> <?php echo $errorConfirmPassword;?></span>
				<?php } ?>
			</div>
      <div class="form-item">
        <label>Telefons</label>
        <input type="tel" name="phone">
      </div>
      <div class="form-item">
        <input class="user-add-btn save-btn" type="submit" name="employeeAdd" value="Saglabāt">
      </div>
    </form>
  </div>
</div>
<?php require_once('footer.php'); ?>