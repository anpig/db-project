<div id="shop" class="tab-pane fade">
  <?php
    if (!$row['role']) include('shop_notowner.html');
    else include('shop_isowner.php');
  ?>
</div>