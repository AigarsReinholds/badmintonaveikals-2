<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT * FROM message WHERE id = '$id'";
  $result = mysqli_query($conn, $sql);
}
?>
<div class="container">
  <div class="container-inner">
    <div class="message-single">
  <?php
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
      ?>
    <div class="row">
      <div class="col"><span class="message-id">Ziņas id</span></div>
      <div class="col"><?php echo $row['id'];?></div>
    </div>
    <div class="row">
      <div class="col"><span class="message-name">Sūtītāja vārds</span></div>
      <div class="col"><?php echo $row['name'];?></div>
    </div>
    <div class="row">
      <div class="col"><span class="message-email">Sūtītāja e-pasts</span></div>
      <div class="col"><?php echo $row['email'];?></div>
    </div>
    <div class="row">
      <div class="col"><span class="message-subject">Ziņas virsraksts</span></div>
      <div class="col"><?php echo $row['subject'];?></div>
    </div>
    <div class="col"><span class="message-text">Ziņa</span></div>
    <div class="col"><?php echo $row['message'];?></div>
      <?php 
        }
      }
      ?>
    </div>
  </div>
</div>
<?php require_once('footer.php'); ?>