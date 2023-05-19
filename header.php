<?php
ob_start();
session_start();
include("admin/includes/config.php");
function showCartQty($conn) {
  if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $loggedIn = true;
  } else {
    $loggedIn = false;
  }
  if($loggedIn) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT SUM(productQty) as totalQty FROM cart WHERE userId = '$userId'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $totalQty = $row['totalQty'];
    if($totalQty > 0) {
      echo '<span class="cart-quantity">'.$totalQty.'</span>';
    }
  }
}
function displayCartData($conn) {
//parada pirkuma groza datus
  if(isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $loggedIn = true;
  } else {
    $loggedIn = false;
  }
    if($loggedIn) {
      $sql = "SELECT * FROM cart INNER JOIN product ON cart.productId = product.id WHERE cart.userId = '$userId'";
      $result = mysqli_query($conn, $sql);
      if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
          echo '<div class="row">';
            echo '<div class="col">';
              echo '<div class="cart-item">' . $row['name'] . '</div>';
              if($row['discount'] != 0.00) {
                echo '<div class="cart-item">'; 
                echo '<span style="color:#bbb;"><s>' . $row['price'] .'€</s></span></br>';
                echo '<span>' .$row['discount']. '€</span>';
                echo '</div>';
              } else {
                echo '<div class="cart-item"><span class="product-price">' . $row['price']. '€</span></div>';
              }
            echo '</div>';
            echo '<div class="col">';
              echo '<div class="cart-item">'.'<img class="featured-img" src="assets/img/'.$row['featuredImage'].'">'.'</div>';
            echo '</div>';
          echo '</div>';
        }
      } 
    }
    else {
    echo '<div id="error-message" style="display:block;">Ir jāpieslēdzās, lai skatītu groza saturu</div>';
  }       
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Badmintona preču e-veikals">
    <title>Badmintona internetveikals</title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/font-awesome-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="assets/img/favicon-32x32.png">
  </head>
  <body>
    <header>
      <div class="top-header">
        <div class="header-logo-wrap">
          <a href="https://badmintonaveikals.shop/">
            <img class="header-logo" src="assets/img/logo-black-transparent.png" alt="logo">
          </a>
        </div>
        <!-- meklesanas lauks -->
        <div class="search-box" id="searchBox">
          <form class="search-bar">
            <div class="search-bar-input">
              <input id="searchWord" type="text" placeholder="Meklēt" onkeyup="search()">
            </div>  
          </form>
          <div id="results" class="search-bar-results"></div>
        </div>  
        <div class="header-login-section">
          <a href="login">
            <i class="fa fa-regular fa-user"></i>
            <div class="header-login-section-title">
            <?php if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) { 
            //parada kad nav piesledzies profilam ?>
              <span>Pieslēgties</span>
            <?php } else { 
            //parada kad ir piesledzies profilam ?>
              <span>Profils</span>
            <?php } ?>
            </div>
          </a>
          <?php 
             if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {  
            //parada kad nav piesledzies profilam ?>
          <div class="header-login-section-authorisation">
            <li><a href="login">Pieslēgties</a></li>
            <li><a href="registration">Reģistrēties</a></li>
          </div>  
          <?php } 
          else { 
          //parada kad ir piesledzies profilam ?>
          <div class="header-login-section-profile">
            <li><a href="profile">Mans profils</a></li>
            <li><a href="order">Pasūtījumi</a></li>
            <li><a href="logout">Iziet</a></li>
          </div> 
          <?php } ?> 
        </div>
        <div class="favourite-items">
          <a href="wishlist">
            <i class="fa fa-regular fa-heart"></i>
          </a>
        </div>
        <div class="cart-shopping-items">
          <a href="cart" class="cart-icon">
            <i id="cart-icon" class="fa fa-cart-shopping"></i> <!-- fa-regular not working -->
            <div id="cart-items">
              <?php displayCartData($conn); ?>
            </div>
            <div class="cart-quantity">
              <?php showCartQty($conn); ?>
            </div>
          </a>
        </div>
      </div>
      <nav class="navbar navbar-desktop">
        <ul class="navbar-inner">
          <li><a href="https://badmintonaveikals.shop/">Sākums</a></li>
          <li><a href="product?category=raketes">Raketes</a></li>
          <li><a href="product?category=apavi">Apavi</a></li>
          <div class="dropdown">
          <li><a href="product?category=apgerbi">Apģērbi</a></li>
            <div class="dropdown-content">
              <a href="product?category=apgerbi&subcategory=viriesu">Vīriešu</a>
              <a href="product?category=apgerbi&subcategory=sieviesu">Sieviešu</a>
              <a href="product?category=apgerbi&subcategory=zenu">Zēnu</a>
              <a href="product?category=apgerbi&subcategory=meitenu">Meiteņu</a>
            </div>
          </div>
          <li><a href="product?category=volanini">Volāniņi</a></li>
          <li><a href="product?category=somas">Somas</a></li> 
          <li><a href="product?category=stigas">Stīgas</a></li>
          <li><a href="product?category=gripi">Gripi</a></li> 
          <div class="dropdown">
          <li><a href="product?category=aksesuari">Aksesuāri</a></li>
            <div class="dropdown-content">
              <a href="product?category=aksesuari&subcategory=zekes">Zeķes</a>
              <a href="product?category=aksesuari&subcategory=cepures-un-nadzini">Cepures/Nadziņi</a>
              <a href="product?category=aksesuari&subcategory=sviedru-lentas">Sviedru lentas</a>
              <a href="product?category=aksesuari&subcategory=dvieli">Dvieļi</a>
              <a href="product?category=aksesuari&subcategory=aksesuari-aksesuari">Aksesuāri</a>
            </div>
          </div> 
          <li><a href="product-discount">Akcijas</a></li>
          <li><a href="contact">Kontakti</a></li>
          </ul>
        </nav>
        <nav class="navbar navbar-mobile">        
        <ul class="navbar-mobile-inner" id="mobileNavbar">
          <li><a href="https://badmintonaveikals.shop/">Sākums</a></li>
          <li><a href="product?category=raketes">Raketes</a></li>
          <li><a href="product?category=apavi">Apavi</a></li>
          <li>
            <div class="dropdown-container">
              <button class="dropdown-btn" onclick="toggleSubmenu()">Apģērbi
                <i class="fa-solid fa-caret-down"></i>
              </button>
              <div class="dropdown-content">
                <a href="product?category=apgerbi&subcategory=viriesu">Vīriešu</a>
                <a href="product?category=apgerbi&subcategory=sieviesu">Sieviešu</a>
                <a href="product?category=apgerbi&subcategory=zenu">Zēnu</a>
                <a href="product?category=apgerbi&subcategory=meitenu">Meiteņu</a>
              </div>
            </div>
          </li>
          <li><a href="product?category=volanini">Volāniņi</a></li>
          <li><a href="product?category=somas">Somas</a></li> 
          <li><a href="product?category=stigas">Stīgas</a></li>
          <li><a href="product?category=gripi">Gripi</a></li> 
          <li>
            <div class="dropdown-container">
              <button class="dropdown-btn" onclick="toggleSubmenu()">Aksesuāri
                <i class="fa-solid fa-caret-down"></i>
              </button>
              <div class="dropdown-content">
                <a href="product?category=aksesuari&subcategory=zekes">Zeķes</a>
                <a href="product?category=aksesuari&subcategory=cepures-un-nadzini">Cepures/Nadziņi</a>
                <a href="product?category=aksesuari&subcategory=sviedru-lentas">Sviedru lentas</a>
                <a href="product?category=aksesuari&subcategory=dvieli">Dvieļi</a>
                <a href="product?category=aksesuari&subcategory=aksesuari-aksesuari">Aksesuāri</a>
              </div>
            </div>
          </li> 
          <li><a href="product-discount">Akcijas</a></li>
          <li><a href="contact">Kontakti</a></li>
        </ul>
        <div>
          <a href="javascript:void(0);" class="navbar-icon" onclick="toggleNavbar()">
            <i class="fa fa-solid fa-bars"></i>
          </a>
        </div>
      </nav>
    </header>
    