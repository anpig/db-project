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
        $sql = $db->prepare("select * from user where UID=:UID");
        $sql->execute(array('UID' => $UID));
        $row = $sql->fetch();
        $user_latitude = $row['location_latitude']; $user_longitude = $row['location_longitude'];
        
        if (isset($_REQUEST['shopname'])) $shopname = "%" . $_REQUEST['shopname'] . "%";
        else $shopname = "%%";
        if (isset($_REQUEST['category'])) $category = "%" . $_REQUEST['category'] . "%";
        else $category = "%%";
        if (isset($_REQUEST['meal'])) $meal = "%" . $_REQUEST['meal'] . "%";
        else $meal = "%%";

        $price_floor = 0;
        $price_ceiling = 2147483647;
        if (isset($_REQUEST['price_floor'])) {
            $price_floor = $_REQUEST['price_floor'];
        }
        if (isset($_REQUEST['price_ceiling'])) {
            $price_ceiling = $_REQUEST['price_ceiling'];
        }
        if (isset($_REQUEST['price']) || isset($_REQUEST['meal'])) {
            $querystring = "SELECT DISTINCT SID, shopname, category, location_longitude, location_latitude
                            FROM shop NATURAL LEFT JOIN product
                            WHERE shopname LIKE :shopname 
                            AND category LIKE :category 
                            AND product_name LIKE :meal
                            AND price BETWEEN :price_floor AND :price_ceiling";
            $sql = $db->prepare($querystring);
            $sql->execute(array(
                'shopname' => $shopname,
                'category' => $category,
                'meal' => $meal,
                'price_floor' => $price_floor,
                'price_ceiling' => $price_ceiling
            ));
        }
        else {
            $querystring = "SELECT DISTINCT SID, shopname, category, location_longitude, location_latitude
                            FROM shop
                            WHERE shopname LIKE :shopname 
                            AND category LIKE :category";
            $sql = $db->prepare($querystring);
            $sql->execute(array(
                'shopname' => $shopname,
                'category' => $category,
            ));
        }
        $result = $sql->fetchAll();
        echo <<< EOT
            <table class="table" style=" margin-top: 15px;">
                <thead>
                    <tr>
                        <th scope="col">Shop Name</th>
                        <th scope="col">Shop Category</th>
                        <th scope="col">Distance</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody id="result-list">
        EOT;
        foreach ($result as &$row) {
            $shopname = $row['shopname'];
            $category = $row['category'];
            $SID = $row['SID'];
            $shop_latitude = $row['location_latitude'];
            $shop_longitude = $row['location_longitude'];
            $dis = cal_distance($user_latitude, $user_longitude, $shop_latitude, $shop_longitude);
            $distanceWord = "";
            if (isset($_REQUEST['distance'])) {
                if ($_REQUEST['distance'] == "near") {
                    $distanceWord = "Near";
                    if ($dis > 2) continue;
                }
                else if ($_REQUEST['distance'] == "medium") {
                    $distanceWord = "Medium";
                    if ($dis <= 2 || $dis >= 5) continue;
                }
                else if ($_REQUEST['distance'] == "far") {
                    $distanceWord = "Far";
                    if ($dis < 5) continue;
                }
            }
            else {
                if ($dis <= 2) $distanceWord = "Near";
                else if ($dis < 5) $distanceWord = "Medium";
                else $distanceWord = "Far";
            }
            echo <<< EOT
                <tr>
                    <td>$shopname</td>
                    <td>$category</td>
                    <td>$distanceWord</td>
                    <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#$SID">Open menu</button></td>
                </tr>
            EOT;
        }
        echo <<< EOT
                </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">menu</h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="  col-xs-12">
                                    <table class="table" style=" margin-top: 15px;">
                                        <thead>
                                            <tr>
                                                <th scope="col">Picture</th>
                                                <th scope="col">Meal Name</th>
                                                <th scope="col">Price</th>
                                                <th scope="col">Quantity</th>
                                                <th scope="col">Order check</th>
                                            </tr>
                                        </thead>
                                        <tbody>
        EOT;
        foreach ($result as &$row) {
            $SID = $row['SID'];
            $sql = $db->query("SELECT * FROM product WHERE SID=$SID");
            $shoprow = $sql->fetch();
            $product_name = $shoprow['product_name'];
            $price = $shoprow['price'];
            $quantity = $shoprow['quantity'];
            $picture = $shoprow['picture'];
            $picture_type = $shoprow['picture_type'];
            echo '<tr><td><img style="max-width:100%; max-height:200px" src="data:'.$picture_type.';base64,' . $picture . '" alt="$product_name"/></td>';
            echo <<< EOT
                    <td>$product_name</td>
                    <td>$price</td>
                    <td>$quantity</td>
                    <td><input type="checkbox" id="#$SID" value="$product_name"></td>
                </tr>
            EOT;
        }
        echo <<< EOT
                        </tbody>
                    </table>
                </div>
                    </div>
                        </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        EOT;
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
