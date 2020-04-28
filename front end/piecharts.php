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

$connection = new AMQPStreamConnection('10.0.0.7', 5672, 'rabbitmq-service', 'Team666!'); //change ip address

$channel = $connection->channel();

//$channel -> queue_declare('totals_by_office_query_queue', false, true, false, false);
//redundant when using exchanges

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
	function piedraw1() { //make function here and name it
	var options = {title: "OpenFEC's Aggregated Candidates Disbursements grouped by office for 2020"};
        var dimension = "total_disbursements"; //what you want to use as an index?
            $.ajax({
              url: "https://api.open.fec.gov/v1/candidates/totals/by_office/?sort_hide_null=false&api_key=r6K96zZiE3CiSz10AhkCh0EGSpKNbxmDYD4osUAN&per_page=100&sort_null_only=false&page=1&is_active_candidate=true&election_year=2020&sort_nulls_last=true",
              dataType: "JSON"
            }).done(function(data) {
                    var disbursementArray = [["Office",dimension]]; //array 0 can be renamed
                    $.each(data.results, function() { //results is taken from the json; used to index
                        var item = [this.office, this[dimension]]; //array[0] = category, array[1] = data for that category
                        disbursementArray.push(item);
                    });
              var disbursementData = google.visualization.arrayToDataTable(disbursementArray);
              var chart = new google.visualization.PieChart(document.getElementById('disbursements')); //make sure to change chart type if you are doing another type
              chart.draw(disbursementData, options);
            });
    }
google.charts.load('current', {'packages':['corechart']});
google.setOnLoadCallback(piedraw1); //call the function here, in this case piedraw1
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
