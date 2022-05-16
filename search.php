<?php
    session_start();
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $UID=$_SESSION['UID'];

    function cal_distance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo) {
        // convert from degrees to radians
        $earthRadius = 6371;
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);
    
        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;
    
        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
          cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
      }

    try {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = $db->prepare("select * from user where UID=:UID");
        $sql->execute(array('UID' => $UID));
        $row = $sql->fetch();
        $user_latitude = $row['location_latitude']; $user_longitude = $row['location_longitude'];

        $somethingisset = false;
        $querystring = "SELECT SID, shopname, category, location_longitude, location_latitude FROM shop";
        if (isset($_REQUEST['shopname'])) {
            if ($somethingisset) $querystring .= " AND ";
            else $querystring .= " WHERE ";
            $shopname = "%" . $_REQUEST['shopname'] . "%";
            $querystring .= "shopname LIKE :shopname";
            $somethingisset = true;
        }
        // if (isset($_REQUEST['distance'])) {
        //     if ($somethingisset) $querystring += " AND ";
        //     if ($_REQUEST['distance'] == "near") $querystring += ""
        //     if ($_REQUEST['distance'] == "medium")
        //     if ($_REQUEST['distance'] == "far")
        // }
        $sql = $db->prepare($querystring);
        $sql->execute(array(
            'shopname' => $shopname
        ));
        $result = $sql->fetchAll();
        foreach ($result as &$row) {
            $shopname = $row['shopname'];
            $category = $row['category'];
            $SID = $row['SID'];
            $shop_latitude = $row['location_latitude'];
            $shop_longitude = $row['location_longitude'];
            $dis = cal_distance($user_latitude, $user_longitude, $shop_latitude, $shop_longitude);
            $Distance = "";
            if (isset($_REQUEST['distance'])) {
                if ($_REQUEST['distance'] == "near") {
                    $Distance = "Near";
                    if ($dis > 2) continue;
                }
                else if ($_REQUEST['distance'] == "medium") {
                    $Distance = "Medium";
                    if ($dis <= 2 || $dis >= 5) continue;
                }
                else if ($_REQUEST['distance'] == "far") {
                    $Distance = "Far";
                    if ($dis < 5) continue;
                }
            }
            else {
                if ($dis <= 2) $Distance = "Near";
                else if($dis > 2 && $dis < 5) $Distance = "Medium";
                else $Distance = "Far";
            }
            echo <<< EOT
                <tr>
                    <td>$shopname</td>
                    <td>$category</td>
                    <td>$Distance</td>
                    <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#$SID">Open menu</button></td>
                </tr>
            EOT;
        }
        // if ($sql->rowCount() == 0) {
        //     echo 'YES';
        // }
        // else {
        //     echo 'NO';
        // }
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
