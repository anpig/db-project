<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    try {
        if (!isset($_REQUEST['uname']) || empty($_REQUEST['uname'])) {
            echo 'FAILED';
            exit();
        }
        $uname = $_REQUEST['uname'];
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        # set the PDO error mode to exception
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $db->prepare("SELECT shopname FROM shop WHERE shopname=:shopname");
        $sql->execute(array('shopname' => $uname));
        if ($sql->rowCount() == 0) {
            echo 'YES';
        }
        else {
            echo 'NO';
        }
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
