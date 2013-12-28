<?php
session_start();

if(!isset($_SESSION['LoggedIn'])) die('Restricted Access.');

$userID = $_SESSION['LoggedIn'][0];
$userSHA = $_SESSION['LoggedIn'][1];

include 'users.php';

//See if password is correct
if($userSHA != sha1($password[$userID] . $user[$userID])) die('Incorrent Password.');
?>
<html>
<head>
<title>zBank CP</title>
<style>
#container
{
	margin: 0 auto;
	width: 600px;
	background:#fff;
}

#header
{
	background:#ccc;
	padding: 20px;
}

#header h1 { margin: 0; }

#navigation
{
	float: left;
	width: 600px;
	background:#333;
}

#navigation ul
{
	margin: 0;
	padding: 0;
}

#navigation ul li
{
	list-style-type: none;
	display: inline;
}

#navigation li a
{
	display: block;
	float: left;
	padding: 5px 10px;
	color:#fff;
	text-decoration: none;
	border-right: 1px solid#fff;
}

#navigation li a:hover { background:#383; }

#content
{
	clear: left;
	padding: 20px;
}

#content h2
{
	color:#000;
	font-size: 160%;
	margin: 0 0 .5em;
}

#footer
{
	background:#ccc;
	text-align: right;
	padding: 20px;
	height: 1%;
}
</style>
<div id="container">
	<div id="header">
		<h1>
			zBank CP
		</h1>
	</div>
	<div id="navigation">
		<ul>
			<li><a href="adminCP.php">Bank</a></li>
			<li><a href="?s=1">Add Transaction</a></li>
			<li><a href="logout.php">Logout</a></li>
		</ul>
	</div>
	<div id="content">
	<?php
	$s = 0;
	$content = "<h2>Welcome</h2><p>Admins can add items, edit, delete, or logout.";
	if(isset($_GET['s'])) $s = $_GET['s'];
	//include class
	include 'classes.php';
	//Create bank
	$bank = new Bank('localhost', 'root', '');
	
	if($s == 1) {
		if(isset($_GET['submit'])) {
			//Add Item
			if($bank->addItem($_GET['item'], $_GET['quantity'], $_GET['donator'])) $content = '<h2>Success</h2><p>Transaction Added.</p>';
		}
		
		else {
			$content = "
			<h2>Add Transaction</h2>
			<p><form action='?s=1&'>Item: <input type='text' name='item'><br />
			 Quantity: <input type='text' name='quantity'> <br />
			 User From/To: <input type='text' name='donator'> <br />
			 <input type='hidden' name='s' value='1'>
			 <input type='submit' name='submit' value='Add Transaction'><br />
			 Adding a negative (-) in front of quantity will subtract item.<br />
			 To add go user item name \"money\".
			";
		}
	}
	
	else {
		$content = '';
		$bank->displayAll();
	}
	$bank->close();
	
	print $content;
	?>
	</div>
	<div id="footer">
		zSkillers Bank <a href='http://www.zskillers.com'>-Forum-</a>
	</div>
</div>