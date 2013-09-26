<?php
global $wpdb;
$url = plugins_url('', __FILE__);
?>
<div class="wrapper">
	<div class="content">
		<div class="body">
			<img style="margin-top:20px;margin-bottom:20px;" src="<?php echo GALLERY_BK_PLUGIN_URL .'/logo.png'; ?>"/>
			<a href="http://gallery-bank.com/" target="_blank"><img style="float:right;cursor: pointer;" src="<?php echo GALLERY_BK_PLUGIN_URL.'/banner.png' ?>"/></a>
			<div class="message red" style="display: block;">
				<span>
					<strong><?php _e("You will be only allowed to add 2 galleries and upto 10 images in each album. Kindly purchase Pro Version for full access.", gallery_bank); ?></strong>
				</span>
			</div>
			<?php
				include_once GALLERY_BK_PLUGIN_DIR .'/install-script.php';
			?>
			
			