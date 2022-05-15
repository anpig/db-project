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
        if (empty($_POST['value'])) {
            throw new Exception('空白欄位');
        }
        else if (!is_numeric($_POST['value']) || $_POST['value'] < 0 ) {
            throw new Exception("輸入格式不對");
        }
        else {
            $value = $_POST['value'];
            $UID=$_SESSION['UID'];
            $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
            $sql = $db->prepare("select * from user where UID=:UID");
            $sql->execute(array('UID' => $UID));
            $row = $sql->fetch();
            
            $odd=$row['balance'];
            $new=$value+$odd;

            $sql = $db->prepare("UPDATE user SET balance=:new WHERE `user`.`UID` = :UID");
            $sql->execute(array('new' => $new, 'UID' => $UID));
            throw new Exception("ADDED Success!");
        }
    } catch (Exception $e) {
        $msg=$e->getMessage();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }
?>
    </body>
</html>