<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  
  
  <?php if ($newss) { ?>
  
  <?php foreach ($newss as $news) { ?>
  <table width="100%"> 
		<tr>
			<td> 
				<?php if($news['image'] != '') { ?><img src="<?php echo $news['image'];?>" style="float:left;margin-right:5px;width: <?php echo  $this->config->get('config_image_news_width');?>px;height: <?php echo  $this->config->get('config_image_news_height');?>px;border: 1px solid #CCCCCC;"><?php } ?> <a href="<?php echo $news['href']; ?>" style="font-size:18px;font-weight:bold;"><?php echo $news['name']; ?> </a> <br><?php echo $news['description']; ?>
			</td>
		</tr>
		<tr>
			<td align="center" background="catalog/view/theme/default/image/cach.png">&nbsp;</td>
		</tr>
	</table>
  <?php } ?>
 
  <div class="pagination"><?php echo $pagination; ?></div>
  <?php } ?>
  <?php if (!$categories && !$newss) { ?>
  <div class="content"><?php echo $text_empty; ?></div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php } ?>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>