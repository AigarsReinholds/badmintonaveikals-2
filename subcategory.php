<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
$sql = "SELECT product_subcategory.*, product_category.name as categoryName FROM product_subcategory, product_category WHERE product_subcategory.categoryId = product_category.id ORDER BY product_subcategory.id ASC";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <div class="container-inner-top">
      <div class="subcategory-add">
        <a href="subcategory-add.php">Pievienot jaunu apakškategoriju</a>
      </div>
    </div>
    <h2>Produktu apakškategorijas:</h2>
    <div class="table-product">
      <div class="table-header">
        <div class="table-product-header">Apakškategorijas id</div>
        <div class="table-product-header">Apakškategorijas nosaukums</div>
        <div class="table-product-header">Apakškategorijas birka</div>
        <div class="table-product-header">Apakškategorijas attēls</div>
        <div class="table-product-header">Kategorijas nosaukums</div>
        <div class="table-product-header"></div>
        <div class="table-product-header"></div>
      </div>
      <?php 
      while($row = mysqli_fetch_assoc($result)) {
      ?>
      <div class="table-body">
        <div class="table-row">
          <div class="table-col"><?php echo $row['id']?></div>
          <div class="table-col"><?php echo $row['name']?></div>
          <div class="table-col"><?php echo $row['slug']?></div>
          <div class="table-col">
            <img src="../assets/img/<?php echo $row['image']?>" width="50px" height="50px">
          </div>
          <div class="table-col"><?php echo $row['categoryName']?></div>
          <div class="table-col table-col-edit"><a href="subcategory-edit?id=<?php echo $row['id']; ?>">Rediģēt</a></div>
          <div class="table-col table-col-delete">
              <a href="subcategory-delete.php?id=<?php echo $row['id'];?>" onclick="confirmDelete(event, 'subcategory-delete.php?id=<?php echo $row['id'];?>')">Dzēst</a>
              <form id="deleteForm-<?php echo $row['id']; ?>" method="post" action="subcategory-delete.php">
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