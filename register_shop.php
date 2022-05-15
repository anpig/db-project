<?php
    session_start();
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    try {
        if (empty($_POST['shopname']) || empty($_POST['category']) || empty($_POST['latitude']) || empty($_POST['longitude'])) {
            $err_message="";
            if (empty($_POST['shopname'])) $err_message=$err_message.'SHOP NAME\n';
            if (empty($_POST['category'])) $err_message=$err_message.'CATEGORY\n';
            if (empty($_POST['latitude'])) $err_message=$err_message.'LATITUDE\n';
            if (empty($_POST['longitude'])) $err_message=$err_message.'LONGTITUDE\n'; 
            throw new Exception('欄位空白：\n'."$err_message");
        }
        else if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180 || !is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) {
            if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180) $err_message=$err_message."經緯度範圍不正確".'\n';
            if (!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) $err_message=$err_message."經緯度應是數字 ";
            throw new Exception("輸入格式不對：".'\n'."$err_message");
        }
        else {
            $shopname = $_POST['shopname'];
            $category = $_POST['category'];
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $UID = $_SESSION['UID'];

            $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
            $sql = $db->prepare("SELECT phone_number FROM user WHERE UID=:UID");
            $sql->execute(array('UID' => $UID));
            $row = $sql->fetch();
            $phonenumber = $row['phone_number'];
            $sql = $db->prepare("
                INSERT into shop (UID, shopname, location_longitude, location_latitude, phone_number, category)
                values (:UID, :shopname, :location_longitude, :location_latitude, :phone_number, :category)
            ");
            $sql->execute(
                array(
                    'UID' => $UID,
                    'shopname' => $shopname,
                    'location_longitude' => $longitude,
                    'location_latitude' => $latitude,
                    'phone_number' => $phonenumber,
                    'category' => $category
                )
            );
            $sql = $db->prepare("UPDATE user SET role=1 WHERE `user`.`UID` = :UID");
            $sql->execute(array('UID' => $UID));
            throw new Exception("註冊成功");
        }
    } catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }
?>