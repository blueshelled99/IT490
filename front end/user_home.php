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
<style>
  a{
    color: white;
  }
</style>
</head>
<body>
<main> 
   
        <div class="Square">

        <center><h1>Home</h1>
    <h1>Welcome, <?php echo $_SESSION['email'] ?></h1>
  <h2>What would you like to do?</h2>
  <a href="statecontributions.php">OpenFEC's Top 100 Presidential Contributions by State for 2020</a>
  <br><br>
  <a href="piecharts.php">OpenFEC's Candidates Aggregated Candidate Receipts and Disbursements Grouped by Office for 2020</a>
  <br><br>
  <form action="query.php" method="get" hidden>
      <input type="text" id="email" name="email" required minlength="3" maxlength="20" value="<?php echo $_SESSION['email']; ?>" readonly hidden>
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