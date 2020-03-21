<?php
session_start();

if (! $_SESSION['logged_in']) {
	header("Location: index.html");
}

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
		<h2>What would you like to do?</h2>
        <br>
            
        </center>
       
    </div>
</main>
</body>
</html>