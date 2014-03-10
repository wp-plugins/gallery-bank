<?php
global $wpdb;
global $current_user;
$current_user = wp_get_current_user();
if (!current_user_can("edit_posts") && ! current_user_can("edit_pages"))
{
	return;
}
else
{
?>
<div class="block well" style="min-height:1094px;">
	<div class="navbar">
		<div class="navbar-inner">
			<h5><?php _e( "Global Settings", gallery_bank ); ?></h5>
		</div>
	</div>
	<div class="body" style="margin:10px;">
		<a href="http://gallery-bank.com/" target="_blank"><img style="float:right;cursor: pointer;width: 100%" src="<?php echo GALLERY_BK_PLUGIN_URL.'/assets/images/settings_page.png' ?>"/></a>
	</div>
</div>
<?php
}
?>