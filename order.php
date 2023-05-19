<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
$sql = "SELECT orders.id AS oid, orders.orderDate, order_details.unitPrice, order_details.qty, orders.status, orders.shippingMethod, orders.paymentId
FROM orders JOIN order_details ON orders.id = order_details.orderId GROUP BY orderId ORDER BY orderDate DESC";
$result = mysqli_query($conn, $sql);
$row = mysqli_num_rows($result);
if(isset($_GET['filterStatus'])) {
  $filterStatus = $_GET['filterStatus'];
  $whereClause = "";
  switch($filterStatus) {
    case "default":
      break;  
    default:
      $status = mysqli_real_escape_string($conn, $filterStatus);
      $whereClause = "WHERE orders.status = '$status'";
      break;
  }
  $sql = "SELECT orders.id AS oid, orders.orderDate, order_details.unitPrice, order_details.qty, orders.status, orders.shippingMethod, orders.paymentId
  FROM orders JOIN order_details ON orders.id = order_details.orderId
  $whereClause
  GROUP BY orderId ORDER BY orderDate DESC";
}
$result = mysqli_query($conn, $sql);
?>
<div class="container">
  <div class="container-inner">
    <h2>Esošie pasūtījumi:</h2>
    <form method="GET">
      <button class="save-btn">Piemērot</button>
      <select class="form-select" name="filterStatus" id="filterStatus">
        <option value="default">Izvēlies statusu</option>
        <?php
        //izvada statusu 
          $sqlSort = "SELECT DISTINCT status FROM orders GROUP BY status";
          $resultSort = mysqli_query($conn, $sqlSort);
          while($rowSort = mysqli_fetch_assoc($resultSort)) {
            $status = $rowSort['status'];
            if($status == 0) { 
              $statusCurrent = "Procesā";
            }
            if($status == 1) { 
              $statusCurrent = "Atcelts";
            }
            if($status == 2) { 
              $statusCurrent = "Pabeigts";
            }
            $selected = ($filterStatus === $status) ? "selected" : "";
            echo "<option value='$status' $selected>$statusCurrent</option>";
          }
        ?>
      </select>
    </form>
    <div class="container-table">
      <div class="table-product" id="tableProduct">
        <div class="table-header">
          <div class="table-product-header table-product-header-product-id">Pasūtījuma id</div>
          <div class="table-product-header">Pasūtījuma datums</div>
          <div class="table-product-header">Statuss</div>
          <div class="table-product-header">Piegādes veids</div>
          <div class="table-product-header">Apmaksāts</div>
          <div class="table-product-header"></div>
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
                $shippingMethod = "Saņemt preci veikalā";
              }
              if($shippingMethodSelected == "shipping_method_delivery") {
                $shippingMethod = "Saņemt preci ar piegādi uz mājām";
              }
              $paymentId = $row["paymentId"];
              if($paymentId != "") {
                $paymentStatus = "Ir";
                $statusClassPayment = "completed";
              } else {
                $paymentStatus = "Nav";
                $statusClassPayment = "canceled";
              }
          ?>
          <div class="table-row">
            <div class="table-col table-col-product-id"><?php echo $row['oid'];?></div>
            <div class="table-col">
              <span><?php echo $row['orderDate'];?></span>
            </div>
            <div class="table-col">
              <span class="<?php echo $statusClass;?>"><?php echo $status;?></span>
            </div>
            <div class="table-col"><?php echo $shippingMethod;?></div>
            <div class="table-col">
              <span class="<?php echo $statusClassPayment;?>"><?php echo $paymentStatus; ?>
            </div>
            <div class="table-col table-col-edit"><a href="order-edit?id=<?php echo $row['oid']; ?>">Rediģēt</a></div>
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