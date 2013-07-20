<?php
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING MENUS
//---------------------------------------------------------------------------------------------------------------//
function create_global_menus_for_gallery_bank()
{
	//add_cap("editor", "manage_options", true);  
	add_menu_page('Gallery Bank', __('Gallery Bank', gallery_bank), 'read', 'gallery_bank','',GALLERY_BK_PLUGIN_URL . '/icon.png');
	add_submenu_page('', 'Dashboard', __('Dashboard', gallery_bank), 'read', 'gallery_bank', 'gallery_bank');
	add_submenu_page('gallery_bank', 'Gallery Bank', __('Global Settings', gallery_bank), 'read', 'settings', 'settings');
	add_submenu_page('', '','' , 'read', 'add_album', 'add_album');
	add_submenu_page('', '','' , 'read', 'view_album', 'view_album');
	add_submenu_page('', '','' , 'read', 'images_sorting', 'images_sorting');
	add_submenu_page('', '','' , 'read', 'album_preview', 'album_preview');
	add_submenu_page('', '','' , 'read', 'edit_album', 'edit_album');
	add_submenu_page('', '','' , 'read', 'pro_version', 'pro_version');
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
function gallery_bank_settings()
{
	global $wpdb;
	return $wpdb->prefix . 'gallery_settings';
}
//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CREATING PAGES
//---------------------------------------------------------------------------------------------------------------//
function gallery_bank()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/dashboard.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
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
		include_once GALLERY_BK_PLUGIN_DIR .'/views/add-new-album.php';
		include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
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
function edit_album()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/edit-album.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
}
function settings()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/settings.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
}
function images_sorting()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/images_sorting.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
}

function album_preview()
{
	global $wpdb;
	include_once GALLERY_BK_PLUGIN_DIR .'/views/header.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/album_preview.php';
	include_once GALLERY_BK_PLUGIN_DIR .'/views/footer.php';
}
//--------------------------------------------------------------------------------------------------------------//
//CODE FOR CALLING JAVASCRIPT FUNCTIONS
//--------------------------------------------------------------------------------------------------------------//
function backend_scripts_calls()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('bootstrap.min.js', GALLERY_BK_PLUGIN_URL .'/assets/js/plugins/bootstrap/bootstrap.min.js');
	wp_enqueue_script('bootstrap-bootbox.min.js', GALLERY_BK_PLUGIN_URL .'/assets/js/plugins/bootstrap/bootstrap-bootbox.min.js');
	wp_enqueue_script('jquery.dataTables.min.js', GALLERY_BK_PLUGIN_URL .'/assets/js/plugins/tables/jquery.dataTables.min.js');
	wp_enqueue_script('mColorPicker_small.js', GALLERY_BK_PLUGIN_URL .'/assets/js/colorpicker/js/mColorPicker_small.js');
	wp_enqueue_script('jquery.validate.min.js', GALLERY_BK_PLUGIN_URL .'/assets/js/plugins/forms/jquery.validate.min.js');
	wp_enqueue_script('jquery.titanlighbox.js', GALLERY_BK_PLUGIN_URL .'/assets/js/jquery.titanlighbox.js');
	
}
function frontend_plugin_js_scripts_gallery_bank()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery.titanlighbox.js', GALLERY_BK_PLUGIN_URL .'/assets/js/jquery.titanlighbox.js');
	wp_enqueue_script('jquery.dataTables.min.js', GALLERY_BK_PLUGIN_URL .'/assets/js/plugins/tables/jquery.dataTables.min.js');
	
}

//--------------------------------------------------------------------------------------------------------------//
// CODE FOR CALLING STYLE SHEETS
function backend_css_calls()
{
	wp_enqueue_style('main', GALLERY_BK_PLUGIN_URL . '/assets/css/main.css');
	wp_enqueue_style('system-message', GALLERY_BK_PLUGIN_URL . '/assets/css/system-message.css');
	wp_enqueue_style('jquery.titanlighbox.css', GALLERY_BK_PLUGIN_URL .'/assets/css/jquery.titanlighbox.css');
}
function frontend_plugin_css_scripts_gallery_bank()
{
	wp_enqueue_style('jquery.titanlighbox.css', GALLERY_BK_PLUGIN_URL .'/assets/css/jquery.titanlighbox.css');
	wp_enqueue_style('main', GALLERY_BK_PLUGIN_URL . '/assets/css/main.css');

	
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
		break;
		case "album_gallery_library":
		add_action( 'admin_init', 'album_gallery_library');
		function album_gallery_library()
		{
			global $wpdb;
			include_once GALLERY_BK_PLUGIN_DIR . '/lib/album-gallery-bank-class.php';
		}
		break;
		case "front_albums_gallery_library":
		add_action( 'admin_init', 'front_albums_gallery_library');
		function front_albums_gallery_library()
		{
			global $wpdb;
			include_once GALLERY_BK_PLUGIN_DIR . '/lib/front-view-album-class.php';
		}
		break;
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
function gallery_bank_short_code_all_albums($atts) 
{
	extract(shortcode_atts(array(
	"album_id" => '',
	), $atts));
	$con = '';
	foreach((array)$album_id as $f)
	{
		$con .=  $f;
	}
	return extract_short_code_all_albums($con);
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
function extract_short_code_all_albums($con) 
{
	$album_id = $con;
	ob_start();
	require GALLERY_BK_PLUGIN_DIR.'/views/front-view-all-albums.php';
	$gallerybank_output = ob_get_clean();
	wp_reset_query();
	return $gallerybank_output;	
}
add_action('admin_init','backend_scripts_calls');
add_action('admin_init','backend_css_calls');
add_action('init','frontend_plugin_js_scripts_gallery_bank');
add_action('init','frontend_plugin_css_scripts_gallery_bank');
add_action('admin_menu','create_global_menus_for_gallery_bank');
add_shortcode('gallery_bank', 'gallery_bank_short_code' );
add_shortcode('gallery_bank_album_cover', 'gallery_bank_short_code_album');
add_shortcode('gallery_bank_all_albums', 'gallery_bank_short_code_all_albums');
remove_filter( 'the_content', 'wpautop' );
add_filter('widget_text', 'do_shortcode');
class GalleryAllAlbumsWidget extends WP_Widget
{
	function GalleryAllAlbumsWidget()
	{
		$widget_ops = array('classname' => 'GalleryAllAlbumsWidget', 'description' => 'Displays Gallery Images' );
		$this->WP_Widget('GalleryAllAlbumsWidget', 'Gallery Bank', $widget_ops);
	}
	function form($instance)
	{
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'galleryid' => '0' ) );
		$title = $instance['title'];
		global $wpdb;
		$albums = $wpdb->get_results
		(
			$wpdb->prepare
			(
				"SELECT * FROM ". gallery_bank_albums(),""
			)
		);
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<p><label for="<?php echo $this->get_field_id('galleryid'); ?>"><?php _e('Select Gallery:', gallery_bank); ?></label>
			<select size="1" name="<?php echo $this->get_field_name('galleryid'); ?>" id="<?php echo $this->get_field_id('galleryid'); ?>" class="widefat">
				<option value="0"><?php _e('Select Album', gallery_bank); ?></option>
			<?php
			if($albums) {
				foreach($albums as $album) {
				echo '<option value="'.$album->album_id.'" ';
				if ($album->album_id == $instance['galleryid']) echo "selected='selected' ";
				echo '>'.$album->album_name.'</option>'."\n\t"; 
				}
			}
			?>
			</select>
		</p>
		<?php
	}
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = $new_instance['title'];
		$instance['galleryid'] = (int) $new_instance['galleryid'];
		return $instance;
	}
	function widget($args, $instance)
	{
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
		if (!empty($title))
		
		if($instance['galleryid'] != 0)
		{
			echo $before_title . $title . $after_title;
			$shortcode_for_albums = "[gallery_bank album_id=" . $instance['galleryid'] . "]";
			echo do_shortcode( $shortcode_for_albums );
			echo $after_widget;
		}
		
		
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("GalleryAllAlbumsWidget");') );
?>