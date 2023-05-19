<?php 
session_start();
include("includes/config.php");
if(isset($_SESSION['admin_id'])=="") {
  header("Location: login");
} 
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/font-awesome-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    <link rel="icon" type="image/png" href="../assets/img/favicon-32x32.png">
  </head>
  <body>
  <header>
    <div class="top-header" id="topHeader">
      <div class="mobile-sidebar">
        <div class="header-mobile-navbar-section">
          <a href="javascript:void(0);" class="navbar-icon" onclick="toggleNavbar()">
            <i class="fa-solid fa-bars"></i>
          </a>
        </div>
      </div>
      <div class="header-login-section">
        <a href="login.php">
        <i class="fa fa-regular fa-user"></i>
        <div class="header-login-section-title">
          <span>Profils</span>
        </div>
        </a>
        <div class="header-login-section-profile">
          <li><a href="profile">Mans profils</a></li>
          <li><a href="logout">Iziet</a></li>
        </div>
      </div>
      <div class="header-notification-section">
        <i class="fa fa-regular fa-bell notification-icon" onclick="toggleNotificationSection()"></i>
        <div id="notificationBox" class="header-notification">
          <?php
          if(isset($_SESSION['orderNotificationCancel']) && !empty($_SESSION['orderNotificationCancel']) || isset($_SESSION['orderNotificationCreate']) && !empty($_SESSION['orderNotificationCreate'])) {
            if(isset($_SESSION['orderNotificationCancel'])) {
              $orderNotificationCanceled = $_SESSION['orderNotificationCancel'];
              //foreach($orderNotificationCanceled as $notification) {
              echo '<div class="row" data-notification-type="cancel">';
                echo '<div class="col">';
                  echo '<span id="notificationMessage" class="notificationMessage">'.$orderNotificationCanceled['message'].'</span></br>';
                  echo '<span id="notificationMessage" class="notificationMessage">Pasūtījums atcelts: '.$orderNotificationCanceled['datetime'].'</span>';
                echo '</div>';
                echo '<div class="col col-right">';
                  echo '<i id="notificationDeleteBtn" class="fa-solid fa-trash notificationDeleteBtn"></i></br>';
                echo '</div>';
              echo '</div>';
              //}  
            }
            if(isset($_SESSION['orderNotificationCreate'])) {
              $orderNotificationCreated = $_SESSION['orderNotificationCreate'];
              //foreach($orderNotificationCreated as $notification) {
              echo '<div class="row" data-notification-type="create">';
                echo '<div class="col">';
                  echo '<span id="notificationMessage" class="notificationMessage">'.$orderNotificationCreated['message'].'</span></br>';
                  echo '<span id="notificationMessage" class="notificationMessage">Pasūtījums veikts: '.$orderNotificationCreated['datetime'].'</span>';
                echo '</div>';
                echo '<div class="col col-right">';
                  echo '<i id="notificationDeleteBtn" class="fa-solid fa-trash notificationDeleteBtn"></i></br>';
                echo '</div>';
              echo '</div>';
              //}
            }
          } else {
            echo "<span>Nav jaunu paziņojumu!</span>";
          }
          ?>
        </div>
      </div>
    </div>  
    <nav>
      <div class="sidebar" id="sidebar">
        <div class="sidebar-inner">
          <?php if($_SESSION['user_role'] == 'employee') {?>
          <!-- Darbinieku navigacija -->
          <ul>
            <li><a href="https://badmintonaveikals.shop/admin/">Panelis</a></li>
            <li><a href="product">Produkti</a></li>
            <li><a href="category">Produktu kategorijas</a></li>
            <li><a href="subcategory">Produktu apakškategorijas</a></li>
            <li><a href="order">Pasūtījumi</a></li>
            <li><a href="message">Ziņas</a></li>
          </ul>
          <?php } else { ?>
          <!-- Administratora navigacija -->
          <ul>
            <li><a href="https://badmintonaveikals.shop/admin/">Panelis</a></li>
            <li><a href="product">Produkti</a></li>
            <li><a href="category">Produktu kategorijas</a></li>
            <li><a href="subcategory">Produktu apakškategorijas</a></li>
            <li><a href="order">Pasūtījumi</a></li>
            <li><a href="message">Ziņas</a></li>
            <li><a href="employee">Darbinieki</a></li>
            <li><a href="customer">Lietotāji</a></li>
          </ul>
          <?php } ?>
        </div>  
      </div>
    </nav>
    <div class="overlay" id="overlay" onclick="overlayClose()"></div>  
  </header>        