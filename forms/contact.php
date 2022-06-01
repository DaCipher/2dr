<?php
require "../middleware/auth.php";
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$subject =  trim($_POST['subject']);
$message = trim($_POST['message']);
$sql = $conn->prepare('insert into contact (name, email, subject, message) values (?, ?, ?, ?)');
$sql->bind_param("ssss", $p_name, $p_email, $p_subject, $p_msg);
$p_name = $name;
$p_email = $email;
$p_subject = $subject;
$p_msg = $message;
if ($sql->execute()) {
  echo "OK";
} else {
  echo 'Something went wrong!';
}