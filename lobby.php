<?php

require_once('session.php');

require_once('connection.php');

$success = true;

$verification = 0;

if(!isset($_SESSION["username"]) and !isset($_GET["page"])) 
{
	$verification = 0;
}

if(ISSET($_GET["page"]) AND $_GET["page"] == "log") 
{
	if(is_numeric($_POST["user"])){
	$user = $_POST["user"];
	$password = $_POST["password"];

	if ($_POST["acct"] == "client") {
		$str = "select password from clientaccount where client_id = " . $user;
		
		$result = @mysqli_query($dbc, $str);
		
		if($result){
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$match_password = $row['password'];
			}

			if($password == $match_password){
				$_SESSION["username"] = $user;
				$verification = 1;
			} 
			else 
			{
				$verification = 2;
			}
		} else {
			$verification = 2;
		}
	}
	else {
		$str = "select password from hotelmanageraccount where manager_id = " . $user;
		$result = @mysqli_query($dbc, $str);
		
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) 
		{
			$match_password = $row['password'];
		}
		if($password == $match_password)
		{
			$_SESSION["managerid"] = $user;
			$verification = 4;
		} 
		else 
		{
			$verification = 2;
		}
	}

} 
else 
{
	$verification = 3;
}
}
?>

<!DOCTYPE>
<html>
<head>
	<title>Log In</title>
	<?php
	if($verification == 1 && $_POST["acct"] == "client") {
	?>
		<meta http-equiv="refresh" content="3; URL=website.php"
	<?php
	}
	if($verification == 4 && $_POST["acct"] == "manager") {
	?>
		<meta http-equiv="refresh" content="3; URL=website2.php"
	<?php
	}
	?>
</head>
<body>
<?php 
if($verification == 0) {
?>
<div align="center">
<h2>Sterling Cooper Draper Price Hotels <h2>
</div>


<fieldset style="width:250px"><legend>Welcome, Please log in below</legend>
	<form method="post" action="lobby.php?page=log">

		<p><b>Login Type:</b><br>
			<input type="radio" name="acct" value="client" checked>Client<br>
			<input type="radio" name="acct" value="manager">Manager<hr>

		<p><b>ID:</b> <input type="text" name="user" maxlength="30" /></p>
		<p><b>Password:</b> <input type="password" name="password" maxlength="20" /></p>

		<p><input type="submit" value="Log In" /></p>
	</form>	
	<form method="get" action="registration.php">
		<button type="submit">Register</button>
	</form>
</fieldset>


<?php
}
if($verification == 1) {

echo 'ID and password verified. Loading site...';

} 

if($verification == 4) {

echo 'ID and password verified. Loading site...';

}  
if($verification == 2) {

echo 'ID and/or password not recognized, <a href="lobby.php">Go Back to Log In </a>.';

}
if($verification == 3) {

echo 'ID must be numeric, <a href="lobby.php">Go Back to Log In </a>.';

}

?>
</body>
</html>