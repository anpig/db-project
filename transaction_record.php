<?php
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
?>
<script>action_filter = {};</script>
<div id="transaction_record" class="tab-pane fade">
  <h3>Transaction Records</h3>
  <div class="row col-xs-8">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-sm-1" for="action">Action</label>
        <div class="col-sm-3">
          <select class="form-control" onchange="action_filter['action'] = this.value;">
            <option>All</option>
            <option>Payment</option>
            <option>Receive</option>
            <option>Recharge</option>
          </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="search_action(action_filter); console.log(action_filter);">Search</button>  
      </div>
    </form>
  </div>
  <div class="row" class="tab-pane fade">
    <div id="result-action" class="col-xs-12"></div>
  </div>
</div>
<script>
  function search_action(action_filter) {
    var querystring = "";
    if (action_filter['action'] == "Payment") querystring += "&action=Payment";
    else if (action_filter['action'] == "Receive") querystring += "&action=Receive";
    else if (action_filter['action'] == "Recharge") querystring += "&action=Recharge";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("result-action").innerHTML = this.responseText;
      }
    };
    console.log(querystring);
    xhttp.open("POST", "list_my_transaction.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(querystring);
  }
  search_action(action_filter);
</script>