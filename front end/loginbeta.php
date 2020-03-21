<?php
session_start();

header('Location: user_home.php');

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$options = [
	'cost' => 12,
];
$hashed_password = password_hash($_POST['password'], PASSWORD_BCRYPT, $options);
    
$userSubmittal = array(
    "email" => $_POST['email'],
    //"password" => $hashed_password
    "password" => $_POST['password']
);

$msgJson = json_encode($userSubmittal);

class RpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function execute()
    {
        $connection = new AMQPStreamConnection('192.168.1.48', 5672, 'rabbitmq-test','test');
        $channel = $connection->channel();
        
        list($callback_queue, ,) = $channel->queue_declare(
            'user-test2', 
            false,
            true,
            false,
            false
        );
        
        $channel->basic_consume(
            $callback_queue,
            '',
            false,
            false,
            false,
            false,
            array($this,'onResponse')
        );
        
        $this->response = null;
        
        $this->corr-id = uniqid();
        
        $msg = new AMQPMessage(
		    $msgJson,
		    array('correlation_id' => $this->corr_id, 'reply_to' => $callback_queue)    
			);
        
        $channel->basic_publish(
			$msg,		 
			'', 		
			'user-test2'	
			);
		
		while(!$this->response) {
			$channel->wait();
		}
		
		$channel->close();
		$connection->close();
		
		return $this->response;
    }

    public function onResponse(AMQPMessage $rep) {
    	if($rep->get('correlation_id') == $this->corr_id) {
			$this->response = $rep->body;
		}
	}
    
    //***anything after this is out of scope from the sitepoint tutorial
    
}

$rpc = new RpcClient();
$rpc->execute();
//***$rpc->onResponse(); //maybe call this function here but i dont think you have to


echo $response;
#echo ' [.] Got ', $response, "\n";

//if ($response == "true"){ //we could change this later so that when we consume its a a string 'true'
if ($response == 'true'){
	$_SESSION['logged_in'] = true;
    header("Location: user_home.php");
}
else{
	$_SESSION['logged_in'] = false;
	header("Location: index.html");
	echo "Wrong username or password. Please try again.";
}

?>
