<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    try {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
            echo <<< EOT
                <tr>
                    <td>$shopname</td>
                    <td>$category</td>
                    <td>distance</td>
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
