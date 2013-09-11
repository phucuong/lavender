<script type="text/javascript" src="image/weather/weather.js"></script>
<div id="load_div"></div>
<div class="box">
<div class="box-heading"><?php echo $heading_title; ?></div>
<div class="box-content" style="text-align: center;">
<?php $selected = 'selected="selected"';?>
  	  <select name="WeatherCity" onChange="weather();" id="WeatherCity">
          
          &nbsp; 
          
          <option value="Sonla" <?php if($code=='sonla') echo($selected);?>>Sơn La</option>
          
          &nbsp; 
          
          <option value="Hanoi" <?php if($code=='hanoi') echo($selected);?>>Hà Nội</option>
          
          &nbsp; 
          
          <option value="Haiphong" <?php if($code=='haiphong') echo($selected);?>>Hải Phòng</option>
          
          &nbsp; 
          
          <option value="Vinh" <?php if($code=='vinh') echo($selected);?>>Vinh</option>
          
          &nbsp; 
          
          <option value="Danang" <?php if($code=='danang') echo($selected);?>>Đà Nẵng</option>
          
          &nbsp; 
          
          <option value="Nhatrang" <?php if($code=='nhatrang') echo($selected);?>>Nha Trang</option>
          
          &nbsp; 
          
          <option value="Pleicu" <?php if($code=='pleicu') echo($selected);?>>Pleiku</option>
          
          &nbsp; 
          
          <option value="HCM" <?php if($code=='hochiminh') echo($selected);?>>TP HCM</option>
          
          &nbsp; 
        </select>
	<div id="weather" align="center"><script type="text/javascript">weather_current('<?php echo($code);?>');</script></div>
</div>
</div>
