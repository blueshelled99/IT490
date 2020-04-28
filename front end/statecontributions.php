<?php
session_start();

$_SESSION['email'];

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$userSubmittal = array(
	"user" => $_SESSION['email'],
	"query" => "https://api.open.fec.gov/v1/presidential/contributions/by_state/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&sort=-contribution_receipt_amount&page=1&election_year=2020&sort_nulls_last=false"
);

$msgJson = json_encode($userSubmittal);

$connection = new AMQPStreamConnection('10.0.0.7', 5672, 'rabbitmq-service', 'Team666!'); //change ip address

$channel = $connection->channel();

//$channel -> queue_declare('contribution_query_queue', false, true, false, false);
//redudant when it comes to using exchanges

$msg = new AMQPMessage($msgJson);

$channel->basic_publish($msg, 'API-Exchange', 'send-api');

$channel->close();
$connection->close();
?>

<html>
  <head>
    <link rel="stylesheet" href="login.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
	function drawStatesMap() {
	var options = {region: 'US', resolution: 'provinces'};
        var dimension = "contribution_receipt_amount";
            $.ajax({
              url: "https://api.open.fec.gov/v1/presidential/contributions/by_state/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&sort=-contribution_receipt_amount&page=1&election_year=2020&sort_nulls_last=false",
              dataType: "JSON"
            }).done(function(data) {
                    var statesArray = [["State",dimension]];
                    $.each(data.results, function() {
                        var stateitem = [this.contribution_state, this[dimension]];
                        statesArray.push(stateitem);
                    });
              var statesData = google.visualization.arrayToDataTable(statesArray);
              var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
              chart.draw(statesData, options);
              $("h3").append(" Sorted by  "+dimension);
            });
    }
google.charts.load('current', {'packages':['corechart']});
google.setOnLoadCallback(drawStatesMap);
    </script>
  </head>
  <body>
    <h1>Here you go, <?php echo $_SESSION['email'] ?></h1>
    <br>
    <h1>OpenFEC's Top 100 Presidential Contributions by State for 2020</h1>
    <div id="chart_div" style="width: 900px; height: 500px;" ></div>
  </body>
</html>
