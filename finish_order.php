<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $OID = $_POST['OID'];
    $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception

    $sql = $db->query("SELECT * FROM orders where `orders`.`OID` = '$OID'");

    $result = $sql->fetch();
    $status = $result['status'];
    if ($status == 'canceled') {
        echo "<script>alert(\"該訂單已被取消\"); window.location.replace(\"nav.php#shop_order\");</script>";
        exit();
    }
    else {
        $sql = $db->query("SELECT * FROM items WHERE OID=$OID");
        $result = $sql->fetchAll();
        foreach ($result as &$row) {
            $PID = $row['PID'];
            $order_quantity = $row['quantity'];
            $sql = $db->query("SELECT * FROM product WHERE PID=$PID");
            $tmp = $sql->fetch();
            $product_existence = $tmp['listed'];
            $product_quantity = $tmp['quantity'];
            if ($order_quantity > $product_quantity) {
                echo "<script>alert(\"有產品庫存不足\"); window.location.replace(\"nav.php#shop_order\");</script>";
                exit();
            }
            else if (!$product_existence) {
                echo "<script>alert(\"有產品已不存在\"); window.location.replace(\"nav.php#shop_order\");</script>";
                exit();
            }
        }
        $sql = $db->query("UPDATE `orders` SET `status` = 'finished', `finish_time` = current_timestamp() where `orders`.`OID` = '$OID'");
        echo "<script>alert(\"訂單完成\"); window.location.replace(\"nav.php#shop_order\");</script>";
    }
?>