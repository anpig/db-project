<?php
    session_start();
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->

  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"> -->
  

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
  <title>ABerEats | Checkout</title>
</head>

<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <span class="nav-item"><a class="navbar-brand" href="nav.php#home">ABerEats</a></span>
        <ul class="nav navbar-nav">
          <li class="nav-item active"><a href="nav.php#home">Home</a></li>
          <li class="nav-item"><a href="nav.php#shop">Shop</a></li>
          <li class="nav-item"><a href="nav.php#my_order">My Orders</a></li>
          <li class="nav-item"><a href="nav.php#shop_order">Shop Orders</a></li>
          <li class="nav-item"><a href="nav.php#transaction_record">Transaction Records</a></li>
        </ul>
        <a style="position: absolute; right: 25px;" href="logout.php"><button class="btn btn-danger navbar-btn navbar-right">Log Out</button></a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="tab-content">
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->


  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->

<?php
    $dbservername='localhost';
    $dbname='db-project';
    $dbusername='db';
    $dbpassword='db';
    $SID=$_POST['SID'];
    $dist = $_POST['dist'];
    $type = $_POST['select'];
    $delivery_fee = 0;
    if ($type == "Delivery") {
        $delivery_fee = intval($dist*10);
        if($delivery_fee < 10) $delivery_fee = 10;
    }
    try {
        $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
        $sql = $db->query("select * from product where SID = $SID");
        $result = $sql->fetchAll();
        if ($sql->rowCount() == 0) {
            echo "<script>alert(\"該店家沒有商品\"); window.location.replace(\"nav.php\");</script>";
            exit();
        }
        echo "<h3>Order</h3>";
        echo <<< EOT
          <form action="check_out.php" class="fh5co-form animate-box form-horizontal" data-animate-effect="fadeIn" method="post">
            <div class="row form-group">
              <div class="col-xs-12">
                <table class="table" table-layout:fixed;>
                    <thead>
                        <tr>
                          <th scope="col">Picture</th>
                          <th scope="col">Meal Name</th>
                          <th scope="col">Price</th>
                          <th scope="col">Order Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
        EOT;
        $sub_total = 0;
        foreach ($result as &$row) {
            $PID = $row['PID'];
            $order_quantity = $_POST["$PID"];
            if ($order_quantity == 0) {
                echo <<< EOT
                    <input type="hidden" name="$PID" value="$order_quantity">
                EOT;
                continue;
            };
            $product_name = $row['product_name'];
            $price = $row['price'];
            $picture = $row['picture'];
            $picture_type = $row['picture_type'];
            $sub_total += $price*$order_quantity;
            echo '<tr><td><img style="max-width:100%; max-height:200px" src="data:'.$picture_type.';base64,' . $picture . '"  alt="$product_name"/></td>';
            echo <<< EOT
                    <td>$product_name</td>
                    <td>$price</td>
                    <td>$order_quantity</td>
                    <input type="hidden" name="$PID" value="$order_quantity">
                </tr>
            EOT;
        }
        if($sub_total == 0) {
            echo "<script>alert(\"請選擇商品\"); window.location.replace(\"nav.php\");</script>";
        }
        echo <<< EOT
              </tbody>
            </table>
          </div>
        EOT;
        $total = $sub_total + $delivery_fee;
        echo <<< EOT
            <div class="col-xs-12">
              <span style="float: left">Subtotal:</span>
              <span style="float: right">$sub_total</span>
            </div>
            <div class="col-xs-12">
              <span style="float: left">Delivery Fee:</span>
              <span style="float: right">$delivery_fee</span>
            </div>
            <div class="col-xs-12">
              <span style="float: left; font-weight: bold">Total Price:</span>
              <span style="float: right; font-weight: bold">$total</span>
            </div>
            <input type="hidden" name="SID" value="$SID">
            <input type="hidden" name="total" value="$total">
            <input type="hidden" name="dist" value="$dist">
            <input type="hidden" name="type" value="$type">
            <input type="hidden" name="sub_total" value="$sub_total">
            <div>
              <input style="width: 100%; margin-top: 15px" type="submit" value="Order" class="btn btn-primary">
            </div>
          </div>
        </form>
        EOT;
    }
    catch (Exception $e) {
        $msg=$e->getMessage();
        session_unset();
        session_destroy();
        echo "<script>alert(\"$msg\"); window.location.replace(\"nav.php\");</script>";
    }
?>

        </div>
      </div>
      <script>
      $(function(){
        var hash = window.location.hash;
        hash && $('ul.nav a[href="' + hash + '"]').tab('show');

        $('.nav-item a').click(function (e) {
          $(this).tab('show');
          var scrollmem = $('body').scrollTop() || $('html').scrollTop();
          window.location.hash = this.hash;
          $('html,body').scrollTop(scrollmem);
        });
        $('.nav-brand a').click(function (e) {
          $(this).tab('show');
          var scrollmem = $('body').scrollTop() || $('html').scrollTop();
          window.location.hash = this.hash;
          $('html,body').scrollTop(scrollmem);
        });
      });
    </script>
  </body>
</html>