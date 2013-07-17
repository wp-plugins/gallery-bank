<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
function create_global_menus_for_gallery_bank()
{
	global $wpdb;
	
		$menu = add_menu_page('Gallery Bank', __('Gallery Bank', gallery_bank), 'administrator', 'gallery_bank','',GALLERY_BK_PLUGIN_URL . '/icon.png');
		add_submenu_page('', 'Gallery Bank', __('Gallery Bank', gallery_bank), 'administrator', 'gallery_bank', 'gallery_bank');
		add_submenu_page('', '','' , 'administrator', 'add_album', 'add_album');
		add_submenu_page('', '','' , 'administrator', 'view_album', 'view_album');
		add_submenu_page('', '','' , 'administrator', 'images_sorting', 'images_sorting');
		add_submenu_page('', '','' , 'administrator', 'edit_album', 'edit_album');
		add_submenu_page('', '','' , 'administrator', 'pro_version', 'pro_version');
	
}
//--------------------------------------------------------------------------------------------------------------//
// FUNCTIONS FOR REPLACING TABLE NAMES
//--------------------------------------------------------------------------------------------------------------//

function gallery_bank_albums()
{
	global $wpdb;
	return $wpdb->prefix . 'gallery_albums';
}
function gallery_bank_pics()
{
	global $wpdb;
	return $wpdb->prefix . 'gallery_pics';
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//
function gallery_bank()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/menus-gallery-bank.php';
	
	include_once GALLERY_BK_PLUGIN_DIR .'/views/album.php';
}

function add_album()
{
	global $wpdb;
	$album_count = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(album_id) FROM ".gallery_bank_albums(),""
		)
	);
	if($album_count < 2)
	{
		global $wpdb;
		include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
		include_once GALLERY_BK_PLUGIN_DIR .'/views/menus-gallery-bank.php';
		include_once GALLERY_BK_PLUGIN_DIR .'/views/add-album.php';
	}
	else 
	{
		?>
		<script type="text/javascript">
			window.location.href="admin.php?page=gallery_bank"; 
		</script>
		<?php
	}
}
function view_album()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/menus-gallery-bank.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/view-album.php';
}
function edit_album()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/menus-gallery-bank.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/edit-album.php';
}
function images_sorting()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/menus-gallery-bank.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/images_sorting.php';
}
//--------------------------------------------------------------------------------------------------------------//
//CODE FOR CALLING JAVASCRIPT FUNCTIONS
//--------------------------------------------------------------------------------------------------------------//
function plugin_js_scripts_gallery_bank()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery.datatables.js', GALLERY_BK_PLUGIN_URL .'/js/jquery.datatables.js');
	wp_enqueue_script('jquery.validate.min.js', GALLERY_BK_PLUGIN_URL .'/js/jquery.validate.min.js');
	wp_enqueue_script('bootstrap-bootbox.min.js', GALLERY_BK_PLUGIN_URL .'/js/bootstrap-bootbox.min.js');
	wp_enqueue_script('bootstrap.min.js', GALLERY_BK_PLUGIN_URL .'/js/bootstrap.min.js');
	wp_enqueue_script('visuallightbox.js', GALLERY_BK_PLUGIN_URL .'/js/visuallightbox.js');
}
function frontend_plugin_js_scripts_gallery_bank()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('visuallightbox.js', GALLERY_BK_PLUGIN_URL .'/js/visuallightbox.js');
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CALLING STYLE SHEETS
function plugin_css_scripts_gallery_bank()
{
	wp_enqueue_style('menu.css', GALLERY_BK_PLUGIN_URL .'/css/menu.css');
	wp_enqueue_style('forms.css', GALLERY_BK_PLUGIN_URL .'/css/forms.css');
	wp_enqueue_style('style.css', GALLERY_BK_PLUGIN_URL .'/css/style.css');
	wp_enqueue_style('bootstrap.css', GALLERY_BK_PLUGIN_URL .'/css/bootstrap.css');
	wp_enqueue_style('datatables.css', GALLERY_BK_PLUGIN_URL .'/css/datatables.css');
	wp_enqueue_style('system-message.css', GALLERY_BK_PLUGIN_URL .'/css/system-message.css');
	wp_enqueue_style('forms-btn.css', GALLERY_BK_PLUGIN_URL .'/css/forms-btn.css');
	wp_enqueue_style('visuallightbox.css', GALLERY_BK_PLUGIN_URL .'/css/visuallightbox.css');
	wp_enqueue_style('plugins.css', GALLERY_BK_PLUGIN_URL .'/css/plugins.css');
	
}
function frontend_plugin_css_scripts_gallery_bank()
{
	
	wp_enqueue_style('visuallightbox.css', GALLERY_BK_PLUGIN_URL .'/css/visuallightbox.css');
}
//--------------------------------------------------------------------------------------------------------------//
// REGISTER AJAX BASED FUNCTIONS TO BE CALLED ON ACTION TYPE AS PER WORDPRESS GUIDELINES
//--------------------------------------------------------------------------------------------------------------//
if(isset($_REQUEST['action']))
{
	switch($_REQUEST['action'])
	{
		case "menu_gallery_library":
		add_action( 'admin_init', 'menu_gallery_library');
		function menu_gallery_library()
		{
			global $wpdb;
			include_once GALLERY_BK_PLUGIN_DIR . '/lib/menus-gallery-bank-class.php';
		}
		case "album_gallery_library":
		add_action( 'admin_init', 'album_gallery_library');
		function album_gallery_library()
		{
			global $wpdb;
			include_once GALLERY_BK_PLUGIN_DIR . '/lib/album-gallery-bank-class.php';
		}
		
		case "front_albums_gallery_library":
		add_action( 'admin_init', 'front_albums_gallery_library');
		function front_albums_gallery_library()
		{
			global $wpdb;
			include_once GALLERY_BK_PLUGIN_DIR . '/lib/front-view-album-class.php';
		}
	}
}
function gallery_bank_short_code($atts) 
{
	extract(shortcode_atts(array(
	"album_id" => '',
	), $atts));
	$con = '';
	foreach((array)$album_id as $f)
	{
		$con .=  $f;
	}
	return extract_short_code($con);
}
function gallery_bank_short_code_album($atts) 
{
	extract(shortcode_atts(array(
	"album_id" => '',
	), $atts));
	$con = '';
	foreach((array)$album_id as $f)
	{
		$con .=  $f;
	}
	return extract_short_code_album($con);
}
function extract_short_code($con) 
{
	$album_id = $con;
	ob_start();
	require GALLERY_BK_PLUGIN_DIR.'/views/front_view.php';
	$gallerybank_output_album = ob_get_clean();
	wp_reset_query();
	return $gallerybank_output_album;
}
function extract_short_code_album($con) 
{
	$album_id = $con;
	ob_start();
	require GALLERY_BK_PLUGIN_DIR.'/views/front-view-albums.php';
	$gallerybank_output = ob_get_clean();
	wp_reset_query();
	return $gallerybank_output;	
}
add_action('admin_init','plugin_js_scripts_gallery_bank');
add_action('admin_init','plugin_css_scripts_gallery_bank');
add_action('init','frontend_plugin_js_scripts_gallery_bank');
add_action('init','frontend_plugin_css_scripts_gallery_bank');
add_action('admin_menu','create_global_menus_for_gallery_bank');
add_shortcode('gallery_bank', 'gallery_bank_short_code' );
add_shortcode('gallery_bank_album_cover', 'gallery_bank_short_code_album');
?>