<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
include("admin/includes/config.php");
require 'admin/includes/phpmailer/src/Exception.php';
require 'admin/includes/phpmailer/src/PHPMailer.php';
require 'admin/includes/phpmailer/src/SMTP.php';
if(isset($_POST['sendMessage'])) {
  $name = htmlentities($_POST['name']);
  $email = htmlentities($_POST['email']);
  $subject = htmlentities($_POST['subject']);
  $message = htmlentities($_POST['message']);
  if(empty($name) || empty($email) || empty($subject) || empty($message)) {
    $_SESSION['messageFieldEmpty'] = 'Visi lauki ir obligāti';
    header("Location: ".$_SERVER['HTTP_REFERER']."#contactSection");
    //exit;
  } else {
    $sqlMessage = "INSERT INTO message (name, email, subject, message) VALUES ('$name', '$email', '$subject', '$message')";
    $resultMessage = mysqli_query($conn, $sqlMessage);
    }
    if($resultMessage) {
      $mail = new PHPMailer(true);
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username = 'aigars.reinholds1@gmail.com';
      $mail->Password = 'pwfoungfqejzlfbn';
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;
      $mail->isHTML(true);
      $mail->setFrom($email, $name);
      $mail->addAddress('aigars.reinholds1@gmail.com');
      $mail->Subject = ("$email ($subject)");
      $mail->Body = $message;
      $mail->send();
      $_SESSION['messageSuccess'] = 'Ziņa ir veiksmīgi nosūtīta';
      header("Location: ".$_SERVER['HTTP_REFERER']."#contactSection");
    } else {
      if(!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $_SESSION['messageFailed'] = 'Ziņa netika nosūtīta';
        header("Location: ".$_SERVER['HTTP_REFERER']."#contactSection");
      }       
    }
}
$error = false;
if(isset($_POST['forgotPassword'])) {
  $email = $_POST['email'];
  if(empty($email)) {
    $_SESSION['messageFieldEmpty'] = 'Ievadiet e-pasta adresi';
    header("Location: ".$_SERVER['HTTP_REFERER']);
    $error = true;
  }
  $query = "SELECT * FROM user WHERE email = '$email'";
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
    <a href='https://badmintonaveikals.shop/reset-password?email=$email'>Atiestatīt paroli</a>";
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