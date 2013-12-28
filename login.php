<?php
/*	
 *  Description: 	Login To Clan Bank
 *  
 *  Notes: None yet.
 *  
 */


//Start Session
session_start();

//Declare error
$error = '';

//Information Submitted?
if(isset($_POST['LOGIN'])) {

	include 'users.php';
	$userID = array_search(strtolower($_POST['username']), $user);

	if(isset($userID)) {
		
		if($password[$userID] === strtolower($_POST['password'])) {
			$_SESSION['LoggedIn'] = array($userID, sha1($_POST['password'] . $_POST['username']));
		}
		else {
			$error =  '<br />Incorrect Password.';
		}
		
	}

}

//See If they are logged in
if(!isset($_SESSION['LoggedIn']) OR $_SESSION['LoggedIn'][1] !== sha1($password[$_SESSION['LoggedIn'][0]] . $user[$_SESSION['LoggedIn'][0]])) {

	print "
			<html>
			<head>
			<style>
			body {
			background-color:#000000; 
			margin-top:0px;
			margin-left:0px;
			}
			#login_Header {
			margin-top:200px;
			margin-left:auto;
			margin-right:auto;
			background-color:#A00000;
			width:500px;
			height:50px;
			border-top-left-radius:25px;
			border-top-right-radius:25px;
			}
			#login_Header_Text {
			margin-left:20px;
			padding-top:7px;
			color:white;
			text-transform:uppercase;
			font-size:200%;
			}
			#login_Content {
			margin-top:-30px;
			background-color:#FFFFFF;
			margin-left:auto;
			margin-right:auto;
			width:500px;
			height:220px;
			border-bottom-left-radius:25px;
			border-bottom-right-radius:25px;
			}
			#username {
			font-size:150%;
			padding-left:30px;
			padding-top:30px;
			}
			#password {
			font-size:150%;
			padding-left:145px;
			padding-top:15px;
			}
			#input {
			width:200px;
			height:30px;
			}
			#loginBTN {
			margin-left:150px;
			width:200px;
			height:30px;
			background-color:#A00000;
			border-style:none;

			}
			</style>
			<body>
				<form action=\"login.php\" method=\"POST\">
				<div id=\"login_Header\">
				<p id=\"login_Header_Text\">Login</p>
				</div>
				<div id=\"login_Content\">
				<p id=\"username\">Runescape Username: <input id=\"input\" type=\"input\" name=\"username\"></p>
				<p id=\"password\">Password: <input id=\"input\" type=\"password\" name=\"password\"></p>
				<p><input type=\"submit\" id='loginBTN' name=\"LOGIN\">$error</p>
				</div>
				</form>
			</body>
			</html>
			";

}

else {

	header("Location: adminCP.php");

}






















//EOF: login.php