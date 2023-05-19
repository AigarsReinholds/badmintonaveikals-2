<?php require_once('header.php'); ?>
<!-- Produktu apakskategorijas -->
<?php
  $categorySlug = "";
  $subcategorySlug = "";
  $categorySlug = $_GET['category'];
  $sql = "SELECT * FROM product_category WHERE slug = '$categorySlug'";
  $result = mysqli_query($conn, $sql);
  $row = mysqli_fetch_assoc($result);
  $categoryId = $row['id'];
?>
<div class="container">
  <div class="product-categories">
    <h2 class="categories-title">Katalogs</h2>
    <div class="link-path">
      <h4><a href="https://badmintonaveikals.shop/">Sākums</a>
      <span class="link-path-seperator">»</span>
      </h4>
      <h4><a href="product?category=<?php echo $categorySlug; ?>"> <?php echo $categorySlug; ?></a></h4>
    </div>
    <?php
      if(isset($_GET['category'])) {
      $sql = "SELECT * FROM product_subcategory WHERE categoryId = '$categoryId'";
      $result = mysqli_query($conn, $sql);
      $count = 0;
      if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          if ($count % 4 == 0) {
    ?>
    <div class="row row-product-categories">
      <?php } $count++; ?>
      <div class="product-category">
        <a href="product?category=<?php echo $categorySlug; ?>&subcategory=<?php echo $row['slug']; ?>">
        <img class="product-category-img" src="assets/img/<?php echo $row['image'];?>" width="150px" height="150px">
        <h4 class="product-category-title"><?php echo $row['name'];?></h4>
        </a>
      </div>
      <?php if ($count % 4 == 0) {  ?>
    </div>
    <?php
            } 
          } 
        }
      } 
   ?>  
  </div>
</div>
</div>
<?php require_once('footer.php'); ?>