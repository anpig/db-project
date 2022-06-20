<?php
    session_start();
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $UID=$_SESSION['UID'];
    include('cal_distance.php');
    try {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        echo <<< EOT
            <table class="table" style="margin-top: 15px">
                <thead>
                    <tr>
                        <th width="5%" scope="col">OID</th>
                        <th width="10%" scope="col">Status</th>
                        <th width="15%" scope="col">Start</th>
                        <th width="15%" scope="col">End</th>
                        <th width="15%" scope="col">Shop Name</th>
                        <th width="15%" scope="col">Total Price</th>
                        <th width="15%" scope="col">Order Details</th>
                        <th width="15%" scope="col">Action</th>
                    </tr>
                </thead>
            <tbody>
        EOT;
        if (isset($_REQUEST['status'])) {
            $status = $_REQUEST['status'];
            $sql = $db->query("select * from orders where UID = '$UID' and status = '$status'");
        }
        else {
            $sql = $db->query("select * from orders where UID = '$UID'");
        }
        $result = $sql->fetchAll();
        $OID_arr = array();
        foreach ($result as &$row) {
            $SID = $row['SID'];
            $OID = $row['OID'];
            $status = ucfirst($row['status']); 
            $start = $row['create_time'];
            $end = $row['finish_time'];
            $tem = $db->query("select shopname from shop where SID = '$SID'");
            $tem1 = $tem->fetch();
            $shopname = $tem1['shopname'];
            $total_price = $row['total_price'];
            echo <<< EOT
                <tr>
                    <td>$OID</td>
                    <td>$status</td>
                    <td>$start</td>
                    <td>$end</td>
                    <td>$shopname</td>
                    <td>$total_price</td>
                    <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#detail_$OID">Order Details</button></td>
            EOT;
            if ($status == 'unfinished') {
                echo <<< EOT
                    <td>
                        <form action="cancel_order.php" method="post">
                        <input type="hidden" name="OID" value="$OID">
                        <button type="submit" class="btn btn-danger">Cancel</button>
                        </form>
                    </td>
                    </tr>
                </tbody>
                EOT;
            }
            else echo "</tr></tbody>";
            array_push($OID_arr, $OID);
        }
        foreach ($OID_arr as $OID) {
            echo <<< EOT
                </table>
                    <!-- Modal -->
                        <div class="modal fade" id=detail_$OID  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Order</h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <table class="table" style="margin-top: 15px; table-layout:fixed;">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Picture</th>
                                                            <th scope="col">Meal Name</th>
                                                            <th scope="col">Price</th>
                                                            <th scope="col">Order Quantity</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
            EOT;
                                                    $tem = $db->query("select * from orders where OID = '$OID'");
                                                    $tem1 = $tem->fetch();
                                                    $total_price = $tem1['total_price'];

                                                    $sql = $db->query("SELECT * FROM items WHERE OID='$OID'");
                                                    $itemrow = $sql->fetchAll();
                                                    $sub_total = 0;
                                                    foreach ($itemrow as &$row) {
                                                        $PID = $row['PID'];
                                                        $quantity = $row['quantity'];
                                                        $sql = $db->query("SELECT * FROM product WHERE PID='$PID'");
                                                        $result = $sql->fetch();
                                                        $picture_type = $result['picture_type'];
                                                        $picture = $result['picture'];
                                                        $product_name = $result['product_name'];
                                                        $price = $result['price'];
                                                        $sub_total += $price*$quantity;
                                                        echo '<tr><td><img style="max-width:100%; max-height:200px" src="data:'.$picture_type.';base64,' . $picture . '" alt="$product_name"/></td>';
                                                        echo <<< EOT
                                                                <td>$product_name</td>
                                                                <td>$price</td>
                                                                <td>$quantity</td>
                                                            </tr>
                                                        EOT;
                                                    }
                                                    $delivery_fee = $total_price - $sub_total;
                                                    echo <<< EOT
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="col-xs-12">
                                                <span style="float: left">Subtotal:</span>
                                                <span style="float: right">$sub_total</span>
                                            </div>
                                            <div class="col-xs-12">
                                                <span style="float: left">Delivery Fee:</span>
                                                <span style="float: right">$delivery_fee</span>
                                            </div>
                                            <div class="col-xs-12">
                                                <span style="float: left; font-weight: bold">Total Price:</span>
                                                <span style="float: right; font-weight: bold">$total_price</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            EOT;
        }
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
