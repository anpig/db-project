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
            if (empty($_POST['name']) || empty($_POST['phonenumber']) || empty($_POST['account']) || empty($_POST['password']) || empty($_POST['re-password']) || empty($_POST['latitude']) || empty($_POST['longitude'])) {
                $err_message="";
                if (empty($_POST['name'])) $err_message=$err_message."NAME".'\n';
                if (empty($_POST['phonenumber'])) $err_message=$err_message."PHONENUMBER".'\n';
                if (empty($_POST['account'])) $err_message=$err_message."ACCOUNT".'\n';
                if (empty($_POST['password'])) $err_message=$err_message."PASSWORD".'\n';
                if (empty($_POST['re-password'])) $err_message=$err_message."RETYPE-PASSWORD".'\n';
                if (empty($_POST['latitude'])) $err_message=$err_message."LATITUDE".'\n';
                if (empty($_POST['longitude'])) $err_message=$err_message."LONGTITUDE".'\n'; 
                throw new Exception('空白欄位：'.'\n'."$err_message");
            }
            else if ($_POST['password'] != $_POST['re-password']) {
                throw new Exception('密碼驗證 ≠ 密碼');
            }
            else if (!ctype_alnum($_POST['account']) || !ctype_alnum($_POST['password']) || !ctype_alpha(str_replace(' ', '', $_POST['name'])) || strlen($_POST['phonenumber']) != 10 || !ctype_digit($_POST['phonenumber']) || $_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180 || !is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) {
                $err_message="";
                if (!ctype_alnum($_POST['account'])) $err_message=$err_message."帳號組成僅包含大小寫英文與數字".'\n';
                if (!ctype_alnum($_POST['password'])) $err_message=$err_message."密碼組成僅包含大小寫英文與數字".'\n';
                if (!ctype_alpha(str_replace(' ', '', $_POST['name']))) $err_message=$err_message."名字組成僅由大小寫英文".'\n';
                if (strlen($_POST['phonenumber']) != 10 || !ctype_digit($_POST['phonenumber'])) $err_message=$err_message."手機號碼需為 10 位數字".'\n';
                if ($_POST['latitude'] > 90 || $_POST['latitude'] < -90 || $_POST['longitude'] > 180 || $_POST['longitude'] < -180) $err_message=$err_message."經緯度範圍不正確".'\n';
                if (!is_numeric($_POST['latitude']) || !is_numeric($_POST['longitude'])) $err_message=$err_message."經緯度應是數字 ";
                throw new Exception("輸入格式不對：".'\n'."$err_message");
            }
            else {
                $name = $_POST['name'];
                $phonenumber = $_POST['phonenumber'];
                $account = $_POST['account'];
                $password = $_POST['password'];
                $re_password = $_POST['re-password'];
                $latitude = $_POST['latitude'];
                $longitude = $_POST['longitude'];

                $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
                $sql = $db->prepare("select account from user where account=:account");
                $sql->execute(array('account' => $account));

                if ($sql->rowCount() == 0) {
                    $salt = strval(rand(1000,9999));
                    $hashvalue = hash('sha256', $salt.$password);
                    $sql = $db->prepare("
                        INSERT into user (account, password, salt, name, location_longitude, location_latitude, phone_number)
                        VALUES (:account, :password, :salt, :name, :location_longitude, :location_latitude, :phone_number)
                    ");
                    $sql->execute(
                        array(
                            'account' => $account,
                            'password' => $hashvalue,
                            'salt' => $salt,
                            'name' => $name,
                            'location_longitude' => $longitude,
                            'location_latitude' => $latitude,
                            'phone_number' => $phonenumber
                        )
                    );
                    echo "<script>alert(\"註冊成功\"); window.location.replace(\"index.php\");</script>";
                    exit();
                }
                else throw new Exception("帳號已被註冊");
            }

        } catch (Exception $e) {
            $msg=$e->getMessage();
            session_unset();
            session_destroy();
            echo "<script>alert(\"$msg\"); window.location.replace(\"sign-up.php\");</script>";
        }
    ?>
    </body>
</html>