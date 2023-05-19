<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
$userId = $_SESSION['user_id'];
$sql = "SELECT * FROM cart WHERE userId = '$userId'";
$result = mysqli_query($conn, $sql);
if(isset($_SESSION['user_id'])) {
  if(isset($_POST['updateTotalQty'])) {
    $productIds = $_POST['productId'];
    if(!is_array($productIds)) {
      $productIds = array($productIds);
    }
    $qtys = $_POST['productQty'];
    //$quantites = array(); // Masivs daudzuma saglabasanai
    foreach($productIds as $i => $productId) {
      $productId = $productIds[$i];
      $qty = intval($qtys[$i]);
      $productSql = "SELECT * FROM product WHERE id = '$productId'";
      $productResult = mysqli_query($conn, $productSql);
      $rowProduct = mysqli_fetch_assoc($productResult);
      //$quantites[$productId] = $qty; // Saglaba daudzumu masiva
      if($rowProduct['qty'] < $qty) {
        $_SESSION['errorCartQtyUpdated'] = 'Noliktavā nav šāds produkta daudzums';
      } else {
        $sql = "UPDATE cart SET productQty = '$qty' WHERE userId = '$userId' AND productId = '$productId'";
        $updateResult = mysqli_query($conn, $sql);
        if ($updateResult) {
          $_SESSION['successCartQtyUpdated'] = 'Produkta daudzums veiksmīgi izmainīts grozā';
        } else {
          $_SESSION['errorCartQtyUpdated'] = 'Kļūda mainot produkta daudzumu grozā';
        }
      }
    }
    header("Location: cart");
    exit;
  } 
  if(isset($_POST['delete'])) {
    $productId = $_POST['productId'];
    $sql = "DELETE FROM cart WHERE userId = '$userId' AND productId = '$productId'";
    $result = mysqli_query($conn, $sql);
    unset($_SESSION['cart'][$productId]);
    unset($_SESSION['selectedSize'][$productId]);
    header("Location: cart");
  }
}
?>
<div class="container">
  <?php 
    if(isset($_SESSION['successCartQtyUpdated'])) {
    ?>
    <span class="cart-notification"><?php echo $_SESSION['successCartQtyUpdated']; ?></span>
    <?php
      unset($_SESSION['successCartQtyUpdated']);
    }
  ?>
  <?php 
    if(isset($_SESSION['errorCartQtyUpdated'])) {
    ?>
    <span class="cart-notification-failed"><?php echo $_SESSION['errorCartQtyUpdated']; ?></span>
    <?php
      unset($_SESSION['errorCartQtyUpdated']);
    }
  ?>
  <div class="product-data-cart">
    <h2>Produktu grozs</h2>
    <form method="POST" action="">
      <div class="table-wrapper">
      <div class="table-product product-data">
        <div class="table-header">
          <div class="table-product-header">Preces</div>
          <div class="table-product-header"></div>
          <div class="table-product-header">Cena</div>
          <div class="table-product-header">Skaits</div>
          <div class="table-product-header">Kopā</div> 
        </div>
        <div class="table-body">
        <?php
        $regularPriceTotal = 0;
        $discountedPriceTotal = 0;
        if(mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_array($result)) {
            $productId = $row['productId'];
            $sql = "SELECT * FROM product WHERE id = '$productId'";
            $productResult = mysqli_query($conn, $sql);
            $product = mysqli_fetch_array($productResult);
            if($product['discount'] == 0.00) {
              $regularPriceTotal = $regularPriceTotal + ($product['price'] * $row['productQty']);
            } else {
              $discountedPriceTotal = $discountedPriceTotal + ($product['discount'] * $row['productQty']);
              } 
              if(isset($_SESSION['selectedSize'][$product['id']])) {
                $selectedSize = $_SESSION['selectedSize'][$product['id']];
              }
              //inicialize izmera masivu, ja tas vel nav izdarits
              if(!isset($_SESSION['selectedSize'][$product['id']])) {
                $_SESSION['selectedSize'][$product['id']] = array();
              }
        ?>
          <div class="table-row" data-total="<?php echo $product['price'] * $row['productQty']; ?>">
            <div class="table-col table-col-product-id">
              <img src="assets/img/<?php echo $product['featuredImage'];?>" width="75px" height="75px">
            </div>
            <div class="table-col">
              <a href="product-single.php?product=<?php echo $product['slug']?>"><?php echo $product['name'];?></a>
              <?php if(!empty($selectedSize)) {?></br>
                <div class="product-size">
                  <span class="product-size-header">Izmērs:</span></br>
                  <span class="product-size-value"><?php echo $selectedSize;?></span>
                </div> 
              <?php
                  } 
              ?>
            </div>
            <div class="table-col">   
              <div class="">
                <?php if($product['discount'] != 0.00) { ?>
                  <span class="regular-price product-price"><?php echo $product['price'];?>€</span>
                  <span class="discount-price-active product-price"><?php echo $product['discount'];?>€</span>
                <?php } else { ?>
                  <span class="product-price"><?php echo $product['price'];?>€</span>
                <?php } ?>
              </div>
            </div>
            <div class="table-col table-col-product-qty">
              <?php if($product['qty'] == 0) { ?>
                <span>Produkts nav pieejams</span>
                <div class="delete-from-cart">
                  <input type="hidden" name="productId" value="<?php echo $product['id'];?>">
                  <button class="delete-from-cart-btn" type="submit" name="delete"><i class="fa-solid fa-x"></i>Izdzēst</button>
                </div>
              <?php } else { ?>
                  <input type="hidden" name="productId[]" value="<?php echo $product['id'];?>">
                  <div class="qty-input-field">
                    <button class="decrement-cart-btn">-</button>
                    <input type="number" class="input-qty" name="productQty[]" value="<?php echo $row['productQty'];?>" size="5" min="1" max="10">
                    <button class="increment-cart-btn">+</button>
                  </div>
                  <button class="update-product-qty-btn" type="submit" name="updateTotalQty" onclick="//calculateTotalPrice()"><i class="fa-solid fa-refresh"></i>Atjaunot</button>
              </form>
              <form method="POST" action="">
                <div class="delete-from-cart">
                  <input type="hidden" name="productId" value="<?php echo $product['id'];?>">
                  <button class="delete-from-cart-btn" type="submit" name="delete"><i class="fa-solid fa-x"></i>Izdzēst</button>
                </div>
              </form>
              <?php } ?>
            </div>
            <div class="table-col">
              <div>
                <?php if($product['discount'] != 0.00) { ?>
                  <span class="total-price"><?php echo str_replace('.',',', number_format($product['discount'] * $row['productQty'], 2)); ?>€</span>
                <?php } else { ?>
                  <span class="total-price"><?php echo str_replace('.',',', number_format($product['price'] * $row['productQty'], 2)); ?>€</span>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php
          }
          $totalPrice = $regularPriceTotal + $discountedPriceTotal;
          $_SESSION['totalPrice'] = $totalPrice;
        } else {
          echo "Grozs ir tukšs";
        }
        ?>
         </div>
      </div>
      </div>
      <?php if(mysqli_num_rows($result) > 0) { ?>
        <div class="row">
        </div>
      <div class="row">
        <div class="cart-summary">
          <h3 class="title">Groza kopsavilkums</h3>
          <div class="table-product">
            <div class="table-body">
              <div class="table-row">
                <div class="table-col">
                  <span>Summa</span>
                </div>
                <div class="table-col">
                  <span class="summary-product-price"><?php echo str_replace('.',',', number_format($_SESSION['totalPrice'], 2));?>€</span>
                </div>
              </div>
              <div class="table-row">
                <div class="table-col">
                  <span>Piegāde</span>
                </div>
                <div class="table-col">
                  <span>Piegādes izmaksas tiek aprēķinātas pirkuma noformēšanas laikā</span>
                </div>
              </div>  
            </div>
          </div>
          <div class="proceed-to-checkout">
            <?php 
            $canProceed = true;
            $cartSql = "SELECT cart.*, product.qty as product_qty FROM cart JOIN product on cart.productId = product.id WHERE cart.userId = '$userId'";
            $cartResult = mysqli_query($conn, $cartSql);
            $cartItems = mysqli_fetch_all($cartResult, MYSQLI_ASSOC);
            foreach($cartItems as $cartItem) {
              $productId = $cartItem['productId'];
              $qty = $cartItem['productQty'];
              $product_qty = $cartItem['product_qty'];
              if($product_qty < $qty || $product_qty == 0) {
                $canProceed = false;
                break;
              }
            }
            if($canProceed == true) { ?>
              <a href="checkout">Turpināt noformēt pirkumu</a>
            <?php } else { ?>
              <a class="inactive-link" href="#">Turpināt noformēt pirkumu</a>
              <span class="proceed-to-checkout-info"><i class="fa-regular fa-circle-question"></i></span>
              <div class="proceed-to-checkout-info-disabled-notification">
                <span class="notification">Nevar noformēt pirkumu, ja produkts nav pieejams</span>
              </div>
            <?php } ?>
          </div>
        </div>
    </div>
    <?php } ?>
  </div>
</div>  
<?php require_once('footer.php'); ?>