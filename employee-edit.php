<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) != 'admin') {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM adminuser WHERE id = '$id' AND role = 'employee'";
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if($count > 0) { 
    $row = mysqli_fetch_array($result);
    $id = $row['id'];
    $firstName = $row['firstName'];
    $lastName = $row['lastName'];
    $email = $row['email'];
    $password = $row['password'];
    $phone = $row['phone'];
  } else {
    header("Location: employee");
  } 
}
if(isset($_POST['employeeEdit'])) {
  $firstName = $_POST['firstName'];
  $lastName = $_POST['lastName'];
  $password = $_POST['password'];
  if(strlen($password) < 8) {
    $errorPassword = "Parolei ir jāsatur vismaz 8 simboli";
  } 
  else if(!preg_match('/[A-Z]/', $password)){
    $errorPassword = "Parolei ir jāsatur vismaz viens lielais burts";
  } else {
    $sql = "UPDATE adminuser SET firstName='$firstName', lastName='$lastName', password=MD5('$password') WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result) {
      $_SESSION['successEditMessageEmployee'] = 'Darbinieka informācija veiksmīgi rediģēta';
      header("Location: employee-edit?id=$id");
      exit;
    } else {
      echo "Kļūda rediģējot darbinieka datus";
    }
  }
}
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successEditMessageEmployee'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successEditMessageEmployee']; ?></span>
    <?php
      unset($_SESSION['successEditMessageEmployee']);
    }
    ?>
    <div class="container-inner-top">
      <div class="product-edit">
        <a href="employee-add">Pievienot jaunu darbinieku</a>
      </div>
    </div>
    <h2>Rediģēt darbinieka datus:</h2>
    <form class="employee-edit-form employee-form" method="post" action="">
      <div class="form-item">
        <label>Vārds</label>
        <input class="product-name-input" type="text" name="firstName" value="<?php echo $firstName;?>">
      </div>
      <div class="form-item">
        <label>Uzvārds</label>
        <input class="product-name-input" type="text" name="lastName" value="<?php echo $lastName;?>">
      </div>
      <div class="form-item">
        <label>E-pasts</label>
        <input class="product-email-input" type="email" name="email" value="<?php echo $email;?>" disabled>
      </div>
      <div class="form-item">
        <label>Parole</label>
        <input id="password" class="product-password-input" type="password" name="password">
        <span id="togglePassword" class="toggle-password">
          <i class="fa fa-eye-slash" onclick="togglePassword('password', 'togglePassword')"></i>
        </span></br>
        <?php if(isset($errorPassword)) { ?>
        <span class="error-message"><?php echo $errorPassword; ?></span>
        <?php } ?>
      </div>
      <div class="form-item">
        <label>Telefons</label>
        <input type="tel" value="<?php echo $phone; ?>">
      </div>
      <div class="form-item">
        <input class="user-edit-btn save-btn" type="submit" name="employeeEdit" value="Saglabāt">
      </div>
    </form>
  </div>
</div>
<?php require_once('footer.php'); ?>
