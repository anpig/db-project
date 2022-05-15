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
            <input class="form-control" name="shopname" id="ex5" placeholder="macdonald" type="text" >
        </div>
        <div class="col-xs-2">
            <label for="ex5">Shop Category</label>
            <input class="form-control" name="category" id="ex5" placeholder="fast food" type="text" >
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