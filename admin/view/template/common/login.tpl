<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<title><?php echo $title; ?></title>

<!--                       CSS                       -->
	  
		<!-- Reset Stylesheet -->
		<link rel="stylesheet" href="view/stylesheet/reset.css" type="text/css" media="screen" />
	  
		<!-- Main Stylesheet -->
	<link id="dynamic_color" rel="stylesheet" href="view/stylesheet/reset.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="view/stylesheet/style.css" type="text/css" media="screen" />
		
		<!-- Invalid Stylesheet. This makes stuff look pretty. Remove it if you want the CSS completely valid -->
		<link rel="stylesheet" href="view/stylesheet/invalid.css" type="text/css" media="screen" />	
		
		<!-- Colour Schemes
	  
		Default colour scheme is green. Uncomment prefered stylesheet to use it.
		
		<link  rel="stylesheet" href="view/stylesheet/blue.css" type="text/css" media="screen" />
		
		<link rel="stylesheet" href="view/stylesheet/red.css" type="text/css" media="screen" />  
	 
		-->
		
		<!-- Internet Explorer Fixes Stylesheet -->
		
		<!--[if lte IE 7]>
			<link rel="stylesheet" href="view/stylesheet/ie.css" type="text/css" media="screen" />
		<![endif]-->
		
		<!--                       Javascripts                       -->
  
		<!-- jQuery -->
		<script type="text/javascript" src="view/javascript/js/jquery-1.3.2.min.js"></script>
		
		<!-- jQuery Configuration -->
		<script type="text/javascript" src="view/javascript/js/simpla.jquery.configuration.js"></script>
		
		<!-- Facebox jQuery Plugin -->
		<script type="text/javascript" src="view/javascript/js/facebox.js"></script>
		
		<!-- jQuery WYSIWYG Plugin -->
		<script type="text/javascript" src="view/javascript/js/jquery.wysiwyg.js"></script>
		
		<!-- jQuery Datepicker Plugin -->
		<script type="text/javascript" src="view/javascript/js/jquery.datePicker.js"></script>
		<script type="text/javascript" src="view/javascript/js/jquery.date.js"></script>
		<!--[if IE]><script type="text/javascript" src="view/javascript/js/jquery.bgiframe.js"></script><![endif]-->

		
		<!-- Internet Explorer .png-fix -->
		
		<!--[if IE 6]>
			<script type="text/javascript" src="view/javascript/js/DD_belatedPNG_0.0.7a.js"></script>
			<script type="text/javascript">
				DD_belatedPNG.fix('.png_bg, img, li');
			</script>
		<![endif]-->
<script type="text/javascript">
		$(document).ready(function(){
			function setCookie(key, value) {  
		        var expires = new Date();  
		        expires.setTime(expires.getTime() + 3650000); //1 year  
		        document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();  
		        }  
		       
		     function getCookie(key) {  
		        var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');  
		        return keyValue ? keyValue[2] : null;  
		        }  
	
			     	var arr = new Array('blue','green','red','yellow');
			     	rand =  Math.round(Math.random()*3); 
			         setCookie('color', arr[rand]);
			
	    $('#dynamic_color').attr('href','view/stylesheet/'+getCookie('color')+'.css');
		});
		</script>

</head>
<body id="login">
		
		<div id="login-wrapper" class="png_bg">
		<div id="login-top">
		<img src="view/images/logonc.png" alt="" />
			</div>
			
			<div id="login-content">
				
				 <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
				
					<div class="notification information png_bg">
						<div>
							<?php echo $text_login; ?>
						</div>
					</div>
					<div id="error" >
					
			 <?php if ($success) { ?>
     <p><?php echo $success; ?></p>
      <?php } ?>
				 <?php if ($error_warning) { ?>
      <p><?php echo $error_warning; ?></p>
      <?php } ?>
				<!-- Logo (221px width) -->
				
			</div> <!-- End #logn-top -->
					
					<p>
						<label><?php echo $entry_username; ?></label>
						 <input type="text" name="username" class="text-input"  value="<?php echo $username; ?>"  />
					
					</p>
					<div class="clear"></div>
					<p>
						<label>  <?php echo $entry_password; ?></label>
						 <input type="password" class="text-input" name="password" value="<?php echo $password; ?>"  />
						
					</p>
					<div class="clear"></div>
					<p>
						<input class="button" type="submit" onclick="$('#form').submit();" value="<?php echo $button_login; ?>" />
					</p>
					
				</form>
				 <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>
			</div> <!-- End #login-content -->
			
		</div> <!-- End #login-wrapper -->
		
  </body>
  
</html>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
