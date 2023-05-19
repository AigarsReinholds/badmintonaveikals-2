<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
$sql = "SELECT * FROM message";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="table-wrapper">
  <div class="table-user">
    <div class="table-header">
      <div class="table-product-header table-product-header-user-id">Ziņas id</div>
      <div class="table-product-header">Sūtītāja vārds</div>
      <div class="table-product-header">Sūtītāja e-pasts</div>
      <div class="table-product-header">Virsraksts</div>
      <div class="table-product-header">Ziņa</div>
      <div class="table-product-header"></div>
    </div>
    <div class="table-body">
      <?php
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="table-row">
        <div class="table-col"><?php echo $row['id'];?></div>
        <div class="table-col"><?php echo $row['name'];?></div>
        <div class="table-col"><?php echo $row['email'];?></div>
        <div class="table-col"><?php echo $row['subject'];?></div>
        <div class="table-col"><?php echo $row['message'];?></div>
        <div class="table-col table-col-view"><a href="message-single?id=<?php echo $row['id'];?>">Skatīt</a></div>
      </div>
      <?php 
        }
      }
      ?>
    </div>  
  </div>
  </div>
</div>
<?php require_once('footer.php'); ?>