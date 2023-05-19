<?php
session_start();
if(isset($_GET['type'])) {
  $type = $_GET['type'];
  switch($type) {
    case 'cancel':
      unset($_SESSION['orderNotificationCancel']);
      break;
    case 'create':
      unset($_SESSION['orderNotificationCreate']);
      break;
    default:
      break;
  }
}
?>