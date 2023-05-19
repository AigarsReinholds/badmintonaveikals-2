<?php require_once('header.php'); ?>
<div class="container">
  <div class="contact-us-section" id="contactSection">
    <h1 class="title">Sazinies ar mums!</h1>
      <?php if(isset($_SESSION['messageSuccess'])) { ?>
        <span class="success-message"><?php echo $_SESSION['messageSuccess']; ?></span>
      <?php unset($_SESSION['messageSuccess']); } ?>
      <?php if(isset($_SESSION['messageFailed'])) { ?>
        <span class="error-message"><?php echo $_SESSION['messageFailed']; ?></span>
      <?php unset($_SESSION['messageFailed']); } ?>
      <?php if(isset($_SESSION['messageFieldEmpty'])) { ?>
        <span class="error-message"><?php echo $_SESSION['messageFieldEmpty']; ?></span>
      <?php unset($_SESSION['messageFieldEmpty']); } ?>
      <?php if(isset($errorEmpty)) { ?><span class="error-message"><?php echo $errorEmpty; ?></span><?php } ?>
    <form id="messageContactform" class="contactform" method="POST" action="send-message.php">
      <div class="form-item contactform-item">
        <label class="contactform-label">Vārds <span class="required-label">*</span></label>
        <input class="input-name" type="text" name="name" value="<?php if(isset($name)) { echo $name; }?>"></br>
      </div>
      <div class="form-item contactform-item">
        <label class="contactform-label">E-pasts <span class="required-label">*</span></label>
        <input class="input-email" type="email" name="email" value="<?php if(isset($email)) { echo $email; }?>"></br>
      </div>
      <div class="form-item contactform-item">
        <label class="contactform-label">Temats <span class="required-label">*</span></label>
        <input class="input-subject" type="text" name="subject" value="<?php if(isset($subject)) { echo $subject; }?>"></br>
      </div>     
      <div class="form-item contactform-item">
        <label class="contactform-label">Ziņa <span class="required-label">*</span></label>
        <textarea class="input-message" name="message" rows="10" cols="75"><?php if(isset($message)) { echo $message; }?></textarea>
      </div>
      <div class="form-item">
        <button id="sendMessage" class="message-btn" type="submit" name="sendMessage">Nosūtīt</button>
      </div>
    </form>
  </div>
</div>
<?php require_once('footer.php'); ?>