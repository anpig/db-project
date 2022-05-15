<?php
  session_start();
  // $_SESSION['logged'] = true; // debug use only
  $dbservername='localhost';
  $dbname='db-project';
  $dbusername='db';
  $dbpassword='db';

  $UID=$_SESSION['UID'];
  $db = new PDO("mysql:host=$dbservername;dbname=$dbname", $dbusername, $dbpassword); // connect to db
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);   // set the PDO error mode to exception
  $sql = $db->prepare("select * from user where UID=:UID");
  $sql->execute(array('UID' => $UID));
  $row = $sql->fetch();

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
  <title>ABerEats</title>
</head>

<body>
  <nav class="navbar navbar-inverse">
    <div class="container-fluid">
      <div class="navbar-header">
        <span class="nav-item"><a class="navbar-brand" href="#home" data-toggle="tab">ABerEats</a></span>
        <ul class="nav navbar-nav">
          <li class="nav-item active"><a href="#home" data-toggle="tab">Home</a></li>
          <li class="nav-item"><a href="#shop" data-toggle="tab">Shop</a></li>
        </ul>
        <a href="logout.php"><button style="position: absolute; right: 1%;" class="btn btn-danger navbar-btn navbar-right">Log Out</button></a>
      </div>
    </div>
  </nav>

  <div class="container">
    <div class="tab-content">
      <div id="home" class="tab-pane fade in active">
        <h3>Profile</h3>
        <div class="row">
          <div class="col-xs-12">
            <?php
              $account = $row['account']; $name = $row['name']; $phone_number = $row['phone_number']; $location_longitude = $row['location_longitude']; $location_latitude = $row['location_latitude'];
              echo "帳號: $account 名字: $name 手機: $phone_number 經度: $location_longitude 緯度: $location_latitude";
            ?>

            <button type="button " style="margin-left: 5px; margin-right: 10px;" class=" btn btn-info " data-toggle="modal"
            data-target="#location">Edit Location</button>
            <!--  -->
            <div class="modal fade" id="location"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <form action="edit_location.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Edit Location</h4>
                    </div>
                    <div class="modal-body form-group">
                      <label class="control-label" for="latitude">Latitude</label>
                      <input type="text" name="latitude" class="form-control" id="latitude" placeholder="enter latitude">
                      <br>
                      <label class="control-label" for="longitude">Longitude</label>
                      <input type="text" name="longitude" class="form-control" id="longitude" placeholder="enter longitude">
                    </div>
                    <div class="modal-footer form-group">
                      <button type="submit" value="Edit" class="btn btn-primary">Edit</button>
                      <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Edit</button> -->
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <!--  -->
            <span>Wallet Balance: <?php echo $row['balance']; ?></span>
            <!-- Modal -->
            <button type="button " style="margin-left: 5px;" class=" btn btn-info " data-toggle="modal"
              data-target="#myModal">Add Value</button>
            <div class="modal fade" id="myModal"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog  modal-sm">
                <form action="edit_balance.php" method="post">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal">&times;</button>
                      <h4 class="modal-title">Add Value</h4>
                    </div>
                    <div class="modal-body">
                      <input type="text" class="form-control" id="value" name="value" placeholder="enter add value">
                    </div>
                    <div class="modal-footer">
                    <button type="submit" value="Edit" class="btn btn-primary">Add</button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
        <h3>Search</h3>
        <div class=" row  col-xs-8">
          <form class="form-horizontal" action="/action_page.php">
            <div class="form-group">
              <label class="control-label col-sm-1" for="Shop">Shop</label>
              <div class="col-sm-5">
                <input type="text" class="form-control" placeholder="Enter Shop name">
              </div>
              <label class="control-label col-sm-1" for="distance">Distance</label>
              <div class="col-sm-5">
                <select class="form-control" id="sel1">
                  <option>Near</option>
                  <option>Medium </option>
                  <option>Far</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-1" for="Price">Price</label>
              <div class="col-sm-2">
                <input type="text" class="form-control">
              </div>
              <label class="control-label col-sm-1" for="~">~</label>
              <div class="col-sm-2">
                <input type="text" class="form-control">
              </div>
              <label class="control-label col-sm-1" for="Meal">Meal</label>
              <div class="col-sm-5">
                <input type="text" list="Meals" class="form-control" id="Meal" placeholder="Enter Meal">
                <datalist id="Meals">
                  <option value="Hamburger">
                  <option value="coffee">
                </datalist>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-sm-1" for="category"> Category</label>
                <div class="col-sm-5">
                  <input type="text" list="categorys" class="form-control" id="category" placeholder="Enter shop category">
                  <datalist id="categorys">
                    <option value="fast food">
                  </datalist>
                </div>
                <button type="submit" style="margin-left: 18px;"class="btn btn-primary">Search</button>
            </div>
          </form>
        </div>
        <div class="row">
          <div class="  col-xs-8">
            <table class="table" style=" margin-top: 15px;">
              <thead>
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">Shop Name</th>
                  <th scope="col">Shop Category</th>
                  <th scope="col">Distance</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <th scope="row">1</th>
                  <td>macdonald</td>
                  <td>fast food</td>
                  <td>near </td>
                  <td><button type="button" class="btn btn-info " data-toggle="modal" data-target="#macdonald">Open menu</button></td>
                </tr>
              </tbody>
            </table>
            <!-- Modal -->
            <div class="modal fade" id="macdonald"  data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">menu</h4>
                  </div>
                  <div class="modal-body">
                    <div class="row">
                      <div class="  col-xs-12">
                        <table class="table" style=" margin-top: 15px;">
                          <thead>
                            <tr>
                              <th scope="col">#</th>
                              <th scope="col">Picture</th>
                            
                              <th scope="col">meal name</th>
                          
                              <th scope="col">price</th>
                              <th scope="col">Quantity</th>
                            
                              <th scope="col">Order check</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <th scope="row">1</th>
                              <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
                            
                              <td>Hamburger</td>
                            
                              <td>80 </td>
                              <td>20 </td>
                          
                              <td> <input type="checkbox" id="cbox1" value="Hamburger"></td>
                            </tr>
                            <tr>
                              <th scope="row">2</th>
                              <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
                            
                              <td>coffee</td>
                        
                              <td>50 </td>
                              <td>20</td>
                          
                              <td><input type="checkbox" id="cbox2" value="coffee"></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Order</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="shop" class="tab-pane fade">
        <?php
          if (!$row['role']) include('shop_notowner.html');
          else include('shop_isowner.php');
        ?>
      </div>
    </div>
  </div>
  <!-- Option 1: Bootstrap Bundle with Popper -->
  <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script> -->
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

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>