<?php
$gb_lang = array();
$gb_translated_lang = array();

array_push($gb_lang, "bg_BG", "ms_MY", "sq", "sr_RS");

array_push($gb_translated_lang,"ar","be_BY","et","fi","ja","ko_KR","nb_NO","no","fr_BE","fr_CA","fr_CH","fr_FR","ru_RU","ru_UA","en_US","en_GB","es_ES","es_CL","es_PE",
"es_PR","es_VE","es_CO","nl_NL","nl_BE","hu_HU","de_DE","pt_BR","pt_PT","he_IL","it_IT","da_DK","pl_PL","sv_SE","zh_CN","zh_HK",
"zh_sg","zh_TW","zh","cs_CZ","sk_SK","el","hr","sl_SL","id_ID","ro_RO","nn_NO","uk","sl_SL", "th","tr_TR");

$language = get_locale();
?>
<div id="welcome-panel" class="welcome-panel" style="padding:0px !important;background-color: #f9f9f9 !important">
	<div class="welcome-panel-content">
		<img src="<?php echo plugins_url("/assets/images/gallery-bank.png" , dirname(__FILE__)); ?>" />
		<div class="welcome-panel-column-container">
			<div class="welcome-panel-column" style="width:240px !important;">
				<h4 class="welcome-screen-margin">
					<?php _e("Get Started", gallery_bank); ?>
				</h4>
					<a class="button button-primary button-hero" target="_blank" href="http://vimeo.com/92378296">
						<?php _e("Watch Gallery Video!", gallery_bank); ?>
					</a>
					<p>or, 
						<a target="_blank" href="http://tech-banker.com/products/wp-gallery-bank/knowledge-base/">
							<?php _e("read documentation here", gallery_bank); ?>
						</a>
					</p>
			</div>
			<div class="welcome-panel-column" style="width:250px !important;">
				<h4 class="welcome-screen-margin"><?php _e("Go Premium", gallery_bank); ?></h4>
				<ul>
					<li>
						<a href="http://tech-banker.com/products/wp-gallery-bank/" target="_blank" class="welcome-icon">
							<?php _e("Features", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="http://tech-banker.com/products/wp-gallery-bank/demo/" target="_blank" class="welcome-icon">
							<?php _e("Online Demos", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="http://tech-banker.com/products/wp-gallery-bank/pricing/" target="_blank" class="welcome-icon">
							<?php _e("Premium Pricing Plans", gallery_bank); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="welcome-panel-column" style="width:240px !important;">
				<h4 class="welcome-screen-margin">
					<?php _e("Knowledge Base", gallery_bank); ?>
				</h4>
				<ul>
					<li>
						<a href="http://tech-banker.com/forums/forum/gallery-bank-support/" target="_blank" class="welcome-icon">
							<?php _e("Support Forum", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="http://tech-banker.com/products/wp-gallery-bank/knowledge-base/" target="_blank" class="welcome-icon">
							<?php _e("FAQ's", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="http://tech-banker.com/products/wp-gallery-bank/" target="_blank" class="welcome-icon">
							<?php _e("Detailed Features", gallery_bank); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="welcome-panel-column welcome-panel-last" style="width:250px !important;">
				<h4 class="welcome-screen-margin"><?php _e("More Actions", gallery_bank); ?></h4>
				<ul>
					<li>
						<a href="http://tech-banker.com/shop/plugin-customization/order-customization-wp-gallery-bank/" target="_blank" class="welcome-icon">
							<?php _e("Plugin Customization", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="admin.php?page=gallery_bank_recommended_plugins" class="welcome-icon">
							<?php _e("Recommendations", gallery_bank); ?>
						</a>
					</li>
					<li>
						<a href="admin.php?page=gallery_bank_other_services" class="welcome-icon">
							<?php _e("Our Other Services", gallery_bank); ?>
						</a>
					</li>
				</ul>
			</div>
		</div>
	</div>
</div>
<script>
jQuery(document).ready(function()
{
	jQuery(".nav-tab-wrapper > a#<?php echo $_REQUEST["page"];?>").addClass("nav-tab-active");
});
</script>
<?php
switch($_REQUEST["page"])
{
	case "gallery_bank":
		$page = "Dashboard";
	break;
	case "gallery_bank_shortcode":
		$page = "Short-Codes";
	break;
	case "gallery_album_sorting":
		$page = "Album Sorting";
	break;
	case "global_settings":
		$page = "Global Settings";
	break;
	case "gallery_bank_system_status":
		$page = "System Status";
	break;
	case "gallery_bank_purchase":
		$page = "Purchase Pro Version";
	break;
	case "save_album":
		$page = "Album";
	break;
	case "images_sorting":
		$page = "Re-order Images";
	break;
	case "album_preview":
		$page = "Album Preview";
	break;
	case "gallery_bank_recommended_plugins":
		$page = "Recommendations";
	break;
	case "gallery_bank_other_services":
		$page = "Our Other Services";
	break;
	case "gallery_auto_plugin_update":
		$page = "Plugin Updates";
	break;
	case "gallery_bank_feature_request":
		$page = "Feature Request";
	break;
}
?>
<ul class="breadcrumb" style="margin-top: 10px;">
	<li>
		<i class="icon-home"></i>
		<a href="admin.php?page=gallery_bank"><?php _e("Gallery Bank", gallery_bank); ?></a>
		<span class="divider">/</span>
		<a href="#"><?php _e($page, gallery_bank); ?></a>
	</li>
</ul>

<?php
switch ($gb_role) 
{
	case "administrator":
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab " id="gallery_bank" href="admin.php?page=gallery_bank"><?php _e("Dashboard", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_shortcode" href="admin.php?page=gallery_bank_shortcode"><?php _e("Short-Codes", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_album_sorting" href="admin.php?page=gallery_album_sorting"><?php _e("Album Sorting", gallery_bank);?></a>
			<a class="nav-tab " id="global_settings" href="admin.php?page=global_settings"><?php _e("Global Settings", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_system_status" href="admin.php?page=gallery_bank_system_status"><?php _e("System Status", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_recommended_plugins" href="admin.php?page=gallery_bank_recommended_plugins"><?php _e("Recommendations", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_purchase" href="admin.php?page=gallery_bank_purchase"><?php _e("Premium Editions", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_other_services" href="admin.php?page=gallery_bank_other_services"><?php _e("Our Other Services", gallery_bank);?></a>
		</h2>
		<?php
	break;
	case "editor":
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab " id="gallery_bank" href="admin.php?page=gallery_bank"><?php _e("Dashboard", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_shortcode" href="admin.php?page=gallery_bank_shortcode"><?php _e("Short-Codes", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_album_sorting" href="admin.php?page=gallery_album_sorting"><?php _e("Album Sorting", gallery_bank);?></a>
			<a class="nav-tab " id="global_settings" href="admin.php?page=global_settings"><?php _e("Global Settings", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_system_status" href="admin.php?page=gallery_bank_system_status"><?php _e("System Status", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_recommended_plugins" href="admin.php?page=gallery_bank_recommended_plugins"><?php _e("Recommendations", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_purchase" href="admin.php?page=gallery_bank_purchase"><?php _e("Premium Editions", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_other_services" href="admin.php?page=gallery_bank_other_services"><?php _e("Our Other Services", gallery_bank);?></a>
		</h2>
		<?php
	break;
	case "author":
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab " id="gallery_bank" href="admin.php?page=gallery_bank"><?php _e("Dashboard", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_shortcode" href="admin.php?page=gallery_bank_shortcode"><?php _e("Short-Codes", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_album_sorting" href="admin.php?page=gallery_album_sorting"><?php _e("Album Sorting", gallery_bank);?></a>
			<a class="nav-tab " id="global_settings" href="admin.php?page=global_settings"><?php _e("Global Settings", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_recommended_plugins" href="admin.php?page=gallery_bank_recommended_plugins"><?php _e("Recommendations", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_purchase" href="admin.php?page=gallery_bank_purchase"><?php _e("Premium Editions", gallery_bank);?></a>
			<a class="nav-tab " id="gallery_bank_other_services" href="admin.php?page=gallery_bank_other_services"><?php _e("Our Other Services", gallery_bank);?></a>
		</h2>
		<?php
	break;
}
if($_REQUEST["page"] != "gallery_bank_feature_request")
{
	?>
	<div class="custom-message green" style="display: block;margin-top:30px">
		<div style="padding: 4px 0;">
			<p style="font:12px/1.0em Arial !important;font-weight:bold;">If you don't find any features you were looking for in this Plugin, 
				please write us <a target="_self" href="admin.php?page=gallery_bank_feature_request">here</a> and we shall try to implement this for you as soon as possible! We are looking forward for your valuable <a target="_self" href="admin.php?page=gallery_bank_feature_request">Feedback</a></p>
		</div>
	</div>
	<?php
}
if(in_array($language, $gb_lang))
{
	?>
	<div class="custom-message red" style="display: block;margin-top:30px">
		<div style="padding: 4px 0;">
			<p style="font:12px/1.0em Arial !important;font-weight:bold;">This plugin language is translated with the help of Google Translator.</p>
				<p style="font:12px/1.0em Arial !important;">If you would like to translate &amp; help us, we will reward you with a free Eco Version License of Gallery Bank.</p>
				<p style="font:12px/1.0em Arial !important;">Contact Us at <a target="_blank" href="http://tech-banker.com">http://tech-banker.com</a> or email us at <a href="mailto:support@tech-banker.com">support@tech-banker.com</a></p>
		</div>
	</div>
	<?php
}
elseif(!(in_array($language, $gb_translated_lang)) && !(in_array($language, $gb_lang)) && $language != "")
{
	?>
	<div class="custom-message red" style="display: block;margin-top:30px">
		<div style="padding: 4px 0;">
			<p style="font:12px/1.0em Arial !important;font-weight:bold;">If you would like to translate Gallery Bank in your native language, we will reward you with a free Eco Version License of Gallery Bank.</p>
				<p style="font:12px/1.0em Arial !important;">Contact Us at <a target="_blank" href="http://tech-banker.com">http://tech-banker.com</a> or email us at <a href="mailto:support@tech-banker.com">support@tech-banker.com</a></p>
		</div>
	</div>
	<?php
}
if (!(is_dir(GALLERY_MAIN_THUMB_DIR)))
{
	if(!(is_dir_empty(GALLERY_MAIN_THUMB_DIR)))
	{
		?>
		<div class="custom-message red" style="display: block;margin-top:15px">
			<span>
				<strong>If you are getting problems with thumbnails, then you need to set 775(write) permissions to <?php echo GALLERY_MAIN_DIR ?> (recursive files &amp; directories) in order to save the images/thumbnails. </strong>
			</span>
		</div>
		<?php
	}
}
function is_dir_empty($dir)
{
	if (!is_readable($dir)) return NULL; 
	$handle = opendir($dir);
	while (false !== ($entry = readdir($handle))) {
		if ($entry != "." && $entry != "..") {
			return FALSE;
		}
	}
	return TRUE;
}
?>