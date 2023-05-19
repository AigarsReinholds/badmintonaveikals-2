<?php 
require_once('header.php');
$error = false;
if(isset($_POST['register'])) {
  $firstname = mysqli_real_escape_string($conn, $_POST['firstname']);
  $lastname = mysqli_real_escape_string($conn, $_POST['lastname']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $password = mysqli_real_escape_string($conn, $_POST['password']);
	$cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
	$sqlSelect = "SELECT * FROM user WHERE email = '$email'";
	$resultSelect = mysqli_query($conn, $sqlSelect);
	if(empty($firstname)) {
		$error = true;
		$nameError = "Ievadiet vārdu";
	}
	else if(!preg_match("/^\p{L}+$/u", $firstname)) {
		$error = true;
		$nameError = "Lauks nav derīgs";
	}
	else if (empty($lastname)) {
		$error = true;
		$surnameError = "Ievadiet uzvārdu";
	}
	else if(!preg_match("/^\p{L}+$/u", $lastname)) {
		$error = true;
		$surnameError = "Lauks nav derīgs";
	}
	else if (empty($email)) {
		$error = true;
		$emailError = "Ievadiet e-pasta adresi";
	}
	else if (empty($password)) {
		$error = true;
		$errorPassword = "Ievadiet paroli";
	}
	else if(strlen($password) < 8) {
		$error = true;
		$errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
	}
  else if(!preg_match('/[A-Z]/', $password)){
    $error = true;
    $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
  }
	else if (empty($cpassword)) {
		$error = true;
		$errorConfirmPassword = "Ievadiet paroli atkārtoti";
	}
	else if($password != $cpassword) {
		$error = true;
		$errorConfirmPassword = "Parolēm ir jāsakrīt";
	}
	else if (mysqli_num_rows($resultSelect) > 0) {
		$rowUser = mysqli_fetch_assoc($resultSelect);
		$error = true;
		$errorEmailExists = "Lietotājs ar šādu e-pastu jau pastāv";
	}
	else {
	if(!$error) {
		$sql = "INSERT INTO user (firstname, lastname, email, password, registeredAt, lastLogin, status)
    VALUES ('$firstname', '$lastname', '$email', MD5('$password'), CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00'), CONVERT_TZ(CURRENT_TIMESTAMP,'+00:00','+03:00'), 1)";
		$result = mysqli_query($conn, $sql);
		if($result) {
			$_SESSION['successRegistration'] = "Jūs esat veiksmīgi reģistrējies!";
			header("Location: https://badmintonaveikals.shop/");
		}	else {
			$errorMessage = "Lauki nav pareizi aizpildīti";
			}
		}
	}
}
?>
<div class="container">
  <form class="registration-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <div class="registration-form-inner">
      <h1>Reģistrēties</h1>
    </div>
		<div class="registration-message">
			<?php if (isset($message)) { ?>
				<span class="successful-message"><?php echo $message; ?></span></br>
		 	<?php } ?>
			<?php if (isset($errorMessage)) { ?>
				<span class="error-message"> <?php echo $errorMessage; ?></span></br>
			<?php } ?>
		</div>
		<?php if(isset($errorEmailExists)) {?>
			<span class="error-message"> <?php echo $errorEmailExists; ?></span></br>
		<?php } ?>
		<div class="form-item">	
			<label>Vārds</label></br>
			<input type="text" name="firstname" value="<?php if(isset($_POST['firstname'])) {echo $_POST['firstname'];}?>"></br>
			<?php if(isset($nameError)) { ?>
				<span class="error-message"><?php echo $nameError; ?></span>
			<?php } ?>
		</div>
		<div class="form-item">	
			<label>Uzvārds</label></br>
			<input type="text" name="lastname" value="<?php if(isset($_POST['lastname'])) {echo $_POST['lastname'];}?>"></br>
			<?php if(isset($surnameError)) { ?>
				<span class="error-message"><?php echo $surnameError; ?></span>
			<?php } ?>
		</div>
		<div class="form-item">	
			<label>E-pasts</label></br>
			<input type="email" name="email" value="<?php if(isset($_POST['email'])) {echo $_POST['email'];}?>"></br>
			<?php if(isset($emailError)) { ?>
				<span class="error-message"><?php echo $emailError; ?></span>
			<?php } ?>
		</div>
		<div class="form-item">
			<label>Parole</label></br>
			<input id="password" type="password" name="password" value="<?php if(isset($_POST['password'])) {echo $_POST['password'];}?>">
			<span id="togglePassword" class="toggle-password">
       	<i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
     	</span></br>
    	<?php if(isset($errorPassword)) { ?>
				<span class="error-message"><?php echo $errorPassword; ?></span>
			<?php } ?>
		</div>
		<div class="form-item">
			<label>Parole atkārtoti</label></br>
			<input id="cpassword" type="password" name="cpassword" value="<?php if(isset($_POST['cpassword'])) {echo $_POST['cpassword'];}?>">
			<span id="toggleCPassword" class="toggle-password">
        <i class="fa fa-eye-slash" onclick="togglePassword('cpassword', 'toggleCPassword')"></i>
      </span></br>
			<?php if(isset($errorConfirmPassword)) {  ?>
				<span class="error-message"> <?php echo $errorConfirmPassword;?></span>
			<?php } ?>
		</div>
		<div class="form-item">
      <input class="register-submit-btn" type="submit" name="register" value="Reģistrēties">
		</div>
    <div class="link-login">
      <p>Jums jau ir profils? <a href="login">Pieslēgties</a></p>
    </div>      
  </form>  
</div>
<?php require_once('footer.php'); ?>
