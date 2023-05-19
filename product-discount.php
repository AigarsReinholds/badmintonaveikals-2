<?php require_once('header.php'); ?>
<div class="container">
  <div class="">
  <h2>Produkti ar atlaidi</h2>
    <form method="GET" action="">
      <select name="sortProducts" id="sortProducts" onchange="sortFilter()">
        <option value="default">Pēc noklusējuma</option>
        <option value="cheapest">Lētākais augšā</option>
        <option value="mostExpensive">Dārgākais augšā</option>
      </select>
    </form>
    <div class="product-page-products" id="productContainer">
      <?php 
      if(isset($_GET['sortProducts'])) {
        $sortOption = $_GET['sortProducts'];
        $query = "SELECT * FROM product WHERE discount != 0";
        switch($sortOption) {
          case "default":
            break;
          case "cheapest":
            $query = "SELECT * FROM product WHERE discount != 0 ORDER BY discount ASC, price ASC";
            break;
          case "mostExpensive":
            $query = "SELECT * FROM product WHERE discount != 0 ORDER BY discount DESC, price DESC";
            break;
        }
        $result = mysqli_query($conn, $query);
      } else {
        $query = "SELECT * FROM product WHERE discount != 0";
        $result = mysqli_query($conn, $query);
      }
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
              echo '<strike><span class="regular-price">'.$row['price'].'€</span></strike>';
              echo '<span class="discount-price">'.$row['discount'].' €</span>';
            } else {
              echo '<span>'.$row['price'].' €</span>';
            }
            ?>
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