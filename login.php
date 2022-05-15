<?php
    if (isset($_SESSION['logged']) && $_SESSION['logged'] == true) {
        header('Location: nav.php');
        die();
    }
?>
<!DOCTYPE html>
<html>
    <body>
    <?php
        session_start();
        $_SESSION['logged'] = false;
        $_SESSION['account'] = "";

        $dbservername='localhost';
        $dbname='db-project';
        $dbusername='db';
        $dbpassword='db';

        try {
            if (empty($_POST['account']) || empty($_POST['password'])) {
                $err_message="";
                if (empty($_POST['account'])) $err_message=$err_message."ACCOUNT ";
                if (empty($_POST['password'])) $err_message=$err_message."PASSWORD ";
                throw new Exception("空白欄位：$err_message");
            }
            else {
                $account = $_POST['account'];
                $password = $_POST['password'];

                $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
                $sql = $db->prepare("select UID, account, password, salt from user where account=:account");
                $sql->execute(array('account' => $account));
                if ($sql->rowCount()==1) {
                    $row = $sql->fetch();
                    if ($row['password'] == hash('sha256',$row['salt'].$_POST['password'])) {
                        $_SESSION['logged'] = true;
                        $_SESSION['UID'] = $row['UID'];
                        $_SESSION['account'] = $row['account'];
                        header("Location: nav.php");
                        exit();
                    }
                    else throw new Exception('登入失敗');
                }
                else throw new Exception('登入失敗');
            }
        } catch (Exception $e) {
            $msg = $e->getMessage();
            session_unset();
            session_destroy();
            echo "<script>alert(\"$msg\"); window.location.replace(\"index.php\");</script>";
        }
    ?>
    </body>
</html>