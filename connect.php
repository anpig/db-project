<?php
try{
    $conn = new PDO("mysql:host=localhost;dbname=db-project","root","");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->query('select * from user');
    foreach($stmt as $row){
        echo $row['username']." ".$row['password']."<br>";
    }
    echo "connected";
    $conn = null;
} catch (Exception $e) {
    $msg=$e->getMessage();
    session_unset();
    session_destroy();

    echo <<<EOT
    <!DOCTYPE html>
        <html>
            <body>
                <script>
                    alert("$msg");
                    window.location.replace("connect.php");
                </script>
            </body>
        </html>
    EOT;
}

?>