<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login.php");
}
$sql = "SELECT product.*, product_category.name AS categoryName FROM product, product_category WHERE product.categoryId = product_category.id ";
$result = mysqli_query($conn, $sql);
$row = mysqli_num_rows($result);
if(isset($_GET['filterCategory'])) {
  $filterOption = $_GET['filterCategory'];
  for($i=1; $i<$row; $i++) {
    switch($filterOption) {
      case "default":
        break;
      case ($i):
        $sql = "SELECT product.*, product_category.name AS categoryName FROM product_category JOIN product ON product_category.id = product.categoryId WHERE product_category.id = $i";
        break;
    }      
  }
}
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <div class="container-inner-top">
      <div class="product-add">
        <a href="product-add">Pievienot jaunu produktu</a>
      </div>
    </div>
    <!-- search bar -->
    <div class="search-box" id="searchBox">
      <form class="search-bar">
        <div class="search-bar-input">
          <input id="searchWord" type="text" placeholder="Meklēt" onkeyup="search()">
        </div>  
      </form>
    <div id="results" class="search-bar-results"></div>
    </div>
    <h2>Esošie produkti:</h2>
    <form method="GET">
      <button id="filterCategory" class="save-btn">Piemērot</button>
      <select class="form-select" name="filterCategory" id="categorySelect">
        <option selected>Izvēlies kategoriju</option>
        <?php
        //izvada kategorijas 
          $sqlSort = "SELECT * FROM product_category";
          $resultSort = mysqli_query($conn, $sqlSort);
          foreach($resultSort as $rowSort) {
        ?>
        <option value="<?php echo $rowSort['id'];?>"
        <?php
          if(isset($_GET['filterCategory']) && $_GET['filterCategory'] == $rowSort['id']) echo 'selected'; 
          ?>>
          <?php echo $rowSort['name'];?>
        </option>
        <?php    
          }
        ?>
      </select></br>
    </form>
    <div class="container-table">
      <div class="table-product" id="tableProduct">
        <div class="table-header">
          <div class="table-product-header table-product-header-product-id">Produkta id</div>
          <div class="table-product-header">Produkta attēls</div>
          <div class="table-product-header table-product-header-product-name">Produkta nosaukums</div>
          <div class="table-product-header table-product-header-product-stock">Noliktavā</div>
          <div class="table-product-header">Produkta cena</div>
          <div class="table-product-header">Produkta cena ar atlaidi</div>
          <div class="table-product-header table-product-header-product-category">Produkta kategorija</div>
          <div class="table-product-header"></div>
          <div class="table-product-header"></div> 
          <div class="table-product-header"></div>
        </div>
        <div class="table-body">
        <?php
        if (mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
        ?>
          <div class="table-row">
            <div class="table-col table-col-product-id"><?php echo $row['id'];?></div>
            <div class="table-col">
              <img src="../assets/img/<?php echo $row['featuredImage'];?>" width="50px" height="50px">
            </div>
            <div class="table-col" id="tableColProductName"><?php echo $row['name'];?></div>
            <div class="table-col">
              <?php if($row['qty'] >= 1) { ?>
                <span class="qty-status-stock">Ir noliktavā</span>
              <?php } else { ?>
                <span class="qty-status-stockout">Nav noliktavā</span>
              <?php } ?>
            </div>
            <div class="table-col">
              <?php if($row['discount'] != 0.00) { ?>
                <span class="regular-price"><?php echo $row['price'];?> €</span>
              <?php } else { ?>
                <span class=""><?php echo $row['price'];?> €</span>
              <?php } ?>
            </div>
            <div class="table-col">
              <?php if($row['discount'] == 0.00) { ?>
                <span class="discount-price-unactive"><?php echo $row['discount'];?> €</span>
              <?php } else { ?>
                <span class="discount-price-active"><?php echo $row['discount'];?> €</span>
              <?php } ?>
            </div>
            <div class="table-col"><?php echo $row['categoryName'];?></div>
            <div class="table-col"><a target="_blank" href="../product-single?product=<?php echo $row['slug']?>"><i class="fa-solid fa-up-right-from-square"></i></a></div>
            <div class="table-col table-col-edit"><a href="product-edit?id=<?php echo $row['id']; ?>">Rediģēt</a></div>
            <div class="table-col table-col-delete">
              <a href="product-delete.php?id=<?php echo $row['id'];?>" onclick="confirmDelete(event, 'product-delete.php?id=<?php echo $row['id'];?>')">Dzēst</a>
              <form id="deleteForm-<?php echo $row['id']; ?>" method="post" action="product-delete.php">
                <input type="hidden" name="delete" value="true">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="confirmDelete" id="confirmDeleteInput-<?php echo $row['id']; ?>" value="">
              </form>
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