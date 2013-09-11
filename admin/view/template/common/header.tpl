<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"
	dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>"
	xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>"
	rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" href="view/stylesheet/stylesheet.css"
	type="text/css" media="screen" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css"
	href="<?php echo $style['href']; ?>"
	media="<?php echo $style['media']; ?>" />
<?php } ?>




<!--                       CSS                       -->

<!-- Reset Stylesheet -->
<link rel="stylesheet" href="view/stylesheet/reset.css" type="text/css"
	media="screen" />

<!-- Main Stylesheet -->
  <link id="dynamic_color" rel="stylesheet" href="view/stylesheet/reset.css" type="text/css" media="screen" />
<link rel="stylesheet" href="view/stylesheet/style.css" type="text/css"media="screen" />
	

<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
<link rel="stylesheet" href="view/stylesheet/invalid.css"
	type="text/css" media="screen" />
		<!-- Colour Schemes
			<link rel="stylesheet" href="view/stylesheet/.css" type="text/css" media="screen" />
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
	 
		-->

<!-- Internet Explorer Fixes Stylesheet -->

<!--[if IE]>
			<link rel="stylesheet" href="view/stylesheet/ie.css" type="text/css" media="screen" />
		<![endif]-->

<!--                       Javascripts                       -->

<!-- jQuery -->
<script type="text/javascript"
	src="view/javascript/js/jquery-1.3.2.min.js"></script>

<!-- jQuery Configuration -->
<script type="text/javascript"
	src="view/javascript/js/simpla.jquery.configuration.js"></script>

<!-- Facebox jQuery Plugin -->
<script type="text/javascript" src="view/javascript/js/facebox.js"></script>

<!-- jQuery WYSIWYG Plugin -->
<script type="text/javascript"
	src="view/javascript/js/jquery.wysiwyg.js"></script>

<!-- jQuery Datepicker Plugin -->

<script type="text/javascript" src="view/javascript/js/jquery.date.js"></script>
<!--[if IE]><script type="text/javascript" src="view/javascript/js/jquery.bgiframe.js"></script><![endif]-->


<!-- Internet Explorer .png-fix -->

<!--[if IE 6]>
			<script type="text/javascript" src="view/javascript/js/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->

<script type="text/javascript"
	src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript"
	src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link rel="stylesheet" type="text/css"
	href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript"
	src="view/javascript/jquery/ui/external/jquery.bgiframe-2.1.2.js"></script>
<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>
<script type="text/javascript"
	src="view/javascript/jquery/superfish/js/superfish.js"></script>
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
$(document).ready(function(){
    // Confirm Delete
    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    	
    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall', 1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });

    $('.breadcrumb').append(
    		'<div id="colour">'
    		+
    		'<ul>'+
    			'<li class="color" rel="green" style="background:#499600"></li>'+
    			'<li class="color" rel="blue" style="background:#0071B1;"></li>'+
    			'<li class="color" rel="yellow" style="background:#ac9f00"></li>'+
    			'<li class="color" rel="red" style="background:#B20000"></li>'+
    		'</ul>'
    		+
    	'</div>'
    	    );
    function setCookie(key, value) {  
        var expires = new Date();  
        expires.setTime(expires.getTime() + 3650000); //1 year  
        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();  
        }  
       
     function getCookie(key) {  
        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');  
        return keyValue ? keyValue[2] : null;  
        }  
    
    $('.color').click(function(){
    	color=$(this).attr('rel');
   	 setCookie('color', color);  
   	 $('#dynamic_color').attr('href','view/stylesheet/'+getCookie('color')+'.css');
     });
		if(!getCookie('color')){
			     	var arr = new Array('blue','green','red','yellow');
			     	rand =  Math.round(Math.random()*3); 
			         setCookie('color', arr[rand]);
			         }
    $('#dynamic_color').attr('href','view/stylesheet/'+getCookie('color')+'.css');
});
</script>
</head>
<body <?php if (!$logged) { ?> style="background: none" <?php } ?>>
<div id="body-wrapper"><!-- Wrapper for the radial gradient background -->

	
    <?php if ($logged) { ?>
   <div id="sidebar">
<div id="sidebar-wrapper"><!-- Sidebar with logo and menu -->
<h1 id="sidebar-title"><img src="view/images/logonc.png" alt="" /></h1>
    <?php } ?>
 
  <?php if ($logged) { ?>
  <div id="profile-links">
				<?php echo $logged; ?><br />
<br />
<a onClick="window.open('<?php echo $store; ?>');"><?php echo $text_front; ?></a>
| <a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
</div>
<ul id="main-nav">
	<!-- Accordion Menu -->
	<li ><a href="<?php echo $home; ?>" class="nav-top-item no-submenu"><?php echo $text_dashboard; ?></a></li>
	
	<li><a href="#" class="nav-top-item "> <?php echo $text_content; ?> </a>
		<ul>
			<li><a href="<?php echo $cat; ?>"><?php echo $text_cat; ?></a></li>
			<li><a href="<?php echo $news; ?>"><?php echo $text_news; ?></a></li>
			<li><a href="<?php echo $comment; ?>"><?php echo $text_comment; ?></a></li>
		</ul>
	</li>		
	<li><a href="#" class="nav-top-item "> <!-- Add the class "current" to current menu item --><?php echo $text_catalog; ?></a>
	<ul>
		 <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
         
              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
        
          <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li>
		  
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
		  <!--
          <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
		  -->
          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
         
	</ul>
	</li>
	<li><a href="#" class="nav-top-item "> <?php echo $text_tool; ?> </a>
		<ul>
			 <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
			 <li><a onclick="image_manager('','');"><?php echo $text_image; ?></a></li>
			 <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
			  <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
		</ul>
	</li>		


	<li><a href="#" class="nav-top-item">
						<?php echo $text_extension; ?>
					</a>
	<ul>
		<li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li>
		<li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
		<li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
		<li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
		<!--<li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li>-->
		
	</ul>
	</li>


	<li><a href="#" class="nav-top-item">
						<?php echo $text_sale; ?>
					</a>
	<ul>
		<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
		<li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
		<li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
		
		<li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
		<!--<li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>-->
		<li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
		<li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
		<li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
		
		<li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
	</ul>
	</li>

	<li><a href="#" class="nav-top-item">
					<?php echo $text_system; ?>
					</a>
	<ul>
		<li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
		<li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li> 
		<li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
		<li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
		<li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li>
		
	</ul>
	</li>
	<li><a href="#" class="nav-top-item"><?php echo $text_localisation; ?></a>
            <ul>
              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
              <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
              <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
              <li><a href="<?php echo $return_status; ?>"><?php echo $text_return; ?></a>
              <!-- 
                <ul>
                  
                </ul>
                --> 
              </li>
              <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
              <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
              <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
              <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax; ?></a></li>
			  <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
                  <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>

              <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
            </ul>
          </li>

	<li><a href="#" class="nav-top-item">
						<?php echo $text_reports; ?>
					</a>
	<ul>

		<li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
		<!--<li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
		<li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
		<li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
		<li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
		-->


		<li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
		<li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>


		<li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
		<!--<li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>-->
		<li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>


		<!--<li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>-->

	</ul>
	</li>



	</li>

</ul>
<script type="text/javascript"><!--
function image_manager(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).attr('value')),
					dataType: 'text',
					success: function(text) {
						$('#' + thumb).replaceWith('<img src="' + text + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>

<script type="text/javascript"><!--

function getURLVar(urlVarName) {
	var urlHalves = String(document.location).toLowerCase().split('?');
	var urlVarValue = '';
	
	if (urlHalves[1]) {
		var urlVars = urlHalves[1].split('&');

		for (var i = 0; i <= (urlVars.length); i++) {
			if (urlVars[i]) {
				var urlVarPair = urlVars[i].split('=');
				
				if (urlVarPair[0] && urlVarPair[0] == urlVarName.toLowerCase()) {
					urlVarValue = urlVarPair[1];
				}
			}
		}
	}
	
	return urlVarValue;
} 

    
$(document).ready(function() {
	h = screen.width; 
    right=h-276; 
	  $('#content').css('width',right);
	route = getURLVar('route');
	
	if (!route) {
		
		$('#dashboard').addClass('current');
	} else {
		
		part = route.split('/');
		
		url = part[0];
		if (part[1]) {
			url += '/' + part[1];
		}

		if(part[0]=='module'||part[0]=='payment'||part[0]=='total'||part[0]=='feed'){
			$('a[href*=\'' + part[0] +  '\']').addClass('current').parents('ul').css('display','block').prev('.nav-top-item').addClass('current');
			}else if(part[0]=='shipping' && part[1]=='flat'){
				$('a[href*=\'' + 'extension/shipping' +  '\']').addClass('current').parents('ul').css('display','block').prev('.nav-top-item').addClass('current');
			}else{
				$('a[href*=\'' + url +  '\']').addClass('current').parents('ul').css('display','block').prev('.nav-top-item').addClass('current');
				}
	}
});


//--></script> <!--[if IE]>
	 <script type="text/javascript">
$(document).ready(function() {
	h = screen.width; 
    right=h-280; 
	  $('#content').css('width',right);

});
 </script>
<![endif]-->
</div>
</div>
  <?php } ?>


