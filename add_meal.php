<?php
  session_start();
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
  $UID = $_SESSION['UID'];
  $dbservername='localhost';
  $dbname='db-project';
  $dbusername='db';
  $dbpassword='db';
  $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
  //開啟圖片檔
  try { 
    if (empty($_POST['product_name']) || empty($_POST['price']) || empty($_POST['quantity']) || empty($_FILES["picture"]["tmp_name"])) {
      $err_message="";
      if (empty($_POST['product_name'])) $err_message=$err_message."PRODUCT_NAME".'\n';
      if (empty($_POST['price'])) $err_message=$err_message."PRICE".'\n';
      if (empty($_POST['quantity'])) $err_message=$err_message."QUANTITY".'\n';
      if (empty($_FILES["picture"]["tmp_name"])) $err_message=$err_message.'請選擇檔案\n';
      throw new Exception('空白欄位：\n'."$err_message");
    }
    if (!ctype_digit($_POST['price']) || !ctype_digit($_POST['quantity']) || $_POST['price'] < 0 || $_POST['quantity'] < 0) {
      throw new Exception("格式錯誤，價格與數量需為非負整數");
    }

    $sql = $db->prepare("SELECT * FROM user WHERE UID=:UID");
    $sql->execute(array('UID' => $UID));
    $row = $sql->fetch();
    if (!$row['role']) {
      header('Location: nav.php#shop');
      die();
    }
    $sql = $db->prepare("SELECT SID FROM shop WHERE UID=:UID");
    $sql->execute(array('UID' => $UID));
    $row = $sql->fetch();
    $SID = $row['SID'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    $file = fopen($_FILES["picture"]["tmp_name"], "rb");
    // 讀入圖片檔資料
    $fileContents = fread($file, filesize($_FILES["picture"]["tmp_name"])); 
    //關閉圖片檔
    fclose($file);
    //讀取出來的圖片資料必須使用base64_encode()函數加以編碼：圖片檔案資料編碼
    $fileContents = base64_encode($fileContents);
    //組合查詢字串
    $imgType = $_FILES["picture"]["type"];
    $msg = $fileContents;
    // echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
    $sql = $db->prepare("
      INSERT into product (SID, product_name, price, quantity, picture, picture_type) 
      VALUES (:SID, :product_name, :price, :quantity, :picture, :picture_type)
    ");
    $sql->execute(
      array(
        'SID' => $SID,
        'product_name' => $product_name,
        'price' => $price,
        'quantity' => $quantity,
        'picture' => $fileContents,
        'picture_type' => $imgType
      )
    );
    header('Location: nav.php#shop');
  } catch (Exception $e) {
      $msg=$e->getMessage();
      echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
  }
?>