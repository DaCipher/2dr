<?php
// DB Connection
define("DB_HOST", "localhost");
define("DB_NAME", "spyrglje_fxtiq");
define("DB_USERNAME", "spyrglje_fxtiq");
define("DB_PASS", "FrancisK123");
$conn = new mysqli(DB_HOST, DB_USERNAME, DB_PASS, DB_NAME);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



// Validate all inputs
function input($data)
{
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  //$data = str_replace(" ", "", $data);
  return $data;
};
// validate Names
function field($input)
{
  if (empty($input) || $input === null || !preg_match("/^[a-zA-Z]*$/", $input)) {
    return false;
  } else {
    return true;
  }
};

// validate username
function username($input)
{
  if (empty($input) || $input === null || !preg_match("/^[A-Za-z][A-Za-z0-9]{4,20}$/", $input)) {
    return false;
  } else {
    return true;
  }
};



// validate Mail
function email($mail)
{
  if (empty($mail) || $mail === null || !filter_var($mail, FILTER_VALIDATE_EMAIL)) {
    return false;
  } else {
    return true;
  }
};

// Search for field in DB to avoid duplication
function exist($column, $table, $data2)
{
  global $conn;
  $data2 = strtolower($data2);
  $sql = "SELECT $column FROM $table WHERE $column =  '$data2'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    return true;
  } else {
    return false;
  }
};

// get single record from DB

function getSingleRecord($table, $column, $data)
{
  global $conn;
  $sql = "SELECT * FROM $table WHERE $column = '$data'";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row;
  } else {
    return false;
  }
};

// Check if Admin
function isAdmin($table, $id)
{
  global $conn;
  $sql = "SELECT * FROM $table WHERE user_id = $id";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    return $row;
  } else {
    return false;
  }
};

function withdrawal($data)
{
  global $conn;
  if ($data["method"] == "bank") {
    $sql = "INSERT INTO transactions (user_id, type, amount, method, acc_number, bank_name, swift, date, status) VALUES (? , ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssss", $data['id'], $data['type'], $data['amount'], $data['method'], $data['iban'], $data['bank_name'], $data['swift'], $data['date'], $data['status']);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  } else if ($data["method"] == "wallet") {
    $sql = "INSERT INTO transactions (user_id, type, amount, method, date, status) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $data['id'], $data['type'], $data['amount'], $data['method'], $data['date'], $data['status']);
    if ($stmt->execute()) {
      return true;
    } else {
      return false;
    }
  }
}

function resetCode($field, $id)
{
  global $conn;
  $code = mt_rand(000000, 999999);
  $sql = "UPDATE investment SET $field = '$code' WHERE user_id = $id";
  $conn->query($sql);
}
function getImageFileType($file)
{
  return strtolower(pathinfo($file, PATHINFO_EXTENSION));
}

function validateImage($file)
{
  if (getimagesize($file['tmp_name'])) {
    return true;
  } else {
    return false;
  }
}
function imageExist($user_id)
{
  global $conn;
  $sql = "SELECT user_id FROM `id_upload` WHERE user_id = '$user_id'";
  $query = $conn->query($sql);
  if ($query->num_rows > 0) {
    return true;
  } else {
    return false;
  }
}

function checkImageSize($file)
{
  if ($file['size'] > 2000000) {
    return false;
  } else {
    return true;
  }
}

function changeImageName($file)
{

  return strtolower($_SESSION['username']) . "-" . $file;
}

function storeImage($from, $to)
{
  if (move_uploaded_file($from, $to)) {
    return true;
  } else {
    return false;
  }
}

function SaveImage($user_id, $front, $back)
{
  global $conn;
  $sql = "INSERT INTO id_upload (user_id, dir_front, dir_back) VALUES ('$user_id', '$front', '$back')";
  $sql2 = "UPDATE users SET status = 'verifying' WHERE id = '$user_id'";
  $conn->query($sql);
  $conn->query($sql2);
}

function checkStatus($user_id)
{
  global $conn;
  $sql = "SELECT * FROM users WHERE id = '$user_id'";
  $query = $conn->query($sql);
  if ($query->num_rows > 0) {
    $row = $query->fetch_assoc();
    return $row;
  } else {
    return false;
  }
}
