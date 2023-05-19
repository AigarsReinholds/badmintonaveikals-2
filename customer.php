<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) !== 'admin') {
  header("Location: login");
}
$sql = "SELECT * FROM user";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successMessageCustomer'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successMessageCustomer']; ?></span>
    <?php
      unset($_SESSION['successMessageCustomer']);
    }
    ?>
    <div class="container-inner-top">
      <div class="user-add">
        <a href="customer-add">Pievienot jaunu lietotāju</a>
      </div>
    </div>
    <h2>Lietotāju saraksts:</h2>
    <div class="table-user">
      <div class="table-header">
        <div class="table-product-header table-product-header-user-id">Lietotāja id</div>
        <div class="table-product-header">Lietotāja vārds</div>
        <div class="table-product-header">Lietotāja uzvārds</div>
        <div class="table-product-header"></div>
        <div class="table-product-header"></div> 
        <div class="table-product-header">Statuss</div>
      </div>
      <div class="table-body">
      <?php
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
      ?>
        <div class="table-row">
          <div class="table-col"><?php echo $row['id'];?></div>
          <div class="table-col"><?php echo $row['firstName'];?></div>
          <div class="table-col"><?php echo $row['lastName'];?></div>
          <div class="table-col"></div>
          <div class="table-col table-col-edit"><a href="customer-edit.php?id=<?php echo $row['id']; ?>">Rediģēt</a></div>
          <div class="table-col table-col-delete">
            <?php if($row['status'] == 1) { ?>
              <a href="customer-deactivate.php?id=<?php echo $row['id'];?>" onclick="confirmDeactivation(event, 'customer-deactivate.php?id=<?php echo $row['id'];?>')">Deaktivizēt</a>
              <form id="deactivateForm-<?php echo $row['id']; ?>" method="post" action="customer-deactivate.php">
                <input type="hidden" name="deactivate" value="true">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="confirmDeactivation" id="confirmDeactivationInput-<?php echo $row['id']; ?>" value="">
              </form>
            <?php } else { ?>
              <a href="customer-activate.php?id=<?php echo $row['id'];?>" onclick="confirmActivation(event, 'customer-activate.php?id=<?php echo $row['id'];?>')">Aktivizēt</a>
              <form id="activateForm-<?php echo $row['id']; ?>" method="post" action="customer-activate.php">
                <input type="hidden" name="activate" value="true">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="confirmActivation" id="confirmActivationInput-<?php echo $row['id']; ?>" value="">
              </form>
            <?php } ?>
          </div>
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