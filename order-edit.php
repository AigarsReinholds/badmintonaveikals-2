<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
if(isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "SELECT orders.id AS oid, orders.*,
  order_details.unitPrice, order_details.discount, order_details.qty, order_details.productId,
  product.name, product.slug, product.featuredImage
  FROM orders JOIN order_details ON orders.id = order_details.orderId
  JOIN product on order_details.productId = product.id 
  WHERE orders.id = '$id'
  GROUP BY order_details.id"; 
  $result = mysqli_query($conn, $sql);
  $count = mysqli_num_rows($result);
  if($count > 0) {
    $row = mysqli_fetch_assoc($result);
    $customerId = $row['customerId'];
    $userSql = "SELECT email FROM user WHERE id = '$customerId'";
    $userResult = mysqli_query($conn, $userSql);
    $userRow = mysqli_fetch_assoc($userResult);
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
  } else {
    header("Location: order");
  }
  if($_POST['statusChange']) {
    $statusProcessing = $_POST['status'];
    $sql = "UPDATE orders SET status = '$statusProcessing' WHERE id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($statusProcessing == 2) {
      $statusProcessing = $_POST['status'];
      $sql = "UPDATE orders SET status = '$statusProcessing', shippedDate = CONVERT_TZ(UTC_TIMESTAMP(),'+00:00','+03:00') WHERE id = '$id'";
      $result = mysqli_query($conn, $sql);
    }
    if($result) {
      $_SESSION['statusChanged'] = 'Pasūtījuma statuss ir izmainīts';
      header("Location: order-edit?id={$row['oid']}");
      exit;
    }
  }
}
?>
<div class="container">
  <div class="container-inner">
  <?php 
    if(isset($_SESSION['statusChanged'])) {
    ?>
    <span class="success-insertion-notification"><?php echo $_SESSION['statusChanged']; ?></span>
    <?php
      unset($_SESSION['statusChanged']);
    }
    ?>
    <h2>Rediģēt pasūtījumu:</h2>
    <form class="order-edit-form" method="post">
      <div class="row">
        <div class="col">
          <label>Statuss:</label>
          <select class="status" name="status">
            <option value="0" <?php if($statusProcessing == 0) echo "selected"; ?>>Procesā</option>
            <option value="1" <?php if($statusProcessing == 1) echo "selected"; ?>>Atcelts</option>
            <option value="2" <?php if($statusProcessing == 2) echo "selected"; ?>>Pabeigts</option>
          </select></br>
          <input class="save-btn" type="submit" name="statusChange" value="Saglabāt">
        </div>
        <div class="col">
          <div class="form-item">
            <label>Pasūtījuma Nr: <?php echo $row['oid']; ?></label>
          </div>
          <div class="form-item">
            <label>Klienta vārds: <?php echo $row['shipFirstName']; ?></label>
          </div>
          <div class="form-item">
            <label>Klienta uzvārds: <?php echo $row['shipLastName']; ?></label>
          </div>
          <div class="form-item">
            <label>Klienta e-pasts: <?php echo $userRow['email']; ?></label>
          </div>
          <div class="form-item">
            <label>Klienta tālrunis: <?php echo $row['shipPhone']; ?></label>
          </div>
        </div>
      </div>
    </form>
    <div class="order-edit-products">
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
                <a href="../product-single?product=<?php echo $rowProduct['slug'];?>">
                <img src="../assets/img/<?php echo $row['featuredImage'];?>" alt="Produkta attēls" width="100px" height="100px">
                </a>
              </div>
              <div class="table-col"><?php echo $row['name'];?></div>
              <?php if($row['discount'] != 0.00) { ?>
              <div class="table-col">
                <span style="color:#bbb;"><s><?php echo number_format($row['unitPrice'], 2);?> €</s></span></br>
                <span><?php echo number_format($row['discount'], 2);?> €</span>
              </div>
              <?php } else { ?>
                <div class="table-col"><?php echo number_format($row['unitPrice'], 2);?> €</div>
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
      <div class="order-summary-amount">
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
<?php require_once('footer.php'); ?>