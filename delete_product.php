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
    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
    $sql = $db->prepare("UPDATE `product` SET listed=0 WHERE `product`.`PID` = :PID");
    $sql->execute(array('PID' => $PID));
    throw new Exception("DELETE Success!");
    header('Location: nav.php#shop');
  } catch (Exception $e) {
      $msg=$e->getMessage();
      echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php#shop\");</script>";
  }
?>