<?php
  if (!isset($_SESSION['logged']) || $_SESSION['logged'] == false) {
    header('Location: .');
    die();
  }
?>
<script>status_filter2 = {};</script>
<div id="shop_order" class="tab-pane fade">
  <h3>Shop order</h3>
  <div class=" row  col-xs-8">
    <form class="form-horizontal">
      <div class="form-group">
        <label class="control-label col-sm-1" for="status2">Status</label>
        <div class="col-sm-5">
          <select style="margin-left: 12px;" class="form-control" onchange="status_filter2['status2'] = this.value;">
            <option>All</option>
            <option>Finished</option>
            <option>Unfinished</option>
            <option>Cancel</option>
          </select>
        </div>
      </div>
        <button type="button" style="margin-left: 18px;"class="btn btn-primary" onclick="search_status2(status_filter2); console.log(status_filter2);">Search</button>
      </div>
    </form>
  <div class="row" class="tab-pane fade">
    <div id="result-stutus2" class="col-xs-8"></div>
  </div>
</div>
<script>
  function search_status2(status_filter2) {
    var querystring = "";
    if (status_filter2['status2'] == "Finished") querystring += "&status2=finished";
    else if (status_filter2['status2'] == "Unfinished") querystring += "&status2=unfinished";
    else if (status_filter2['status2'] == "Cancel") querystring += "&status2=cancel";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("result-stutus2").innerHTML = this.responseText;
      }
    };
    console.log(querystring);
    xhttp.open("POST", "list_shop_order.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send(querystring);
  }
  search_status2(status_filter2);
</script>