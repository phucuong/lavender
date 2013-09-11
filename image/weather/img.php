<?php
$id = $_GET['id'];
//if(!$id || $id == '') header("location: /");
header("Content-Type: application/gif");
$img = 'image/'.$id;
if(@file_exists($img)) @readfile($img);
else{
	$link = 'http://vnexpress.net/Images/Weather/'.$id;		
	@file_put_contents($img,@file_get_contents($link));
	@readfile($img);
}


?>
