<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $OID = $_POST['OID'];
    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
    $sql = $db->query("UPDATE `orders` SET `status` = 'cancel', `finish_time` = current_timestamp() where `orders`.`OID` = '$OID'");
    
    $sql = $db->query("SELECT * FROM orders where `orders`.`OID` = '$OID'");
    $result = $sql->fetch();
    $UID = $result['UID'];
    $SID = $result['SID'];
    $total_price = $result['total_price'];

    $sql = $db->query("select * from user where UID = $UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $old = $row['balance'];
    }
    $new = $old + $total_price;
    $sql = $db->query("UPDATE user SET balance = $new WHERE `user`.`UID` = $UID");

    $sql = $db->query("SELECT * FROM items where `OID` = '$OID'");
    $result = $sql->fetchAll();
    $sub_total = 0;
    foreach ($result as &$row) {
        $PID = $row['PID'];
        $quantity = $row['quantity'];
        $tem = $db->query("select * from product where PID = '$PID'");
        $tem1 = $tem->fetch();
        $price = $tem1['price'];
        $old_quantity = $tem1['quantity'];
        $sub_total += $price*$quantity;
        $new_quantity = $old_quantity + $quantity;
        $sql = $db->query("UPDATE `product` SET `quantity` = '$new_quantity' WHERE `product`.`PID` = $PID;");
    }

    $sql = $db->query("select * from shop where SID = $SID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $shop_name = $row['shopname'];
        $shop_owner_UID = $row['UID'];
    }

    $sql = $db->query("select name from user where UID = $UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $client_name = $row['name'];
    }

    $neg_subtotal = -1*$sub_total;

    $sql = $db->query("INSERT INTO `transaction` (`TID`, `UID`, `type`, `value`, `time`, `trader`) VALUES (NULL, '$shop_owner_UID', 'Payment', '$neg_subtotal', current_timestamp(), '$client_name')");
    $sql = $db->query("INSERT INTO `transaction` (`TID`, `UID`, `type`, `value`, `time`, `trader`) VALUES (NULL, '$UID', 'Receive', '$total_price', current_timestamp(), '$shop_name')");

    $sql = $db->query("select * from user where UID = $shop_owner_UID");
    $result = $sql->fetchAll();
    foreach ($result as &$row) {
        $old = $row['balance'];
    }
    $new = $old - $sub_total;
    $sql = $db->query("UPDATE user SET balance = $new WHERE `user`.`UID` = $shop_owner_UID");

    echo "<script>alert(\"取消成功\"); window.location.replace(\"nav.php#my_order\");</script>";
?>