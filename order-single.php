<?php require_once('header.php');?>
<?php 
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
if(isset($_GET['orderid'])) {
$userId = $_SESSION['user_id'];
$orderId = $_GET['orderid'];
$sql = "SELECT orders.id AS oid, orders.orderDate, orders.status, orders.shippingMethod, orders.paymentMethod,
  order_details.unitPrice, order_details.discount, order_details.qty, order_details.productId,
  product.name, product.slug, product.featuredImage
  FROM orders JOIN order_details ON orders.id = order_details.orderId 
  JOIN product on order_details.productId = product.id
  WHERE orders.customerId = '$userId' AND orders.id = '$orderId'
  GROUP BY order_details.id";
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="order-page">
    <div class="order-details">
      <?php 
      if(isset($_SESSION['successOrder'])) {
      ?>
      <span class="success-message"><?php echo $_SESSION['successOrder']; ?></span>
      <?php
        unset($_SESSION['successOrder']);
      }
      ?>
     
      <h3><a href="order">Visi pasūtījumi</a></h3>
      <?php 
        if(mysqli_num_rows($result) > 0) {
          $row = mysqli_fetch_assoc($result);
            $statusProcessing = $row['status'];
            if($statusProcessing == 0) { 
              $status = "Procesā";
              $statusClass = "processing";
            }
            if($statusProcessing == 1) { 
              $status = "Atcelts";
              $statusClass = "canceled";
            }
            if($statusProcessing == 2) { 
              $status = "Pabeigts";
              $statusClass = "completed";
            }
            $shippingMethodSelected = $row['shippingMethod'];
            if($shippingMethodSelected == "shipping_method_store") {
              $shippingMethod = "Saņemt preci veikalā (Babolat badmintona veikals Rīgā, Cēsu ielā 31)";
              $shippingPrice = 0;
            }
            if($shippingMethodSelected == "shipping_method_delivery") {
              $shippingMethod = "Saņemt preci ar piegādi uz mājām";
              $shippingPrice = 9.99;
            }
            $paymentMethodSelected = $row['paymentMethod'];
            if($paymentMethodSelected == "payment_method_store") {
              $paymentMethod = "Uz vietas veikalā";
            }
            if($paymentMethodSelected == "payment_method_paypal") {
              $paymentMethod = "Maksāts ar PayPal";
            }
            if(isset($_POST['cancelOrder'])) {
              if($statusProcessing == 1) {
                $cancelError = "Pasūtījums jau ir atcelts.";
              } 
              else if ($statusProcessing == 2) {
                $cancelError = "Pasūtījums jau ir apstiprināts.";
              } else {
                $sqlUpdate = "UPDATE orders SET status = 1 WHERE orders.customerId = '$userId' AND orders.id = '$orderId'";
                $resultUpdate = mysqli_query($conn, $sqlUpdate);
                if($resultUpdate) {
                  $orderNotificationCanceled = [
                    'message' => "Pasūtījums ir atcelts, pasūtījuma ID: $orderId",
                    'datetime' => date('Y-m-d H:i:s')
                  ];
                  $_SESSION['cancelSuccess'] = 'Pasūtījums ir veiksmīgi atcelts!';
                  $_SESSION['orderNotificationCancel'] = $orderNotificationCanceled;
                }
                header("Location: order-single?orderid=$orderId");
                exit;
              }
            }
        ?>
      <?php if (isset($cancelError)) { ?>
        <span class="error-message"> <?php echo $cancelError; ?></span></br>
      <?php } ?>
      <?php if(isset($_SESSION['cancelSuccess'])) { ?>
        <span class="success-message"><?php echo $_SESSION['cancelSuccess']; ?></span>
      <?php unset($_SESSION['cancelSuccess']); } ?>  
      <h2>Pasūtījuma nr. <?php echo $row['oid'];?></h2>
      <span>Pasūtījuma datums: <?php echo $row['orderDate'];?></span></br>
      <span>Pasūtījuma statuss:
        <span class="<?php echo $statusClass;?>"><?php echo $status;?></span>
      </span>
    </div>
    <?php if($paymentMethodSelected == "payment_method_store") { ?>
      <form id="cancelForm-<?php echo $row['oid'];?>" method="POST">  
        <div class="col col-order-cancel">
          <input class="cancel-btn" type="submit" name="cancelOrder" value="Atcelt pasūtījumu">
        </div>
      </form>
    <?php } ?>
    <hr>
    <div class="row row-order-details-transaction">
      <div class="col">
        <p>Piegādes veids:</p>
        <p><?php echo $shippingMethod; ?></p>
      </div>
      <div class="col">
        <p>Maksājuma veids:</p>
        <p><?php echo $paymentMethod; ?></p>
      </div>
    </div>
      <?php } ?>
    <h2>Pasūtītie produkti</h2>
    <div class="order-details-products">
      <div class="table-wrapper">
      <div class="table-product product-data">
        <div class="table-header">
          <div class="table-product-header">Produkti</div>
          <div class="table-product-header"></div>
          <div class="table-product-header">Cena (gab.)</div>
          <div class="table-product-header">Daudzums</div>
          <div class="table-product-header">Kopā</div>
        </div>
        <div class="table-body">
          <?php
          if(mysqli_num_rows($result) > 0) {
            mysqli_data_seek($result, 0); //atiestata result pointeri
            while($row = mysqli_fetch_assoc($result)) {
              if($row['discount'] != 0.00) {
                $totalPrice += $row['discount'] * $row['qty'];
              } else {
                $totalPrice += $row['unitPrice'] * $row['qty'];
              }
          ?>
          <div class="table-row">
            <div class="table-col">
              <a href="product-single?product=<?php echo $row['slug'];?>">
              <img src="assets/img/<?php echo $row['featuredImage'];?>" alt="Produkta attēls" width="100px" height="100px">
              </a>
            </div>
            <div class="table-col"><?php echo $row['name'];?></div>
            <?php if($row['discount'] != 0.00) { ?>
              <div class="table-col">
                <span style="color:#bbb;"><s><?php echo str_replace('.',',', number_format($row['unitPrice'], 2));?> €</s></span></br>
                <span><?php echo number_format($row['discount'], 2);?> €</span>
              </div>
            <?php } else { ?>
              <div class="table-col"><?php echo str_replace('.',',', number_format($row['unitPrice'], 2));?> €</div>
            <?php } ?>
            <div class="table-col"><?php echo $row['qty'];?></div>
            <div class="table-col">
            <?php if($row['discount'] != 0.00) { ?>
              <span class="total-price"><?php echo str_replace('.',',', number_format($row['discount'] * $row['qty'], 2)); ?>€</span>
             <?php } else { ?>
                <span class="total-price"><?php echo str_replace('.',',', number_format($row['unitPrice'] * $row['qty'], 2)); ?>€</span>
              <?php } ?>
            </div>
          </div>
          <?php
            } 
          }
          ?> 
        </div>
      </div>
      </div>
      <?php
      ?>
      <div class="order-total-amount">
        <p class="product-amount">Kopā:
          <?php echo str_replace('.',',', number_format($totalPrice, 2));?> €
        </p>
        <p class="shipping-amount">Piegādes maksa:
          <?php echo str_replace('.',',', $shippingPrice); ?> €
        </p>
        <p class="total-amount">Kopējā summa:
        <?php
        if($shippingMethodSelected == "shipping_method_delivery") { 
          echo str_replace('.',',', number_format($totalPrice + $shippingPrice, 2)); 
        } else {
          echo str_replace('.',',', number_format($totalPrice, 2)); 
        }
        ?> €
        </p>
      </div>
    </div>
  </div>
</div>
<?php
}
?>
<?php require_once('footer.php');?>
