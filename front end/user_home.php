<?php
session_start();

if (! $_SESSION['logged_in']) {
	header("Location: index.html");
}

$user = $_SESSION['email'];
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
	<form action="query.php" method="get">
			<input type="text" id="email" name="email" required minlength="3" maxlength="20" value="<?php echo $user; ?>" readonly hidden>
			<select id="function" name="function">
				<option value=1>1</option>
				<option value=2>2</option>
				<option value=3>3</option>
			</select>
			<select id="candidate" name="candidate">
				<option value="A">A</option>
				<option value="B">B</option>
				<option value="C">C</option>
			</select>
                <input type="submit">
	</form>
       
    </div>
</main>
</body>
</html>
