<?php require_once('header.php'); ?>
<?php
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
}
$sqlProduct = "SELECT COUNT(id) AS totalProducts FROM product";
$sqlCategory = "SELECT COUNT(id) AS totalCategories FROM product_category";
$sqlSubcategory = "SELECT COUNT(id) AS totalSubcategories FROM product_subcategory";
$sqlOrder = "SELECT COUNT(id) AS totalOrders FROM orders";
$sqlEmployee = "SELECT COUNT(ID) AS totalEmployees FROM adminuser WHERE role='employee'";
$sqlUser = "SELECT COUNT(id) AS totalUsers FROM user";
$sqlMessage = "SELECT COUNT(id) AS totalMessages FROM message";

$resultProduct = mysqli_query($conn, $sqlProduct);
$resultCategory = mysqli_query($conn, $sqlCategory);
$resultSubcategory = mysqli_query($conn, $sqlSubcategory);
$resultOrder = mysqli_query($conn, $sqlOrder);
$resultEmployee = mysqli_query($conn, $sqlEmployee);
$resultUser = mysqli_query($conn, $sqlUser);
$resultMessage = mysqli_query($conn, $sqlMessage);

$rowProduct = mysqli_fetch_assoc($resultProduct);
$rowCategory = mysqli_fetch_assoc($resultCategory);
$rowSubcategory = mysqli_fetch_assoc($resultSubcategory);
$rowOrder = mysqli_fetch_assoc($resultOrder);
$rowEmployee = mysqli_fetch_assoc($resultEmployee);
$rowUser = mysqli_fetch_assoc($resultUser);
$rowMessage = mysqli_fetch_assoc($resultMessage);
?>
<div class="container">
  <div class="container-inner">
    <div class="admin-panel">
      <h1>Panelis</h1>
      <?php if($_SESSION['user_role'] == 'employee') {?>
      <!-- Darbiniekiem pieejams -->
      <div class="product-box">
        <a href="product">
          <h2>Produkti (<?php echo $rowProduct['totalProducts'];?>)</h2>
        </a>
      </div>
      <div class="product-category-box">
        <a href="category">
          <h2>Kategorijas (<?php echo $rowCategory['totalCategories'];?>)</h2>
        </a>
      </div>
      <div class="product-subcategory-box">
        <a href="subcategory">
          <h2>Apakškategorijas (<?php echo $rowSubcategory['totalSubcategories'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="order">
          <h2>Pasūtījumi (<?php echo $rowOrder['totalOrders'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="message">
          <h2>Ziņas (<?php echo $rowMessage['totalMessages'];?>)</h2>
        </a>
      </div>
      <?php } else { ?>
        <!-- Administratoram pieejams -->
        <div class="product-box">
        <a href="product">
          <h2>Produkti (<?php echo $rowProduct['totalProducts'];?>)</h2>
        </a>
      </div>
      <div class="product-category-box">
        <a href="category">
          <h2>Kategorijas (<?php echo $rowCategory['totalCategories'];?>)</h2>
        </a>
      </div>
      <div class="product-subcategory-box">
        <a href="subcategory">
          <h2>Apakškategorijas (<?php echo $rowSubcategory['totalSubcategories'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="order">
          <h2>Pasūtījumi (<?php echo $rowOrder['totalOrders'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="message">
          <h2>Ziņas (<?php echo $rowMessage['totalMessages'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="employee">
          <h2>Darbinieki (<?php echo $rowEmployee['totalEmployees'];?>)</h2>
        </a>
      </div>
      <div class="product-order-box">
        <a href="customer">
          <h2>Lietotāji (<?php echo $rowUser['totalUsers'];?>)</h2>
        </a>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php require_once('footer.php'); ?>