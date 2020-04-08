<?php
session_start();

if (! $_SESSION['logged_in']) {
	header("Location: index.html");
}

$email = $_POST['email'];
$pass = $_POST['password'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Menu</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
<main>
   
        <div class="Square">

        <center><h1>Home</h1>
		<h2>Welcome, $email</h2>
		<br>
		<h2>What would you like to do?<h2>
        <br>
            
        </center>
       
    </div>
</main>
</body>
</html>
