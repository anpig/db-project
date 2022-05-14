<!DOCTYPE html>
<html>
    <body>
<?php
    session_start();
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    try {
        if (empty($_POST['latitude']) || empty($_POST['longitude'])) {
            $err_message="";
            if (empty($_POST['latitude'])) $err_message=$err_message."LATITUDE".'\n';
            if (empty($_POST['longitude'])) $err_message=$err_message."LONGTITUDE".'\n'; 
            throw new Exception('空白欄位：'.'\n'."$err_message");
        }
        else if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180 || !is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) {
            if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180) $err_message=$err_message."經緯度範圍不正確".'\n';
            if (!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) $err_message=$err_message."經緯度應是數字 ";
            throw new Exception("輸入格式不對：".'\n'."$err_message");
        }
        else {
            $latitude = $_POST['latitude'];
            $longitude = $_POST['longitude'];
            $UID = $_SESSION['UID'];

            $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
            $sql = $db->prepare("UPDATE user SET location_latitude = :latitude, location_longitude = :longitude WHERE `user`.`UID` = :UID");
            $sql->execute(array('latitude' => $latitude, 'longitude' => $longitude, 'UID' => $UID));
            throw new Exception("EDIT Success!");
        }
    } catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }
?>
    </body>
</html>