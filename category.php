<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
$sql = "SELECT * FROM product_category";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <div class="container-inner-top">
      <div class="category-add">
        <a href="category-add">Pievienot jaunu kategoriju</a>
      </div>
    </div>
    <h2>Produktu kategorijas:</h2>
    <div class="table-product">
      <div class="table-header">
        <div class="table-product-header">Kategorijas id</div>
        <div class="table-product-header">Kategorijas nosaukums</div>
        <div class="table-product-header">Kategorijas birka</div>
        <div class="table-product-header">Kategorijas attēls</div>
        <div class="table-product-header"></div>
        <div class="table-product-header"></div>
      </div>
      <?php 
      foreach($result as $row) {
      ?>
      <div class="table-body">
        <div class="table-row">
          <div class="table-col"><?php echo $row['id']?></div>
          <div class="table-col"><?php echo $row['name']?></div>
          <div class="table-col"><?php echo $row['slug']?></div>
          <div class="table-col">
            <img src="../assets/img/<?php echo $row['image']?>" width="50px" height="50px">
          </div>
          <div class="table-col table-col-edit"><a href="category-edit?id=<?php echo $row['id']; ?>">Rediģēt</a></div>
          <div class="table-col table-col-delete">
            <a href="category-delete.php?id=<?php echo $row['id'];?>" onclick="confirmDelete(event, 'category-delete.php?id=<?php echo $row['id'];?>')">Dzēst</a>
            <form id="deleteForm-<?php echo $row['id'];?>" method="post" action="category-delete.php">
              <input type="hidden" name="delete" value="true">
              <input type="hidden" name="id" value="<?php echo $row['id'];?>">
              <input type="hidden" name="confirmDelete" id="confirmDeleteInput-<?php echo $row['id']; ?>" value="">
            </form>
          </div>
        </div>
      </div>
      <?php 
      }
      ?>
    </div>  
  </div>
</div>
<?php require_once('footer.php'); ?>