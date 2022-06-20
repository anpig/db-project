<?php
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
?>
<script>status_filter = {};</script>
<div id="my_order" class="tab-pane fade">
  <h3>My Orders</h3>
  <div class="row col-xs-8">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-sm-1" for="status">Status</label>
        <div class="col-sm-3">
          <select class="form-control" onchange="status_filter['status'] = this.value;">
            <option>All</option>
            <option>Finished</option>
            <option>Unfinished</option>
            <option>Canceled</option>
          </select>
        </div>
        <button type="button" class="btn btn-primary" onclick="search_status(status_filter); console.log(status_filter);">Search</button>
      </div>
    </form>
  </div>
  <div class="row" class="tab-pane fade">
    <div id="result-status" class="col-xs-12"></div>
  </div>
</div>
<script>
  function search_status(status_filter) {
    var querystring = "";
    if (status_filter['status'] == "Finished") querystring += "&status=finished";
    else if (status_filter['status'] == "Unfinished") querystring += "&status=unfinished";
    else if (status_filter['status'] == "Canceled") querystring += "&status=canceled";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("result-status").innerHTML = this.responseText;
      }
    };
    console.log(querystring);
    xhttp.open("POST", "list_my_order.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(querystring);
  }
  search_status(status_filter);
</script>