<div class="box">
	<div class="box-heading"><?php echo $heading_title; ?></div>
	<div class="box-content">
    	<div>
			<img src="<?php echo $imgPath; ?>today.png" width="16" height="16" alt="" />
			&nbsp;<?php echo $data['day']; ?>&nbsp;<?php echo $text_today; ?>
		</div>
		<div>
			<img src="<?php echo $imgPath; ?>week.png" width="16" height="16" alt="" />
			&nbsp;<?php echo $data['week']; ?>&nbsp;<?php echo $text_week; ?>
		</div>
		<div>
			<img src="<?php echo $imgPath; ?>month.png" width="16" height="16" alt="" />
			&nbsp;<?php echo $data['month']; ?>&nbsp;<?php echo $text_month; ?>
		</div>
		<div>
			<img src="<?php echo $imgPath; ?>year.png" width="16" height="16" alt="" />
			&nbsp;<?php echo $data['year']; ?>&nbsp;<?php echo $text_year; ?>
	 	</div>
	 	<div>
			<img src="<?php echo $imgPath; ?>all.png" width="16" height="16" alt="" />
			&nbsp;<?php echo $data['all']; ?>&nbsp;<?php echo $text_all; ?>
		</div>
		<div style="padding: 5px 22px;">
			<?php echo $data['online'] .' '. $text_online; ?>
		</div>
		<?php echo $t2vn; ?>
	</div>
</div>