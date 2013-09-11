<?php
if(!$_POST['city']) die("hack");
$city = $_POST['city'];

	$path = "image/weather/";
	$htm_url = @file_get_contents("http://vnexpress.net/ListFile/Weather/".$city.".xml");
	$we = explode('<AdImg>',$htm_url);
	$we = explode('</AdImg>',$we[1]);
	$we = $path.'images/'.$we[0];

	$we1 = explode('<AdImg1>',$htm_url);
	$we1 = explode('</AdImg1>',$we1[1]);
	$we1 = $path.$we1[0];

	$we2 = explode('<AdImg2>',$htm_url);
	$we2 = explode('</AdImg2>',$we2[1]);
	$we2 = $path.$we2[0];

	$we3 = explode('<Weather><![CDATA[',$htm_url);
	$we3 = explode(']]></Weather>',$we3[1]);
	$we3 = $we3[0];

	$we4 = explode('<City><![CDATA[',$htm_url);
	$we4 = explode(']]></City>',$we4[1]);
	$we4 = $we4[0];
?>
<br><?=$we4?><br>
<img src="<?=$we?>" border="0"/>
<img src="<?=$we1?>" border="0"/>
<img src="<?=$we2?>" border="0"/>
<img src="<?=$path?>c.gif" border="0"/>
<br><?=$we3?>