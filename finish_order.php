<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $OID = $_POST['OID'];
    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
    $sql = $db->query("UPDATE `orders` SET `status` = 'finished', `finish_time` = current_timestamp() where `orders`.`OID` = '$OID'");

    echo "<script>alert(\"訂單完成\"); window.location.replace(\"nav.php#shop_order\");</script>";
?>