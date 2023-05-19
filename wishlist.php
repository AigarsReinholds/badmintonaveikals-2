<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
} 
$userId = $_SESSION['user_id'];
$wishlistQuery = "SELECT * FROM wishlist WHERE userId = '$userId'";
$wishlistResult = mysqli_query($conn, $wishlistQuery);
if(isset($_POST['submitToCart'])) {
  $productIds = $_POST['productId'];
  $userId = $_SESSION['user_id'];
  for($i = 0; $i < count($productIds); $i++) {
    $productId = $productIds[$i];
    $sql = "SELECT * FROM cart WHERE userId = '$userId' AND productId = '$productId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);
    if(mysqli_num_rows($result) == 0) {
      $sql = "INSERT INTO cart (userId, productId, productQty) VALUES ('$userId', '$productId', 1)";
      $insertResult = mysqli_query($conn, $sql);
      if($insertResult) {
        echo "<div class='cart-notification'>Produkts veiksmīgi pievienots grozam</div>";
      } else {
        echo "<div class='cart-notification'>Kļūda pievienojot produktu grozam</div>";
      }
    }
    else if(mysqli_num_rows($result) > 0){
      echo "<div class='cart-notification-failed'>Produkts jau ir pievienots grozam</div>";
    }
  }   
}
?>
<div class="container">
  <div class="product-data-wishlist">
    <h2 class="title">Vēlmju saraksts</h2>
    <?php
    $count = 0;
    if(mysqli_num_rows($wishlistResult) > 0) {
      while($row = mysqli_fetch_array($wishlistResult)) {
        $productId = $row['productId'];
        $productQuery = "SELECT * FROM product WHERE id = '$productId'";
        $productResult = mysqli_query($conn, $productQuery);
        $product = mysqli_fetch_array($productResult);
        $product['price'] = str_replace('.',',',$product['price']);
        if($product['discount'] != 0.00) {
          $product['discount'] = str_replace('.',',',$product['discount']);
        }
        if ($count % 3 == 0) {
    ?>
    <div class="row row-wishlist-products">
      <?php } $count++; ?>
      <div class="product-category">
        <form method="POST" action="">
          <div class="wishlist-product">
            <img class="product-category-img" src="assets/img/<?php echo $product['featuredImage'];?>" width="100px" height="100px">
            <a href="product-single?product=<?php echo $product['slug']?>"><h4 class="product-category-title"><?php echo $product['name'];?></h4></a>
            <div class="product-price">
              <?php if($product['discount'] != 0.00) { ?>
                <span class="regular-price product-price"><s><?php echo $product['price'];?>€</s></span>
                <span class="discount-price-active product-price"><?php echo $product['discount'];?>€</span>
              <?php } else { ?>
                <span class="product-price"><?php echo $product['price'];?>€</span>
              <?php } ?>
            </div>
            <input type="hidden" name="productId[]" value="<?php echo $product['id']; ?>">
            <div class="wishlist-functions">
              <div class="col">
                <button class="add-to-cart-btn" name="submitToCart">Pievienot grozam</button>
              </div>
          </div>    
        </form>
            <div class="col">
              <form method="POST" action="wishlist-product-delete.php">
                <input type="hidden" name="productId" value="<?php echo $product['id'];?>">
                <button class="delete-from-favourite-btn" type="submit" name="delete"><i class="fa-solid fa-x"></i>Izdzēst</button>
              </form>
            </div>
          </div>
      </div>
      <?php if ($count % 3 == 0) {  ?>
    </div>
    <?php
        }
      } 
    } else {
      echo "Vēlmju saraksts ir tukšs";
    }
    ?>
  </div>
</div>
</div>
<?php require_once('footer.php'); ?>