<?php require_once('header.php'); ?>
<?php
if(isset($_GET['product'])) {
  $productSlug = $_GET['product'];
  $sql = "SELECT * FROM product WHERE slug = '$productSlug'";
  $result = mysqli_query($conn, $sql); 
  $rowProduct = mysqli_fetch_array($result);
  $id = $rowProduct['id'];
  $categoryId = $rowProduct['categoryId'];
  $categorySql = "SELECT id, name, slug FROM product_category WHERE id = '$categoryId'";
  $categoryResult = mysqli_query($conn, $categorySql);
  $rowCategory = mysqli_fetch_array($categoryResult);
  $galleryImageSql = "SELECT * FROM product_image WHERE productId = '$id'";
  $galleryResult = mysqli_query($conn, $galleryImageSql);
  $productSizeSql = "SELECT * FROM product_size WHERE productId = '$id'";
  $productSizeResult = mysqli_query($conn, $productSizeSql);
  $sizes = array();
  while($sizeRow = mysqli_fetch_array($productSizeResult)) {
    $sizes[] = $sizeRow['sizeValue'];
  }
  if(isset($_SESSION['user_id'])) {
    if(isset($_POST['submitToWishlist'])) {
      $productIds = $_POST['productId'];
      $userId = $_SESSION['user_id'];
      foreach($productIds as $i => $productId) {  
        $query = "SELECT * FROM wishlist WHERE userId = '$userId' AND productId = '$productId'";
        $result = mysqli_query($conn, $query);
        if(mysqli_num_rows($result) == 0) {
          $insertQuery = "INSERT INTO wishlist (userId, productId) VALUES ('$userId', '$productId')";
          $insertResult = mysqli_query($conn, $insertQuery);
          if($insertResult) {
            echo "<div class='cart-notification'>Produkts veiksmīgi pievienots vēlmju sarakstam</div>";
          } else {
            echo "<div class='cart-notification-failed'>Kļūda pievienojot produktu vēlmju sarakstam</div>";
          }
        }
        else if(mysqli_num_rows($result) > 0) {
          echo "<div class='cart-notification-failed'>Produkts jau ir pievienots vēlmju sarakstam</div>";
        }
      }
    }
  }
    if(isset($_POST['submitToCart'])) {
      $productIds = $_POST['productId'];
      $qtys = $_POST['productQty'];
      $userId = $_SESSION['user_id'];
      if($_SESSION['user_id'] == "") {
        echo "<div class='cart-notification-failed'>Lai produktu pievienotu grozam ir jābūt reģistrētam</div>";
      } else {
        for($i = 0; $i < count($productIds); $i++) {
          $productId = $productIds[$i];
          $qty = $qtys[$i];
          $sql = "SELECT * FROM cart WHERE userId = '$userId' AND productId = '$productId'";
          $result = mysqli_query($conn, $sql);
          $row = mysqli_fetch_array($result);
          if(mysqli_num_rows($result) == 0) {
            if($rowProduct['qty'] == 0) {
              echo "<div class='cart-notification-qty'>Produkts pašlaik nav pieejams</div>";
            }
            else if($rowProduct['qty'] < $qty) {
              echo "<div class='cart-notification-qty'>Noliktavā nav šāds produkta daudzums</div>";
            } else {
              $sql = "INSERT INTO cart (userId, productId, productQty) VALUES ('$userId', '$productId', $qty)";
              $insertResult = mysqli_query($conn, $sql);
              if($insertResult) {
                echo "<div class='cart-notification'>Produkts veiksmīgi pievienots grozam</div>";
              } else {
                echo "<div class='cart-notification-failed'>Kļūda pievienojot produktu grozam</div>";
              }
            }
          }
          else if(mysqli_num_rows($result) > 0) {
            echo "<div class='cart-notification-failed'>Produkts jau ir pievienots grozam</div>";
          }
        }
        if(isset($_POST['size'])) {
          $_SESSION['selectedSize'][$productId] = $_POST['size'];
        }
      } 
    }
?>
<div class="container">
  <div class="product-single product-data">
    <form method="POST" action="">
    <div class="row">
      <div class="col col-single-product-featured-image">
        <img id="featuredImage" class="single-product-featured-image" src="assets/img/<?php echo $rowProduct['featuredImage']; ?>" alt="Produkta attēls" width="150px" height="auto" onclick="galleryOpen()">
        <div class="singe-product-image-gallery">
          <?php
          $images = []; 
          while($galleryRow = mysqli_fetch_array($galleryResult)) {
            $galleryImage = $galleryRow['image'];
            echo '<img class="thumbnail" src="assets/img/product/'.$galleryImage.'" alt="Produkta galerijas attēls" width="150px" height="auto" onclick="updateFeaturedImage()">';
          }
          ?>
        </div>
        <div id="galleryBox" class="gallery-box" style="display: none;">
          <div class="gallery-box-container">
            <span id="imageCount" class="gallery-number"></span>
            <span class="gallery-close" onclick="galleryClose()">×</span>
            <img id="imageContainer" style="display: none;">
            <a id="previousButton" class="previous" onclick="previousButtonClick()"><</a>
            <a id="nextButton" class="next" onclick="nextButtonClick()">></a>
          </div>
        </div>
      </div>
      <div class="col col-single-product-summary">
        <h1 class="product-title"><?php echo $rowProduct['name'];?></h1>
        <div class="product-price">
            <?php
            $rowProduct['price'] = str_replace('.',',',$rowProduct['price']);
            if($rowProduct['discount'] != 0.00) {
              $rowProduct['discount'] = str_replace('.',',',$rowProduct['discount']);
            }
            if($rowProduct['discount'] != 0.00) {
              echo '<strike><span class="regular-price">'.$rowProduct['price'].' €</span></strike>';
              echo '<span class="discount-price">'.$rowProduct['discount'].' €</span>';
            } else {
              echo '<span>'.$rowProduct['price'].' €</span>';
            }
            ?>
        </div>
        <?php if ($sizes != null) {?>
        <div class="row row-size">
          <div class="col">
            <span>Izmērs</span>
          </div>
          <div class="col col-size">
            <select name="size">
              <?php foreach($sizes as $size) {?>
                <option value="<?php echo $size;?>"><?php echo $size;?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <?php } ?>
        <div class="row">
          <div class="col">
            <input type="hidden" name="productId[]" value="<?php echo $rowProduct['id']; ?>">
            <div class="qty-input-field">
              <button class="decrement-btn">-</button>
              <input type="number" class="input-qty" value="1" name="productQty[]" size="5">
              <button class="increment-btn">+</button>
            </div>
          </div>
        </div>
        <div class="row row-functions">
          <div class="col col-cart-btn">
            <button class="add-to-cart-btn" name="submitToCart">Pievienot grozam</button>
          </div>
          <div class="col col-favourite-btn">
          <?php
          if(isset($_SESSION['user_id'])=="") { ?>
            <button class="favourite-btn-disabled" disabled>Pievienot favorītiem</button>
            <div class="favourite-btn-disabled-notification">
              <span class="notification">Lai produktu pievienotu favorītiem ir jābūt reģistrētam</span>
            </div>
          <?php
          } else { 
          ?>
            <button class="favourite-btn" name="submitToWishlist">Pievienot favorītiem</button>
          <?php } ?>
          </div>
        </div>
        <div>
          <?php if($rowProduct['qty'] >= 1) { ?>
            <span class="qty-status-stock">Noliktavā</span>
          <?php } else { ?>
            <span class="qty-status-stockout">Nav noliktavā</span>
          <?php } ?>
        </div>
        <div class="single-product-category">
          <span class="">Kategorija:
            <a href="product?category=<?php echo $rowCategory['slug'];?>"><?php echo $rowCategory['name']; ?></a>
          </span>  
        </div>
        </hr>
      </div>
      </div>
      <div class="product-description">
        <p class="product-description-title">Apraksts</p>
        <p class="single-product-description"><?php echo $rowProduct['description']; ?></p>
        <p><strong>Piegādes veidi un cenas: </strong></p>
        <ul>
          <li>Saņemt preci veikalā – <strong>BEZMAKSAS</strong></li>
          <li>Saņemt preci ar piegādi uz mājām – <strong>9,99 EUR</strong></li>
        </ul>
        <p>Uzzināt vairāk par apmaksas veidiem: <a href="https://badmintonaveikals.shop/payment-method">APMAKSAS VEIDI</a></p>
        <p>Uzzināt vairāk par garantijas nosacījumiem un atgriešanas iespējām: <a href="https://badmintonaveikals.shop/garanties">GARANTIJA UN ATGRIEŠANA</a></p>
      </div>
    </form>
    </div>
  </div>
</div>
<?php
}   
?>
<?php require_once('footer.php'); ?>