<?php require_once('header.php');?>
<?php 
if(isset($_SESSION['user_id'])=="") {
  header("Location: login");
}
$userId = $_SESSION['user_id'];
$sql = "SELECT orders.id AS oid, orders.orderDate, order_details.unitPrice, order_details.qty, orders.status, orders.shippingMethod, orders.paymentMethod
FROM orders JOIN order_details ON orders.id = order_details.orderId WHERE orders.customerId = '$userId'
GROUP BY orders.orderDate ORDER BY orders.orderDate DESC";
$result = mysqli_query($conn, $sql);

$sqlProduct = "SELECT order_details.productId, product.name, product_category.name AS catname
FROM order_details JOIN product ON order_details.productId = product.id
JOIN product_category ON product.categoryId = product_category.id";
$resultProduct = mysqli_query($conn, $sqlProduct);
$rowProduct = mysqli_fetch_assoc($resultProduct);
?>
<div class="container">
  <div class="order-page">
    <h2>Pasūtījumi</h2>
    <span class="success-message"></span>
    <div class="table-wrapper">
    <div class="table-product product-data">
      <div class="table-header">
        <div class="table-product-header">Pasūtījuma Nr.</div>
        <div class="table-product-header">Pasūtījuma datums</div>
        <div class="table-product-header">Summa</div>
        <div class="table-product-header">Maksājuma veids</div>
        <div class="table-product-header">Statuss</div> 
      </div>
      <div class="table-body">
        <?php 
        if(mysqli_num_rows($result) > 0) {
          while($row = mysqli_fetch_assoc($result)) {
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
              $shippingPrice = 0;
            }
            if($shippingMethodSelected == "shipping_method_delivery") {
              $shippingPrice = 9.99;
            }
            $paymentMethodSelected = $row['paymentMethod'];
            if($paymentMethodSelected == "payment_method_store") {
              $paymentMethod = "Uz vietas veikalā";
            }
            if($paymentMethodSelected == "payment_method_paypal") {
              $paymentMethod = "Maksāts ar PayPal";
            }
            $totalPrice = 0;
            $orderId = $row['oid'];
            $productSql = "SELECT * FROM order_details WHERE orderId = '$orderId'";
            $productResult = mysqli_query($conn, $productSql);
            if(mysqli_num_rows($result) > 0) {
              while($productRow = mysqli_fetch_assoc($productResult)) {
                if($productRow['discount'] != 0.00) {
                  $totalPrice += $productRow['discount'] * $productRow['qty'];
                } else {
                  $totalPrice += $productRow['unitPrice'] * $productRow['qty'];
                }
              }
            }
            ?>    
        <div class="table-row">
          <div class="table-col">
            <a href="order-single?orderid=<?php echo $row['oid']?>">
            <span><?php echo $row['oid'];?></span>
            </a>
          </div>
          <div class="table-col">
            <span><?php echo $row['orderDate'];?></span>
          </div>
          <div class="table-col">
            <?php if($shippingMethodSelected == "shipping_method_delivery") { ?>
              <span><?php echo str_replace('.',',', number_format($totalPrice + $shippingPrice, 2));?> €</span> 
            <?php } else { ?>
              <span><?php echo str_replace('.',',', number_format($totalPrice, 2));?> €</span>
            <?php } ?>
          </div>
          <div class="table-col">
            <span><?php echo $paymentMethod; ?></span>
          </div>
          <div class="table-col">
            <span class="<?php echo $statusClass;?>"><?php echo $status;?></span>
          </div>
        </div>
        <?php
          }
        } else {
          echo "Pasūtījumu nav";
        }
        ?>
      </div>
    </div>
    </div>
  </div>
</div>
<?php require_once('footer.php');?>