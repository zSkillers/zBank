<?php

$imageURL = $_GET['imageURL'];
$imageURL = str_replace(" ", "%20", $imageURL);
$quantity = $_GET['quantity'];
$ch = curl_init();
$timeout = 5;
curl_setopt($ch, CURLOPT_URL, $imageURL);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
$data = curl_exec($ch);
curl_close($ch);
$im = imagecreatefromstring($data);
if($quantity > 1 OR isset($_GET['displayNum'])){
	$yel = imagecolorallocate($im, 255, 255, 0);
	imagettftext($im, 8, 0, 8, 13, $yel, "arial.ttf", number_format($quantity));
}

header('Content-type: image/png');

imagepng($im);

imagedestroy($im);