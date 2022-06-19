<?php
    session_start();
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $SID=$_POST['SID'];
    $dist = $_POST['dist'];
    $type = $_POST['select'];
    $delivery_fee = 0;
    if ($type == "Delivery") {
        $delivery_fee = intval($dist*10);
        if($delivery_fee < 10) $delivery_fee = 10;
    }
    try {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
        $sql = $db->query("select * from product where SID = $SID");
        $result = $sql->fetchAll();
        if ($sql->rowCount() == 0) {
            echo "<script>alert(\"該店家沒有商品\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        echo "<h3>Order</h3>";
        echo <<< EOT
        <form action="check_out.php" class="fh5co-form animate-box" data-animate-effect="fadeIn" method="post">
        <table>
            <thead>
                <tr>
                    <th width="50%" scope="col">Picture</th>
                    <th width="20%" scope="col">Meal Name</th>
                    <th width="15%" scope="col">Price</th>
                    <th width="15%" scope="col">Order Quantity</th>
                </tr>
            </thead>
            <tbody>
        EOT;
        $sub_total = 0;
        foreach ($result as &$row) {
            $PID = $row['PID'];
            $order_quantity = $_POST["$PID"];
            if ($order_quantity == 0) {
                echo <<< EOT
                    <input type="hidden" name="$PID" value="$order_quantity">
                EOT;
                continue;
            };
            $product_name = $row['product_name'];
            $price = $row['price'];
            $picture = $row['picture'];
            $picture_type = $row['picture_type'];
            $sub_total += $price*$order_quantity;
            echo '<tr><td><img style="max-width:100%; max-height:200px" src="data:'.$picture_type.';base64,' . $picture . '"  alt="$product_name"/></td>';
            echo <<< EOT
                    <td>$product_name</td>
                    <td>$price</td>
                    <td>$order_quantity</td>
                    <input type="hidden" name="$PID" value="$order_quantity">
                </tr>
            EOT;
        }
        if($sub_total == 0) {
            echo "<script>alert(\"請選擇商品\"); window.location.replace(\"nav.php\");</script>";
        }
        echo <<< EOT
            </tbody>
        </table>
        EOT;
        $total = $sub_total + $delivery_fee;
        echo <<< EOT
        <br>
        Subtotal : \$$sub_total <br>
        Delivery fee : \$$delivery_fee <br>
        Total Price : \$$total <br>
        <div class="form-group">
            <input type="hidden" name="SID" value="$SID">
            <input type="hidden" name="total" value="$total">
            <input type="hidden" name="dist" value="$dist">
            <input type="hidden" name="type" value="$type">
            <input type="hidden" name="sub_total" value="$sub_total">
            <input type="submit" value="Order" class="btn btn-primary">
        </div>
        </form>
        EOT;
    }
    catch (Exception $e) {
        $msg=$e->getMessage();
        session_unset();
        session_destroy();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }
?>