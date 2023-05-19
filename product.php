<?php require_once('header.php'); ?>
<!-- Produkti pec izveletes kategorijas -->
<?php
  $categorySlug = "";
  $subcategorySlug = "";
  if(isset($_GET['category'])) {
    $categorySlug = $_GET['category'];
  }
  if(isset($_GET['subcategory'])) {
    $subcategorySlug = $_GET['subcategory'];
  }
?>
<div class="container">
  <div class="product-list">
    <div class="link-path">
      <h4><a href="https://badmintonaveikals.shop/">Sākums</a>
      <span class="link-path-seperator">»</span>
      </h4>
      <h4><a href="product?category=<?php echo $categorySlug;?>"> <?php echo $categorySlug;?></a></h4>
      <span class="link-path-seperator">»</span>
      <h4><a href="product?category=<?php echo $categorySlug;?>&subcategory=<?php echo $subcategorySlug;?>"><?php echo $subcategorySlug;?></a></h4>
    </div>
    <h2>Produkti</h2>
    <form method="GET" action="">
      <select name="sortProducts" id="sortProducts" onchange="sortFilter()">
        <option value="default">Pēc noklusējuma</option>
        <option value="cheapest">Lētākais augšā</option>
        <option value="mostExpensive">Dārgākais augšā</option>
      </select>
    </form>
    <div class="product-page-products" id="productContainer">
    <?php
  if(isset($_GET['category']) && isset($_GET['subcategory'])) { 
    $categorySlug = $_GET['category'];
    $subcategorySlug = $_GET['subcategory'];
    $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug') AND subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug')";
    if(isset($_GET['sortProducts'])) {
      $sortOption = $_GET['sortProducts'];
      switch($sortOption) {
        case "default":
          break;
        case "cheapest":
          $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug') AND subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug') ORDER BY price ASC";
          break;
        case "mostExpensive":
          $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug') AND subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug') ORDER BY price DESC";
          break;
      }
    }
  }  
  else if(isset($_GET['category'])) { 
      $categorySlug = $_GET['category'];
      $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug')";
      if(isset($_GET['sortProducts'])) {
        $sortOption = $_GET['sortProducts'];
        switch($sortOption) {
          case "default":
            break;
          case "cheapest":
            $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug') ORDER BY price ASC";
            break;
          case "mostExpensive":
            $query = "SELECT * FROM product WHERE categoryId = (SELECT id FROM product_category WHERE slug = '$categorySlug') ORDER BY price DESC";
            break;
        }
      }
    }
    else if(isset($_GET['subcategory'])) {
      $subcategorySlug = $_GET['subcategory'];
      $query = "SELECT * FROM product WHERE subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug')";
      if(isset($_GET['sortProducts'])) {
        $sortOption = $_GET['sortProducts'];
        switch($sortOption) {
          case "default":
            break;
          case "cheapest":
            $query = "SELECT * FROM product WHERE subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug') ORDER BY price ASC";
            break;
          case "mostExpensive":
            $query = "SELECT * FROM product WHERE subcategoryId = (SELECT id FROM product_subcategory WHERE slug = '$subcategorySlug') ORDER BY price DESC";
            break;
        }
      }
    } else {
      $query = "SELECT * FROM product";
    }
      $result = mysqli_query($conn, $query);
      $count = 0;
      if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {  
          $row['price'] = str_replace('.',',',$row['price']);
          if($row['discount'] != 0.00) {
            $row['discount'] = str_replace('.',',',$row['discount']);
          }
          if ($count % 3 == 0) {
      ?>
      <div class="row row-products">
        <?php } $count++; ?>
        <div class="product-category" id="productPlace">
          <a href="product-single?product=<?php echo $row['slug'];?>">
          <img class="product-category-img" src="assets/img/<?php echo $row['featuredImage'];?>" width="100px" height="100px" alt="Produkta attēls">
          <h4 class="product-category-title"><?php echo $row['name'];?></h4>
          <div class="product-price">
            <?php
            if($row['discount'] != 0.00) {
              echo '<strike><span class="regular-price">'.$row['price'].' €</span></strike>';
              echo '<span class="discount-price">'.$row['discount'].' €</span>';
            } else {
              echo '<span>'.$row['price'].' €</span>';
            }
            ?>
          </div>
          <div class="stock">
            <?php if($row['qty'] >= 1) { ?>
              <span class="qty-status-stock">Noliktavā</span>
            <?php } else { ?>
              <span class="qty-status-stockout">Nav noliktavā</span>
            <?php } ?>
          </div>
          </a>
        </div>
        <?php if ($count % 3 == 0) {  ?>
      </div>
      <?php
      } 
    }
  } else { ?>
    <div class="product-category">
      <h4><?php echo "Produkti netika atrasti";?></h4>
    </div>
  <?php
  }
  ?>
  </div>
  </div>
</div>
</div> 
<?php require_once('footer.php'); ?>