<?php
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
?>
<div class="row">
    <div class="  col-xs-8">
        <table class="table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th scope="col">Picture</th>
                    <th scope="col">Meal Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = $db->query("SELECT * FROM product WHERE SID=(SELECT SID FROM shop WHERE UID=$UID)");
                    $result = $sql->fetchAll();
                    
                    foreach ($result as &$row) {
                        $PID = $row['PID'];
                        $product_name = $row['product_name'];
                        $price = $row['price'];
                        $quantity = $row['quantity'];
                        echo <<< EOT
                            <tr>
                                <td><img src="Picture/1.jpg" with="50" heigh="10" alt="$product_name"></td>
                                <td>$product_name</td>
                                <td>$price</td>
                                <td>$quantity</td>
                                <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#$PID">Edit</button></td>
                                <!-- Modal -->
                                <div class="modal fade" id="$PID" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">$product_name Edit</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="row" >
                                                    <div class="col-xs-6">
                                                        <label for="ex71">Price</label>
                                                        <input class="form-control" id="ex71" type="text">
                                                    </div>
                                                    <div class="col-xs-6">
                                                        <label for="ex41">Quantity</label>
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
                        EOT;
                    }
                ?>
                
            </tbody>
        </table>
    </div>
</div>