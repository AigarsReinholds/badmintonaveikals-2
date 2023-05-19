<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id']) == "" || isset($_SESSION['user_role']) !== 'admin')  {
  header("Location: login");
}
$sql = "SELECT * FROM adminuser WHERE role = 'employee'";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <?php 
    if(isset($_SESSION['successMessageEmployee'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['successMessageEmployee']; ?></span>
    <?php
      unset($_SESSION['successMessageEmployee']);
    }
    ?>
    <div class="container-inner-top">
      <div class="user-add">
        <a href="employee-add">Pievienot jaunu darbinieku</a>
      </div>
    </div>
    <h2>Darbinieku saraksts:</h2>
    <div class="table-wrapper">
    <div class="table-user">
      <div class="table-header">
        <div class="table-product-header table-product-header-user-id">Darbinieka id</div>
        <div class="table-product-header">Darbinieka vārds</div>
        <div class="table-product-header">Darbinieka uzvārds</div>
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
          <div class="table-col table-col-edit"><a href="employee-edit.php?id=<?php echo $row['id']; ?>">Rediģēt</a></div>
          <div class="table-col table-col-status">
            <?php if($row['status'] == 1) { ?>
              <a class="deactivate-link" href="employee-deactivate.php?id=<?php echo $row['id'];?>" onclick="confirmDeactivation(event, 'employee-deactivate.php?id=<?php echo $row['id'];?>')">Deaktivizēt</a>
              <form id="deactivateForm-<?php echo $row['id']; ?>" method="post" action="employee-deactivate.php">
                <input type="hidden" name="deactivate" value="true">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="confirmDeactivation" id="confirmDeactivationInput-<?php echo $row['id']; ?>" value="">
              </form>
            <?php } else { ?>
              <a class="activate-link" href="employee-activate.php?id=<?php echo $row['id'];?>" onclick="confirmActivation(event, 'employee-activate.php?id=<?php echo $row['id'];?>')">Aktivizēt</a>
              <form id="activateForm-<?php echo $row['id']; ?>" method="post" action="employee-activate.php">
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
</div>
<?php require_once('footer.php'); ?>