<?php
session_start();if (! $_SESSION['logged_in']) {
	header("Location: index.html");
}$user = $_SESSION['email'];
//echo "<h1>$user</h1>";

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
	<h1><?php echo $user; ?></h1>
	<form action ="" method="post">
                <table>
                    <tr>
                        <td>E-mail:</td>
                        <td><input type="text" id="email" name="email" required minlength="3" maxlength="20" value="<?php echo $user; ?>" readonly></td> <!-- you should add the hidden attribute to the input tag here -->
		    </tr>              
		</table>
                <input type="submit" name="submit" value="submit query">
	</form>    
	</div>
</main>
</body>
</html>
