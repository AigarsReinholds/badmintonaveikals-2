<?php
ob_start();
session_start();
include("includes/config.php");
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
    <div class="container container-forgot-passw">
      <form class="forgot-passw-form" method="post" action="send-message.php">
      <h1 class="form-title">Paroles atiestatīšana</h1>
        <div class="form-item">
          <?php if(isset($_SESSION['messageSuccess'])) { ?>
            <span class="success-message"><?php echo $_SESSION['messageSuccess']; ?></span>
          <?php unset($_SESSION['messageSuccess']); } ?>
          <?php if(isset($_SESSION['messageFailed'])) { ?>
            <span class="error-message"><?php echo $_SESSION['messageFailed']; ?></span>
          <?php unset($_SESSION['messageFailed']); } ?>
        </div>
        <div class="form-item">
          <label class="form-item-label">E-pasts</label></br>
          <input class="form-item-input" type="email" name="email">
        </div>
        <div class="form-item">
          <input class="forgot-passw-btn" type="submit" name="forgotPassword" value="Nosūtīt ziņu">
        </div>
        <div class="form-item">
          <?php if(isset($_SESSION['messageFieldEmpty'])) { ?>
            <span class="error"><?php echo $_SESSION['messageFieldEmpty']; ?></span>
          <?php unset($_SESSION['messageFieldEmpty']); } ?>
          <?php if(isset($_SESSION['userNotFound'])) { ?>
            <span class="error"><?php echo $_SESSION['userNotFound']; ?></span>
          <?php unset($_SESSION['userNotFound']); } ?>
        </div>
      </form>
    </div>
  </body>
</html>