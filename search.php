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
        $somethingisset = false;
        $querystring = "SELECT SID, shopname, category, location_longitude, location_latitude FROM shop 
                        WHERE shopname LIKE :shopname AND category LIKE :category AND SID=(
                            SELECT SID FROM product WHERE product_name LIKE :meal)
                        )";
        if (isset($_REQUEST['shopname'])) $shopname = "%" . $_REQUEST['shopname'] . "%";
        else $shopname = "%%";
        if (isset($_REQUEST['category'])) $category = "%" . $_REQUEST['category'] . "%";
        else $category = "%%";
        if (isset($_REQUEST['meal'])) $meal = "%" . $_REQUEST['meal'] . "%";
        else $meal = "%%";
        // if (isset($_REQUEST['distance'])) {
        //     if ($somethingisset) $querystring += " AND ";
        //     if ($_REQUEST['distance'] == "near") $querystring += ""
        //     if ($_REQUEST['distance'] == "medium")
        //     if ($_REQUEST['distance'] == "far")
        // }
        $sql = $db->prepare($querystring);
        $sql->execute(array(
            'shopname' => $shopname,
            'category' => $category,
            'meal' => $meal
        ));
        $result = $sql->fetchAll();
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
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
