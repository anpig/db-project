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
                        <th width="20%" scope="col">Record ID</th>
                        <th width="20%" scope="col">Action</th>
                        <th width="20%" scope="col">Time</th>
                        <th width="20%" scope="col">Trader</th>
                        <th width="20%" scope="col">Amount Change</th>
                    </tr>
                </thead>
            <tbody>
        EOT;
        if (isset($_REQUEST['action'])) {
            $action = $_REQUEST['action'];
            $sql = $db->query("SELECT * FROM transaction WHERE UID = '$UID' AND type = '$action' ORDER BY TID DESC");
        }
        else {
            $sql = $db->query("SELECT * FROM transaction WHERE UID = '$UID' ORDER BY TID DESC");
        }
        $result = $sql->fetchAll();
        foreach ($result as &$row) {
            $record_id = $row['TID'];
            $action = $row['type'];
            $time = $row['time'];
            $trader = $row['trader'];
            $amount_change = $row['value'];
            echo <<< EOT
                <tr>
                    <td>$record_id</td>
                    <td>$action</td>
                    <td>$time</td>
                    <td>$trader</td>
                    <td>$amount_change</td>
                    </tr>
                </tbody>
            EOT;
        }
    }
    catch (Exception $e) { 
        $msg = $e->getMessage();
        echo $msg;
    }
?>
