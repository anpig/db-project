<?php
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
?>
<h3>Start a business</h3>
<form action="register_shop.php" method="post">
    <div class="form-group">
        <div class="row">
        <div class="col-xs-2">
            <label for="ex5">Shop Name</label>
            <span id="check_shop"></span>
            <input class="form-control" name="shopname" id="ex5" placeholder="McDonald's" type="text" oninput="check_shop(this.value);">
        </div>
        <div class="col-xs-2">
            <label for="ex5">Shop Category</label>
            <input class="form-control" name="category" id="ex5" placeholder="Fast Food" type="text" >
        </div>
        <div class="col-xs-2">
            <label for="ex6">Latitude</label>
            <input class="form-control" name="latitude" id="ex6" placeholder="24.78472733371133121.00028167648875" type="text" >
        </div>
        <div class="col-xs-2">
            <label for="ex8">Longitude</label>
            <input class="form-control" name="longitude" id="ex8" placeholder="121.00028167648875" type="text" >
        </div>
        </div>
    </div>
    <div class=" row" style=" margin-top: 25px;">
        <div class=" col-xs-3">
        <button type="submit" class="btn btn-primary">Register</button>
        </div>
    </div>
</form>
<script>
        function check_shop(uname) {
            if (uname!="") {
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    var message;
                    if (this.readyState == 4 && this.status == 200) {
                        switch(this.responseText) {
                            case 'YES':
                                message='<label style="color: green">Available</label>';
                                break;
                            case 'NO':
                                message='<label style="color: red">Unavailable</label>';
                                break;
                            default:
                                message='Oops. There is something wrong.';
                                break;
                        }
                        document.getElementById("check_shop").innerHTML = message;
                    }
                };
                xhttp.open("POST", "check_shop.php", true);
                xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhttp.send("uname=" + uname);
            }
            else document.getElementById("check_shop").innerHTML = "";
        }
</script>