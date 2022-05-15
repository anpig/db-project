<?php
    $sql = $db->prepare("SELECT * FROM shop WHERE UID=:UID");
    $sql->execute(array('UID' => $UID));
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
<div class="form-group ">
    <div class="row">
    <div class="col-xs-6">
        <label for="ex3">meal name</label>
        <input class="form-control" id="ex3" type="text">
    </div>
    </div>
    <div class="row" style=" margin-top: 15px;">
    <div class="col-xs-3">
        <label for="ex7">price</label>
        <input class="form-control" id="ex7" type="text">
    </div>
    <div class="col-xs-3">
        <label for="ex4">quantity</label>
        <input class="form-control" id="ex4" type="text">
    </div>
    </div>
    <div class="row" style=" margin-top: 25px;">
    <div class=" col-xs-3">
        <label for="ex12">上傳圖片</label>
        <input id="myFile" type="file" name="myFile" multiple class="file-loading">
    </div>
    <div class=" col-xs-3">
        <button style=" margin-top: 15px;" type="button" class="btn btn-primary">Add</button>
    </div>
    </div>
</div>
<div class="row">
    <div class="  col-xs-8">
    <table class="table" style="margin-top: 15px;">
        <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Picture</th>
            <th scope="col">meal name</th>
            <th scope="col">price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Edit</th>
            <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <th scope="row">1</th>
            <td><img src="Picture/1.jpg" with="50" heigh="10" alt="Hamburger"></td>
            <td>Hamburger</td>
            <td>80 </td>
            <td>20 </td>
            <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#Hamburger-1">Edit</button></td>
            <!-- Modal -->
            <div class="modal fade" id="Hamburger-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Hamburger Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                    <div class="col-xs-6">
                        <label for="ex71">price</label>
                        <input class="form-control" id="ex71" type="text">
                    </div>
                    <div class="col-xs-6">
                        <label for="ex41">quantity</label>
                        <input class="form-control" id="ex41" type="text">
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Edit</button>
                </div>
                </div>
            </div>
            </div>
            <td><button type="button" class="btn btn-danger">Delete</button></td>
        </tr>
        <tr>
            <th scope="row">2</th>
            <td><img src="Picture/2.jpg" with="10" heigh="10" alt="coffee"></td>
            <td>coffee</td>
            <td>50 </td>
            <td>20</td>
            <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#coffee-1">Edit</button></td>
            <!-- Modal -->
            <div class="modal fade" id="coffee-1" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">coffee Edit</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row" >
                    <div class="col-xs-6">
                        <label for="ex72">price</label>
                        <input class="form-control" id="ex72" type="text">
                    </div>
                    <div class="col-xs-6">
                        <label for="ex42">quantity</label>
                        <input class="form-control" id="ex42" type="text">
                    </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Edit</button>
                </div>
                </div>
            </div>
            </div>
            <td><button type="button" class="btn btn-danger">Delete</button></td>
        </tr>
        </tbody>
    </table>
    </div>
</div>