<?php if (isset($_SERVER['HTTP_USER_AGENT']) && !strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6')) echo '<?xml version="1.0" encoding="UTF-8"?>'. "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/colorbox/jquery.colorbox.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/colorbox/colorbox.css" media="screen" />
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<!--[if IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?php echo $google_analytics; ?>
</head>
<body>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href=""><label id="head_logo">Lavender Shop</label></a></div>
  <?php } ?>
  <?php echo $language; ?>
  <?php echo $currency; ?>
  <?php echo $cart; ?>
  <div id="search">
    <div class="button-search"></div>
    <?php if ($filter_name) { ?>
    <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
    <?php } else { ?>
    <input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
    <?php } ?>
  </div>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href=""><?php echo $text_home; ?></a><a href="<?php echo $wishlist; ?>" id="wishlist-total"><?php echo $text_wishlist; ?></a><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><a href="<?php echo $shopping_cart; ?>"><?php echo $text_shopping_cart; ?></a><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
</div>
<div id="menu">
  <ul>
  <?php if($home_active == 1){ ?>
   <li class="active"><a href="/"><?php echo $text_home; ?></a></li>
   <?php } else { ?>
  <li><a href=""><?php echo $text_home; ?></a></li>
  <?php } ?>
  <?php if($informations){ ?>
  <?php foreach ($informations as $information) { ?>
  	<?php if($information['id'] == $information_id){?>
  	  <li><a class="active" href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
  	<?php }else{?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
    <?php } ?>
  <?php } ?>
  <?php } ?>
 <!--  <li><a href="index.php?route=product/new"><?php echo $text_product;?></a></li> -->
	
  <?php if ($categories) { ?>
    <?php foreach ($categories as $category) { ?>
	<?php if($category['category_id'] == $category_id){ ?>
     <li><a class="active" href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	<?php }else{ ?>
	 <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
	<?php } ?> 
      <?php if ($category['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($category['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($category['children'][$i])) { ?>
          <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
	<?php } ?>
	
	<?php if ($categoriesnews) { ?>
    <?php foreach ($categoriesnews as $cat) { ?>
	 <?php if($cat['cat_id'] == $cat_id){ ?>
		<li><a class="active" href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a>
	 <?php }else{ ?>
	 	<li><a href="<?php echo $cat['href']; ?>"><?php echo $cat['name']; ?></a>
	<?php } ?>	
      <?php if ($cat['children']) { ?>
      <div>
        <?php for ($i = 0; $i < count($cat['children']);) { ?>
        <ul>
          <?php $j = $i + ceil(count($cat['children']) / $cat['column']); ?>
          <?php for (; $i < $j; $i++) { ?>
          <?php if (isset($cat['children'][$i])) { ?>
          <li><a href="<?php echo $cat['children'][$i]['href']; ?>"><?php echo $cat['children'][$i]['name']; ?></a></li>
          <?php } ?>
          <?php } ?>
        </ul>
        <?php } ?>
      </div>
      <?php } ?>
    </li>
    <?php } ?>
	<?php } ?>
	<?php if($route == 'information/contact'){ ?>
		<li><a class="active" href="index.php?route=information/contact"><?php echo $text_contact;?></a></li>
	<?php }else{ ?>
		<li><a href="index.php?route=information/contact"><?php echo $text_contact;?></a></li>
	<?php } ?>

  </ul>
  

  
</div>
<div id="notification"></div>
