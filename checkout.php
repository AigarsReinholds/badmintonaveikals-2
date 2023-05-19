<?php require_once('header.php'); ?>
<script>
window.onload = function() {
  const adressFields = document.getElementById("adress-fields");
  const cardFields = document.getElementById("stripe-payment-data");
  const adressFieldsVisibility = localStorage.getItem("adressFieldsVisibility");
  const cardFieldsVisibility = localStorage.getItem("cardFieldsVisibility");
  if(adressFieldsVisibility === 'none') {
    adressFields.style.display = 'none';
  }
  if(cardFieldsVisibility === 'none') {
    cardFields.style.display = 'none';
  }
}; 
</script>
<?php 
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
$userId = $_SESSION['user_id'];
$totalPrice = $_SESSION['totalPrice'];
//$selectedSize = $_SESSION['selectedSize'][$product['id']];
$error = false;
$nameError = "";
$surnameError = "";
$phoneError = "";
$adressError = "";
$cityError = "";
$postcodeError = "";
$shippingMethod = "";
$paymentMethod = "";
if(isset($_POST['paypalApproved']) || isset($_POST['submitOrder'])) {
  $firstName =  $_POST['shipFirstName'];
  $lastName =  $_POST['shipLastName'];
  $phone = $_POST['shipPhone'];
  $adress = $_POST['shippingAdress'];
  $city = $_POST['shippingCity'];
  $region = $_POST['shippingRegion'];
  $postcode = $_POST['shippingPostcode'];
  $comments = $_POST['comments'];
  $shippingMethod = $_POST['shippingMethod'];
  $paymentMethod = $_POST['paymentMethod'];
  $paymentId = $_POST['paymentId'];
  if(empty($firstName)) {
		$error = true;
		$nameError = "Norēķinu Vārds ir obligātais lauks";
	}
	else if(!preg_match("/^\p{L}+$/u", $firstName)) {
		$error = true;
		$nameError = "Lauks nav derīgs";
	}
	if (empty($lastName)) {
		$error = true;
		$surnameError = "Norēķinu Uzvārds ir obligātais lauks";
	}
	else if(!preg_match("/^\p{L}+$/u", $lastName)) {
		$error = true;
		$surnameError = "Lauks nav derīgs";
	}
  if(empty($phone)) {
    $error = true;
    $phoneError = "Norēķinu Tālrunis ir obligātais lauks";
  }
  else if(strlen($phone) < 8) {
    $error = true;
    $errorCount = "Telefonam ir jāsatur 8 simboli";
  }
  if($shippingMethod == "shipping_method_delivery") {
    if(empty($adress)) {
      $error = true;
      $adressError = "Norēķinu Adrese ir obligātais lauks";
    }
    if(empty($city)) {
      $error = true;
      $cityError = "Norēķinu Pilsēta ir obligātais lauks";
    }
    if(empty($postcode)) {
      $error = true;
      $postcodeError = "Norēķinu Pasta indekss ir obligātais lauks";
    }
  }
  if(empty($shippingMethod)) {
    $error = true;
    $shippingMethodError = "Piegādes veids ir obligāti jāizvēlas";
  }
  else if(!empty($shippingMethod)) {
    $_SESSION['shipping_method'] = $_POST['shippingMethod'];
  }
  if(empty($paymentMethod)) {
    $error = true;
    $paymentMethodError = "Apmaksas veids ir obligāti jāizvēlas";
  }
  else if(!empty($paymentMethod)) {
    $_SESSION['payment_method'] = $_POST['paymentMethod'];
  }

  $sql = "SELECT * FROM cart WHERE userId = '$userId'";
  $result = mysqli_query($conn, $sql);
  $orderDetails = array();
  while($row = mysqli_fetch_array($result)) {
    $productId = $row['productId'];
    $productSql = "SELECT * FROM product WHERE id = '$productId'";
    $productResult = mysqli_query($conn, $productSql);
    $product = mysqli_fetch_array($productResult);
    $unitPrice = $product['price'];
    $qty = $row['productQty'];
    $discount = $product['discount'];
    $orderDetails[] = array('productId' => $productId, 'unitPrice' => $unitPrice, 'qty' => $qty, 'discount' => $discount);
  }
    if(!$error) {
      if ($_POST['shippingMethod'] == 'shipping_method_delivery' || !isset($_POST['shippingMethod'])) {
        $sqlInsert = "INSERT INTO orders (customerId, orderDate, shipFirstName, shipLastName, shipPhone, shippingAdress, shippingCity, shippingRegion, shippingPostcode, comments, shippingMethod, paymentMethod, paymentId) 
        VALUES('$userId', CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','+03:00'), '$firstName', '$lastName', '$phone', '$adress', '$city','$region', '$postcode', '$comments', '$shippingMethod', '$paymentMethod', '$paymentId')";
        $resultInsert = mysqli_query($conn, $sqlInsert);
        $orderId = mysqli_insert_id($conn);
      }
      else if ($_POST['shippingMethod'] == 'shipping_method_store') {
        $sqlInsert = "INSERT INTO orders (customerId, orderDate, shipFirstName, shipLastName, shipPhone, comments, shippingMethod, paymentMethod, paymentId) 
        VALUES ('$userId', CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','+03:00'), '$firstName', '$lastName', '$phone', '$comments', '$shippingMethod', '$paymentMethod', '$paymentId')";
        $resultInsert = mysqli_query($conn, $sqlInsert);
        $orderId = mysqli_insert_id($conn);
      }
    if($resultInsert) {
      foreach($orderDetails as $orderDetail) {
        $productId = $orderDetail['productId'];
        $unitPrice = $orderDetail['unitPrice'];
        $qty = $orderDetail['qty'];
        $discount = $orderDetail['discount'];
        $orderDetailsInsert = "INSERT INTO order_details (orderId, productId, unitPrice, qty, discount) VALUES ('$orderId','$productId', '$unitPrice', '$qty', '$discount')";
        $orderDetailsResult = mysqli_query($conn, $orderDetailsInsert);
        if(!$orderDetailsResult) {
          echo "Error: " . mysqli_error($conn);
        }
        $productSql = "SELECT * FROM product WHERE id = '$productId'";
        $productResult = mysqli_query($conn, $productSql);
        $productRow = mysqli_fetch_assoc($productResult);
        $newQty = $productRow['qty'] - $qty;
        $productUpdate = "UPDATE product SET qty = '$newQty' WHERE id = '$productId'";
        $productUpdateResult = mysqli_query($conn, $productUpdate);
      }
      $sql = "DELETE FROM cart WHERE userId = '$userId'";
      $result = mysqli_query($conn, $sql);
      unset($_SESSION['cart'][$productId]);
      unset($_SESSION['selectedSize'][$productId]);
      unset($_SESSION['totalPrice']);
      $orderNotificationCreated = [
        'message' => "Pasūtījums ir veikts, pasūtījuma ID: $orderId",
        'datetime' => date('Y-m-d H:i:s')
      ];
      $_SESSION['orderNotificationCreate'] = $orderNotificationCreated;
      $_SESSION['successOrder'] = 'Pasūtījums ir veiksmīgi veikts!';
      header("Location: order-single?orderid=$orderId");
    } else {
      echo "Kļūda veicot pasūtījumu";
    }
  }
}
/*if(isset($_SESSION['shipping_method'])) {
  echo $_SESSION['shipping_method'];
}*/
$sql = "SELECT * FROM cart WHERE userId = '$userId'";
$result = mysqli_query($conn, $sql);
?>
  <div class="container">
    <div class="checkout-page">
    <?php if($error == true) { ?>
      <div class="error-box">
        <?php if(isset($nameError)) { ?>
          <span class="error-message"><?php echo $nameError; ?></span></br>
        <?php } ?>
        <?php if(isset($surnameError)) { ?>
          <span class="error-message"><?php echo $surnameError; ?></span></br>
        <?php } ?>
        <?php if(isset($phone)) { ?>
          <span class="error-message"><?php echo $phoneError; ?></span></br>
        <?php } ?>
        <?php if(isset($errorCount)) { ?>
          <span class="error-message"><?php echo $errorCount; ?></span></br>
        <?php } ?>
        <?php if(isset($adressError)) { ?>
          <span class="error-message"><?php echo $adressError; ?></span></br>
        <?php } ?>
        <?php if(isset($cityError)) { ?>
        <span class="error-message"><?php echo $cityError; ?></span></br>
        <?php } ?>
        <?php if(isset($postcodeError)) { ?>
        <span class="error-message"><?php echo $postcodeError; ?></span></br>
        <?php } ?>
        <?php if(isset($shippingMethodError)) { ?>
        <span class="error-message"><?php echo $shippingMethodError; ?></span></br>
        <?php } ?>
        <?php if(isset($paymentMethodError)) { ?>
        <span class="error-message"><?php echo $paymentMethodError; ?></span></br>
        <?php } ?>
      </div>
    <?php } ?>
      <form class="checkout" id="checkout-form" method="POST">
        <div class="row row-checkout">
          <div class="customer-details">
            <div class="form-item form-item-col-1">
              <label class="fname-label">Vārds <span class="required-label">*</span></label></br>
              <input class="fname-input" type="text" name="shipFirstName" id="shipFirstName" value="<?php if(isset($_POST['shipFirstName'])) {echo $_POST['shipFirstName'];}?>">
              <span class="error-message shipFirstName"></span>
            </div>
            <div class="form-item form-item-col-2">
              <label class="lname-label">Uzvārds <span class="required-label">*</span></label></br>
              <input class="lname-input" type="text" name="shipLastName" id="shipLastName" value="<?php if(isset($_POST['shipLastName'])) {echo $_POST['shipLastName'];}?>">
              <span class="error-message shipLastName"></span>
            </div>
            <div id="adress-fields">
              <div class="form-item form-item-row">
                <label class="adress-label">Adrese <span class="required-label">*</span></label></br>
                <input class="adress-input" type="text" name="shippingAdress" id="shippingAdress" value="<?php if(isset($_POST['shippingAdress'])) {echo $_POST['shippingAdress'];}?>">
                <span class="error-message shippingAdress"></span>
              </div>
              <div class="form-item form-item-row">
                <label class="city-label">Pilsēta <span class="required-label">*</span></label></br>
                <input class="city-input" type="text" name="shippingCity" id="shippingCity" value="<?php if(isset($_POST['shippingCity'])) {echo $_POST['shippingCity'];}?>">
                <span class="error-message shippingCity"></span>
              </div>
              <div class="form-item form-item-row">
                <label class="city-label">Reģions</label></br>
                <input class="city-input" type="text" name="shippingRegion" id="shippingRegion" value="<?php if(isset($_POST['shippingRegion'])) {echo $_POST['shippingRegion'];}?>">
              </div>
              <div class="form-item form-item-row">
                <label class="postcode-label">Pasta indekss <span class="required-label">*</span></label></br>
                <input class="postcode-input" type="text" name="shippingPostcode" id="shippingPostcode" value="<?php if(isset($_POST['shippingPostcode'])) {echo $_POST['shippingPostcode'];}?>">
                <span class="error-message shippingPostcode"></span>
              </div>
            </div>
            <div class="form-item form-item-row">
              <label class="phone-label">Telefons <span class="required-label">*</span></label></br>
              <input class="phone-input" type="tel" name="shipPhone" id="shipPhone" value="<?php if(isset($_POST['shipPhone'])) {echo $_POST['shipPhone'];}?>">
              <span class="error-message shipPhone"></span>
            </div>
            <div class="form-item form-item-row">
              <label class="notes-label">Pirkuma piezīmes</label></br>
              <textarea class="notes-input" name="comments" placeholder="Pasūtījuma piezīmes, papildu norādījumi par piegādi."></textarea>
            </div>
          </div>
          <div class="order-review">
            <div class="table-product table-order">
              <div class="table-header">
                <div class="table-product-header table-order-header">Produkts</div>
                <div class="table-product-header table-order-header">Summa</div>
              </div>
              <div class="table-body">
                  <?php 
                    if(mysqli_num_rows($result) > 0) {
                      while($row = mysqli_fetch_array($result)) {
                        $productId = $row['productId'];
                        $productSql = "SELECT * FROM product WHERE id = '$productId'";
                        $productResult = mysqli_query($conn, $productSql);
                        $product = mysqli_fetch_array($productResult);
                    ?>
                <div class="table-row">
                  <div class="table-col table-col-order">
                    <span><?php echo $product['name']; ?></span></br>
                    <strong>x <?php echo $row['productQty']; ?></strong>                       
                  </div>
                  <div class="table-col table-col-order">
                    <?php if($product['discount'] != 0.00) { ?>
                      <span class="total-price"><?php echo str_replace('.',',', number_format($product['discount'] * $row['productQty'], 2)); ?>€</span>
                    <?php } else { ?>
                      <span class="total-price"><?php echo str_replace('.',',', number_format($product['price'] * $row['productQty'], 2)); ?>€</span>
                    <?php } ?>
                  </div>
                </div>
                <?php
                      }
                    }
                ?>
                <div class="table-row">
                  <div class="table-col table-col-order">
                    <span>Summa</span>
                  </div>
                  <div class="table-col table-col-order">
                    <span><?php echo str_replace('.',',', number_format($totalPrice, 2)); ?>€</span>
                  </div>
                </div>                
                <div class="table-row">
                  <div class="table-col table-col-shipping-method">
                    <span>Piegāde</span>
                  </div>
                  <div class="table-col table-col-shipping-method">
                    <ul class="shipping-options">
                      <li>
                        <input type="radio" name="shippingMethod" id="shipping_method_store" <?php if(isset($_POST['shippingMethod']) && $_POST['shippingMethod'] == 'shipping_method_store') { echo "checked"; }?> value="shipping_method_store" onclick="handleShippingOption()">
                        <label>Saņemt preci veikalā (Babolat badmintona veikals Rīgā, Cēsu ielā 31)</label>
                      </li>
                      <li>
                        <input type="radio" name="shippingMethod" id="shipping_method_delivery" <?php if(isset($_POST['shippingMethod']) && $_POST['shippingMethod'] == 'shipping_method_delivery') { echo "checked"; }?> value="shipping_method_delivery" onclick="handleShippingOption()">
                        <label>Saņemt preci ar piegādi uz mājām: <span>9,99€</span></label>
                      </li>
                      <span class="error-message shippingMethod"></span>
                    </ul>
                  </div>
                </div>
                <div class="table-row">
                  <div class="table-col table-col-order">
                    <span>Kopā</span>
                  </div>
                  <div class="table-col table-col-order table-col-order-total">
                    <span id="totalPrice"><?php echo str_replace('.',',', number_format($totalPrice, 2)); ?>€</span>
                    <input type="hidden" id="totalPricePayment" value="<?php echo $totalPrice;?>">
                  </div>
                </div>
              </div>
            </div>
            <div class="checkout-payment">
              <ul class="payment-methods">
                <li>
                  <input type="radio" name="paymentMethod" id="payment_method_paypal" <?php if(isset($_POST['paymentMethod']) && $_POST['paymentMethod'] == 'payment_method_paypal') { echo "checked"; }?> value="payment_method_paypal" onclick="handlePaymentOption()">
                  <label>Maksāt ar PayPal</label>
                  <div id="paypal-payment-button"></div>
                </li>
                <li id="paymentMethodStore">
                  <input type="radio" name="paymentMethod" id="payment_method_store" <?php if(isset($_POST['paymentMethod']) && $_POST['paymentMethod'] == 'payment_method_store') { echo "checked"; }?> value="payment_method_store" onclick="handlePaymentOption()">
                  <label>Maksāt uz vietas veikalā</label>
                </li>
              </ul>
              <div class="payment-proceed">
                <input type="hidden" name="paypalApproved" id="paypalApproved" value="">
                <input type="hidden" name="paymentId" id="paymentId" value="">
                <button class="order-btn" id="submitOrder" name="submitOrder" type="submit">VEIKT PASŪTĪJUMU</button>
                <div class="order-btn-disabled-notification">
                  <span class="notification">Poga strādā tikai pasūtījuma veikšanai ar SAŅEMŠANU un APMAKSU veikalā.</br>Veicot apmaksu ar PayPal, pasūtījums tiks automātiski izveidots!</span>
                </div>
              </div>
            </div>
          </div>  
        </div>
    </form>
  </div>
</div>
<?php require_once('footer.php'); ?>
<!-- Replace "test" with your own sandbox Business account app client ID -->
<script src="https://www.paypal.com/sdk/js?client-id=ASTc_XsIKKe0Xxj0OUORrKh24JADhLxR5-kUB_1sNL9D9tfcl-aV8KAWs9Z1esWL-lKMt_JUgkJ--Fnd&currency=EUR"></script>
<script src="assets/js/paypal-script.js"></script>