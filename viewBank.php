<html>
<head>
<title>zBank Bank</title>
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
			zBank
		</h1>
	</div>
	<div id="navigation">
		<ul>
			<li><a href="viewBank.php">Bank</a></li>
			<li><a href="donator.php">Transactions</a></li>
			<li><a href="login.php">Login</a></li>
		</ul>
	</div>
	<div id="content">
	<?php
	//include class
	include 'classes.php';
	//Create bank
	$bank = new Bank('localhost', 'root', '');

		$bank->displayAll();
		$bank->close();
	
	?>
	</div>
	<div id="footer">
		Created by Zam & Chuxx Skills  - zSkillers Bank - <a href='http://www.zskillers.com'>Forum</a>
	</div>
</div>











