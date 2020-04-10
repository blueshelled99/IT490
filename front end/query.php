<?php
	$email = $_GET["email"];
	$function = $_GET["function"];
	$candidate = $_GET["candidate"];

	$query = $email.$function.$candidate;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Main Menu</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>
	<?php echo $query; ?>
	<br><br>
	<?php echo $email; ?>
	<br>
	<?php echo $function; ?>
	<br>
	<?php echo $candidate; ?>
	<br><br>
	This is your query: <?php echo $_GET["email"]; ?><?php echo $_GET["function"]; ?><?php echo $_GET["candidate"]; ?>
</body>
</html>
