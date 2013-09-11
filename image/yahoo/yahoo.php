<?php
$img = $_GET['img'];
if(!$img || $img == '') $img = 1;
$id = $_GET['id'];
if(!$id || $id == '') header("location: /");
header("Content-Type: application/jpg");
$online = "online".$img.".gif";
$offline = "offline".$img.".gif";
$buka = fopen("http://opi.yahoo.com/online?u=".$id."&m=t","r") or header("location: /");
while ($baca = fread( $buka, 2048 )){ 
	$status = $baca; 
}
fclose($buka);

if($status == $id." is ONLINE")readfile($online);
else readfile($offline);

?>