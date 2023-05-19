<?php require_once('header.php'); ?>
<div class="container container-forgot-passw">
  <form class="forgot-passw-form" method="post" action="send-message.php">
    <h1 class="form-title">Paroles atiestatīšana</h1>
    <?php if(isset($_SESSION['messageSuccess'])) { ?>
      <div class="form-item">
        <span class="success-message"><?php echo $_SESSION['messageSuccess']; ?></span>
      </div>
    <?php unset($_SESSION['messageSuccess']); } ?>
    <?php if(isset($_SESSION['messageFailed'])) { ?>
      <div class="form-item">
        <span class="error-message"><?php echo $_SESSION['messageFailed']; ?></span>
      </div>
    <?php unset($_SESSION['messageFailed']); } ?>
    <div class="form-item">
      <label class="form-item-label">E-pasts</label></br>
      <input class="form-item-input" type="email" name="email"></br>
      <?php if(isset($_SESSION['messageFieldEmpty'])) { ?>
        <span class="error-message"><?php echo $_SESSION['messageFieldEmpty']; ?></span>
      <?php unset($_SESSION['messageFieldEmpty']); } ?>
    </div>
    <div class="form-item">
      <input class="forgot-passw-btn" type="submit" name="forgotPassword" value="Nosūtīt ziņu">
    </div>
    <?php if(isset($_SESSION['userNotFound'])) { ?>
      <div class="form-item">
        <span class="error-message"><?php echo $_SESSION['userNotFound']; ?></span>
      </div>
    <?php unset($_SESSION['userNotFound']); } ?>
    <div class="row row-links">
      <div class="link-login">
        <p><a href="login">Pieslēgties</a></p>
      </div>
      <div class="link-registration">
        <p><a href="registration">Reģistrēties</a></p>
      </div>
    </div>
  </form>
</div>
<?php require_once('footer.php'); ?>