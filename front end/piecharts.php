<?php
session_start();

$_SESSION['email'];

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$userSubmittal = array(
	"user" => $_SESSION['email'],
	"query" => "https://api.open.fec.gov/v1/candidates/totals/by_office/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&page=1&is_active_candidate=true&election_year=2020&sort_nulls_last=true"
);

$msgJson = json_encode($userSubmittal);

$connection = new AMQPStreamConnection('192.168.1.50', 5672, 'rabbitmq-test', 'test'); //change ip address

$channel = $connection->channel();

$channel -> queue_declare('totals_by_office_query_queue', false, true, false, false);

$msg = new AMQPMessage($msgJson);

$channel->basic_publish($msg, '', 'totals_by_office_query_queue');

$channel->close();
$connection->close();
?>

<html>
  <head>
    <link rel="stylesheet" href="login.css">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script type="text/javascript">
	function piedraw1() {
	var options = {title: "OpenFEC's Aggregated Candidates Disbursements grouped by office for 2020"};
        var dimension = "total_disbursements";
            $.ajax({
              url: "https://api.open.fec.gov/v1/candidates/totals/by_office/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&page=1&is_active_candidate=true&election_year=2020&sort_nulls_last=true",
              dataType: "JSON"
            }).done(function(data) {
                    var disbursementArray = [["Office",dimension]];
                    $.each(data.results, function() {
                        var item = [this.office, this[dimension]];
                        disbursementArray.push(item);
                    });
              var disbursementData = google.visualization.arrayToDataTable(disbursementArray);
              var chart = new google.visualization.PieChart(document.getElementById('disbursements'));
              chart.draw(disbursementData, options);
            });
    }
google.charts.load('current', {'packages':['corechart']});
google.setOnLoadCallback(piedraw1);
    </script>
    <script type="text/javascript">
	function piedraw2() {
	var options = {title: "OpenFEC's Aggregated Candidates Receipts grouped by office for 2020"};
        var dimension = "total_receipts";
            $.ajax({
              url: "https://api.open.fec.gov/v1/candidates/totals/by_office/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&page=1&is_active_candidate=true&election_year=2020&sort_nulls_last=true",
              dataType: "JSON"
            }).done(function(data) {
                    var receiptArray = [["Office",dimension]];
                    $.each(data.results, function() {
                        var item = [this.office, this[dimension]];
                        receiptArray.push(item);
                    });
              var receiptData = google.visualization.arrayToDataTable(receiptArray);
              var chart = new google.visualization.PieChart(document.getElementById('receipts'));
              chart.draw(receiptData, options);
            });
    }
google.charts.load('current', {'packages':['corechart']});
google.setOnLoadCallback(piedraw2);
    </script>
  </head>
  <body>
    <h1>Here you go, <?php echo $_SESSION['email'] ?></h1>
    <br>
    <h1>OpenFEC's Aggregated Candidate Receipts and Disbursements grouped by office for 2020</h1>
    <div id="disbursements" style="width: 900px; height: 500px;" ></div>
    <br>
    <div id="receipts" style="width: 900px; height: 500px;" ></div>
  </body>
</html>
