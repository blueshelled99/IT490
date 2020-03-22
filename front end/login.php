<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$email = $_POST['email'];
$pass = $_POST['password'];

class RpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            '192.168.1.50', //change ip address here
            5672,
            'rabbitmq-test',
            'test'
        );

        $this->channel = $this->connection->channel();
        list($this->callback_queue, ,) = $this->channel->queue_declare(
            "user-test2",
            false,
            true,
            false,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            "",
            false,
            true,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call($n)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            //(string) $n,
	    $n,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
	$this->channel->basic_publish($msg, '', 'user-test2');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response); //maybe initialize the type of variable here
    }
}

$options = [
	'salt' => 'VerySecureSalt', 
	'cost' => 12,
];

$hashed_password = password_hash($pass, PASSWORD_BCRYPT, $options);

$userSubmittal = array(
	"email" => $_POST['email'],
	"pass" => $_POST['password']
);

$msgJSON = json_encode($userSubmittal);

$rpc = new RpcClient();
$response = $rpc->call($msgJSON);

echo $response;

if ($response == "true"){
	$_SESSION['logged_in'] = true;
	header("Location: user_home.php");
}

else{
	$_SESSION['logged_in'] = false;
	header("Location: index.html");
	echo "Wrong username or password. Please try again.";
}

?>
