<?php
    if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
        header('Location: .');
        die();
    }
?>
<div class="row">
    <div class="  col-xs-12">
        <table class="table" style="margin-top: 15px;">
            <thead>
                <tr>
                    <th width="50%" scope="col">Picture</th>
                    <th width="20%" scope="col">Meal Name</th>
                    <th width="10%" scope="col">Price</th>
                    <th width="10%" scope="col">Quantity</th>
                    <th width="5%" scope="col">Actions</th>
                    <th width="5%" scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $sql = $db->query("SELECT * FROM product WHERE SID=(SELECT SID FROM shop WHERE UID=$UID) AND listed=1");
                    $result = $sql->fetchAll();
                    
                    foreach ($result as &$row) {
                        $PID = $row['PID'];
                        $product_name = $row['product_name'];
                        $price = $row['price'];
                        $quantity = $row['quantity'];
                        $picture = $row['picture'];
                        $picture_type = $row['picture_type'];
                        echo '<tr><td><img style="max-width:100%; max-height:200px" src="data:'.$picture_type.';base64,' . $picture . '"  alt="$product_name"/></td>';
                        echo <<< EOT
                                <td>$product_name</td>
                                <td>$price</td>
                                <td>$quantity</td>
                                <td><button type="button" class="btn btn-info" data-toggle="modal" data-target="#edit_$PID">Edit</button></td>
                                <!-- Modal -->
                                <div class="modal fade" id="edit_$PID" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="staticBackdropLabel">$product_name Edit</h5>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <form action="edit_product.php" method="post">
                                                <div class="modal-body">
                                                    <div class="row" >
                                                        <div class="col-xs-6">
                                                            <label for="ex71">Price</label>
                                                            <input class="form-control" id="ex71" name="price" type="text">
                                                        </div>
                                                        <div class="col-xs-6">
                                                            <label for="ex41">Quantity</label>
                                                            <input class="form-control" id="ex41" name="quantity" type="text">
                                                        </div>
                                                        <input type="hidden" name="PID" value="$PID">
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-secondary">Edit</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <form action="delete_product.php" method="post">
                                    <input type="hidden" name="PID" value="$PID">
                                    <td><button type="submit" class="btn btn-danger">Delete</button></td>
                                </form>
                            </tr>
                        EOT;
                    }
                ?>
                
            </tbody>
        </table>
    </div>
</div>