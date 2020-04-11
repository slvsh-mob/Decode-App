<?php
$page_title = "Home Page";
include_once "../static/layout_header.php";
?>
      <script src="https://cdn.anychart.com/js/8.0.1/anychart-core.min.js"></script>
      <script src="https://cdn.anychart.com/js/8.0.1/anychart-pie.min.js"></script>

  <div class="col s3 left">

  <img src="../logos/Great-American-Foods.png" alt="Great American Logo" style="height: 300px; width: 300px;" class="center">

  </div>
  <div class="col s9">
    <div class="valign-wrapper">
      <p style="font-family: sans-serif; font-size: 40px;">Welcome to Great American Foods Inventory Management System</p>
    </div>
  </div>

</div>

<div class="row">
  <div class="col s8 left" id="container" style="height: 500px;">

      <script type="text/javascript">
        anychart.onDocumentReady(function() {

        // set the data
        var data = [
            {x: "White", value: 223553265},
            {x: "Black or African American", value: 38929319},
            {x: "American Indian and Alaska Native", value: 2932248},
            {x: "Asian", value: 14674252},
            {x: "Native Hawaiian and Other Pacific Islander", value: 540013},
            {x: "Some Other Race", value: 19107368},
            {x: "Two or More Races", value: 9009073}
        ];

        // create the chart
        var chart = anychart.pie();

        // set the chart title
        chart.title("Current Inventory By Protein Type");

        // add the data
        chart.data(data);

        // display the chart in the container
        chart.container('container');
        chart.draw();

        });
      </script>

  </div>
  <div class="col s3">
    <h1 class="center">Quick Nav</h1>
        <div class="center">
          <button class="btn-large green accent-3" id="RecButton" onclick="window.location.href = 'GAFReceiving6.php'" style="margin-bottom: 2em;">Receiving</button>
          <button class="btn-large indigo accent-3" id="OutButton" onclick="window.location.href = 'GAFScanOut4.php'" style="margin-bottom: 2em;">Inventory Out</button>
        </div>
  </div>

</div>
  


<?php
include_once "../static/layout_footer.php";
?>