<?php
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
?>
<div id="shop" class="tab-pane fade">
  <?php
    if ($row['role']) include('shop_isowner.php');
    else include('shop_notowner.php');
  ?>
</div>