<?php
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
    $sql = $db->query("SELECT * FROM shop WHERE UID=$UID");
    $row = $sql->fetch();
    $shopname = $row['shopname'];
    $category = $row['category'];
    $location_longitude = $row['location_longitude'];
    $location_latitude = $row['location_latitude'];
?>
<h3>Shop Info</h3>
<div class="row">
    <div class="col-xs-12">
        <?php echo "店名：$shopname<br>類別：$category<br>經度：$location_longitude<br>緯度：$location_latitude";?>
    </div>
</div>
<h3>Add Meal</h3>
<form action="add_meal.php" enctype="multipart/form-data" method="post">
    <div class="form-group">
        <div class="row">
        <div class="col-xs-6">
            <label for="ex3">Meal Name</label>
            <input name="product_name"class="form-control" id="ex3" type="text">
        </div>
        </div>
        <div class="row" style=" margin-top: 15px;">
        <div class="col-xs-3">
            <label for="ex7">Price</label>
            <input name="price" class="form-control" id="ex7" type="text">
        </div>
        <div class="col-xs-3">
            <label for="ex4">Quantity</label>
            <input name="quantity" class="form-control" id="ex4" type="text">
        </div>
        </div>
        <div class="row" style=" margin-top: 25px;">
        <div class=" col-xs-3">
            <label for="ex12">上傳圖片</label>
            <input name="picture" type="file">
        </div>
        <div class=" col-xs-3">
            <button style=" margin-top: 15px;" type="submit" class="btn btn-primary">Add</button>
        </div>
        </div>
    </div>
</form>
<?php include('list_product.php'); ?>