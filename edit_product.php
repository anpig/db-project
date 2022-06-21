<?php
  session_start();
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
  $PID = $_POST['PID'];
  $UID = $_SESSION['UID'];
  $dbservername='localhost';
  $dbname='db-project';
  $dbusername='db';
  $dbpassword='db';

  try { 
    if (!isset($_POST['price']) || !isset($_POST['quantity'])) {
      $err_message="";
      if (!isset($_POST['price'])) $err_message=$err_message."PRICE".'\n';
      if (!isset($_POST['quantity'])) $err_message=$err_message."QUANTITY".'\n';
      throw new Exception('空白欄位：'.'\n'."$err_message");
    }
    if (!ctype_digit($_POST['price']) || !ctype_digit($_POST['quantity']) || $_POST['price'] < 0 || $_POST['quantity'] < 0) {
      throw new Exception("格式錯誤，價格與數量需為非負整數");
    }
    else {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

        $price=$_POST['price'];
        $quantity=$_POST['quantity'];
        //UPDATE `product` SET `price` = '10000', `quantity` = '10' WHERE `product`.`PID` = 1
        $sql = $db->prepare("UPDATE `product` SET price = :price, quantity = :quantity WHERE `product`.`PID` = :PID");
        $sql->execute(array('price' => $price, 'quantity' => $quantity, 'PID' => $PID));
        throw new Exception("EDIT Success!");
    }
    header('Location: nav.php#shop');
  } catch (Exception $e) {
      $msg=$e->getMessage();
      echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
  }
?>