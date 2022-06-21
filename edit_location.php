<!DOCTYPE html>
<html>
    <body>
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
    try {
        if (!isset($_POST['latitude']) || !isset($_POST['longitude'])) {
            $err_message="";
            if (!isset($_POST['latitude'])) $err_message=$err_message."LATITUDE".'\n';
            if (!isset($_POST['longitude'])) $err_message=$err_message."LONGTITUDE".'\n'; 
            throw new Exception('空白欄位：'.'\n'."$err_message");
        }
        else if (!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) {
            throw new Exception("經緯度應是數字");
        }
        else if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180) {
            throw new Exception("經緯度範圍不正確");
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