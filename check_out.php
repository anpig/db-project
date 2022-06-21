<?php
    session_start();
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
    $UID = $_SESSION['UID'];
    $SID=$_POST['SID'];
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $total=$_POST['total'];
    $sub_total=$_POST['sub_total'];
    $dist = $_POST['dist'];
    $type = $_POST['type'];

    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
    $sql = $db->query("SELECT balance FROM user WHERE UID = $UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $balance = $row['balance'];
    }
    if($total > $balance) {
        echo "<script>alert(\"訂購失敗：訂購金額 > 餘額\"); window.location.replace(\"nav.php\");</script>";
        exit();
    }
    $sql = $db->query("INSERT INTO `orders` (`OID`, `UID`, `SID`, `status`, `create_time`, `finish_time`, `distance`, `total_price`, `type`) VALUES (NULL, '$UID', '$SID', 'unfinished', current_timestamp(), NULL, '$dist', '$total', '$type');");
    $sql = $db->query("SELECT LAST_INSERT_ID() AS tem;");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $OID = $row['tem'];
    }

    $sql = $db->query("SELECT * FROM product WHERE SID = $SID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $old_quantiy = $row['quantity'];
        $PID = $row['PID'];
        $order_quantity = $_POST["$PID"];
        if ($order_quantity == 0) continue;
        $new_quantity = $old_quantiy - $order_quantity;
        $sql = $db->query("INSERT INTO `items` (`OID`, `PID`, `quantity`) VALUES ('$OID', '$PID', '$order_quantity')");
        $sql = $db->query("UPDATE `product` SET `quantity` = '$new_quantity' WHERE `product`.`PID` = $PID;");
    }

    $sql = $db->query("SELECT * FROM shop WHERE SID = $SID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $shop_name = $row['shopname'];
        $shop_owner_UID = $row['UID'];
    }

    $sql = $db->query("SELECT name FROM user WHERE UID = $UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $client_name = $row['name'];
    }
    
    $neg_total = -1*$total;
    $sql = $db->query("INSERT INTO `transaction` (`TID`, `UID`, `type`, `value`, `time`, `trader`) VALUES (NULL, '$shop_owner_UID', 'Receive', '$sub_total', current_timestamp(), '$client_name')");
    $sql = $db->query("INSERT INTO `transaction` (`TID`, `UID`, `type`, `value`, `time`, `trader`) VALUES (NULL, '$UID', 'Payment', '$neg_total', current_timestamp(), '$shop_name')");
    
    $sql = $db->query("SELECT * FROM user WHERE UID = $UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $old = $row['balance'];
    }
    $new = $old - $total;
    $sql = $db->query("UPDATE user SET balance=$new WHERE `user`.`UID`=$UID");

    $sql = $db->query("SELECT * FROM user WHERE UID=$shop_owner_UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $old = $row['balance'];
    }
    $new = $old + $sub_total;
    $sql = $db->query("UPDATE user SET balance=$new WHERE `user`.`UID`=$shop_owner_UID");

    echo "<script>alert(\"訂購成功\"); window.location.replace(\"nav.php\");</script>";
?>