<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("includes/config.php");
require 'includes/phpmailer/src/Exception.php';
require 'includes/phpmailer/src/PHPMailer.php';
require 'includes/phpmailer/src/SMTP.php';
$error = false;
if(isset($_POST['forgotPassword'])) {
  $email = $_POST['email'];
  if(empty($email)) {
    $_SESSION['messageFieldEmpty'] = 'Lūdzu ievadiet e-pastu';
    header("Location: ".$_SERVER['HTTP_REFERER']);
    $error = true;
  }
  $query = "SELECT * FROM adminuser WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  if($email && mysqli_num_rows($result) == 0) {
    $_SESSION['userNotFound'] = 'Netika atrasts lietotājs ar šādu e-pastu';
    header("Location: ".$_SERVER['HTTP_REFERER']);
    $error = true;
  }
    if($result && $error == false) {
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'aigars.reinholds1@gmail.com';
      $mail->Password = 'pwfoungfqejzlfbn';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;
      $mail->isHTML(true);
      $mail->setFrom($email);
      $mail->addAddress($email);
      $mail->Subject = "Paroles atiestatisana";
      $mail->Body = "Mēs saņēmām pieprasījumu par paroles atiestatīšanu! <br>Lai atiestatītu paroli, spiediet uz saiti zemāk: <br>
      <a href='https://badmintonaveikals.shop/admin/reset-password?email=$email'>Atiestatīt paroli</a>";
      $mail->send();
      $_SESSION['messageSuccess'] = 'Ziņa ir veiksmīgi nosūtīta';
      header("Location: ".$_SERVER['HTTP_REFERER']);
    } else {
      if(!empty($email) && $error == false) {
        $_SESSION['messageFailed'] = 'Ziņa netika nosūtīta';
        header("Location: ".$_SERVER['HTTP_REFERER']);
      }       
    }
}
?>