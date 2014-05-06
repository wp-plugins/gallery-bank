<?php
/**
Plugin Name: Contact Bank Standard Edition
Plugin URI: http://tech-banker.com
Description: Build Complex, Powerful Contact Forms in Just Seconds. No Programming Knowledge Required! Yeah, It's Really That Easy.
Author: Tech Banker
Version: 2.0.23
Author URI: http://tech-banker.com
 */
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//   D e f i n e     CONSTANTS //////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
if (!defined("CONTACT_DEBUG_MODE"))    define("CONTACT_DEBUG_MODE",  false );
if (!defined("CONTACT_BK_FILE"))       define("CONTACT_BK_FILE",  __FILE__ );
if (!defined("CONTACT_CONTENT_DIR"))      define("CONTACT_CONTENT_DIR", ABSPATH . "wp-content");
if (!defined("CONTACT_CONTENT_URL"))      define("CONTACT_CONTENT_URL", site_url() . "/wp-content");
if (!defined("CONTACT_PLUGIN_DIR"))       define("CONTACT_PLUGIN_DIR", CONTACT_CONTENT_DIR . "/plugins");
if (!defined("CONTACT_PLUGIN_URL"))       define("CONTACT_PLUGIN_URL", CONTACT_CONTENT_URL . "/plugins");
if (!defined("CONTACT_BK_PLUGIN_FILENAME"))  define("CONTACT_BK_PLUGIN_FILENAME",  basename( __FILE__ ) );
if (!defined("CONTACT_BK_PLUGIN_DIRNAME"))   define("CONTACT_BK_PLUGIN_DIRNAME",  plugin_basename(dirname(__FILE__)) );
if (!defined("CONTACT_BK_PLUGIN_DIR")) define("CONTACT_BK_PLUGIN_DIR", CONTACT_PLUGIN_DIR."/".CONTACT_BK_PLUGIN_DIRNAME );
if (!defined("CONTACT_BK_PLUGIN_URL")) define("CONTACT_BK_PLUGIN_URL", site_url()."/wp-content/plugins/".CONTACT_BK_PLUGIN_DIRNAME );
if (!defined("contact_bank")) define("contact_bank", "contact_bank");

function plugin_uninstall_script_for_contact_bank()
{
   // include_once CONTACT_BK_PLUGIN_DIR ."/lib/uninstall-script.php";
}
/* Function Name : plugin_install_script_for_contact_bank
 * Paramters : None
 * Return : None
 * Description : This Function check the version number of the plugin database and performs necessary actions related to the plugin database upgrade.
 * Created in Version 1.0
 * Last Modified : 1.0
 * Reasons for change : None
 */
function plugin_install_script_for_contact_bank()
{
    if(file_exists(CONTACT_BK_PLUGIN_DIR ."/lib/install-script.php"))
    {
        include_once CONTACT_BK_PLUGIN_DIR ."/lib/install-script.php";
    }
}
/* Function Name : create_global_menus_for_contact_bank
 * Paramters : None
 * Return : None
 * Description : This Function creates menus in the admin menu sidebar and related mention function in each menu are being called.
 * Created in Version 1.0
 * Last Modified : 1.0
 * Reasons for change : None
 */
function create_global_menus_for_contact_bank()
{
	add_menu_page("Contact Bank", __("Contact Bank", contact_bank), "read", "dashboard","",CONTACT_BK_PLUGIN_URL . "/assets/images/icon.png");
    add_submenu_page("dashboard", "Dashboard", __("Dashboard", contact_bank), "read", "dashboard","dashboard");
    add_submenu_page("","","", "read", "contact_bank","contact_bank");
	add_submenu_page("dashboard", "Short-Codes", __("Short-Codes", contact_bank), "read", "short_code", "short_code" );
    add_submenu_page("dashboard", "Form Entries", __("Form Entries", contact_bank), "read", "frontend_data","frontend_data");
    add_submenu_page("dashboard", "Email Settings", __("Email Settings", contact_bank), "read", "contact_email", "contact_email");
    add_submenu_page("dashboard", "Global Settings", __("Global Settings", contact_bank), "read", "layout_settings", "layout_settings");
	add_submenu_page("dashboard", "System Status", __("System Status", contact_bank), "read", "system_status", "system_status" );
    //add_submenu_page("dashboard", "Documentation", __("Documentation", contact_bank), "read", "documentation", "documentation" );
    add_submenu_page("dashboard", "Purchase PRO Edition", __("Purchase PRO Edition", contact_bank), "read", "pro_version", "pro_version" );
    add_submenu_page("","","", "read", "add_contact_email_settings", "add_contact_email_settings" );
	add_submenu_page("","","", "read", "form_preview", "form_preview" );
}
/* Function Name : contact_bank
 * Paramters : None
 * Return : None
 * Description : This Function used to linked menu page is requested.
 * Created in Version 1.0
 * Last Modified : 1.0
 * Reasons for change : None
 */
function contact_bank()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_view.php";
    include_once CONTACT_BK_PLUGIN_DIR . "/views/includes_common_after.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function dashboard()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/dashboard.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function edit_contact_view()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_view.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function contact_email()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_email_settings.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function frontend_data()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_frontend_data.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
// function documentation()
// {
    // include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    // include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_documentation.php";
    // include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
// }
function add_contact_email_settings()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/add_contact_email.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function layout_settings()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_bank_layout_settings.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}

function system_status()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact-bank-system-report.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}

function form_preview()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_bank_form_preview.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function pro_version()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/contact_bank_pro_version.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}
function short_code()
{
    include_once CONTACT_BK_PLUGIN_DIR ."/views/header.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/shortcode.php";
    include_once CONTACT_BK_PLUGIN_DIR ."/views/footer.php";
}

function backend_plugin_js_scripts_contact_bank()
{
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery-ui-sortable");
    wp_enqueue_script("jquery-ui-droppable");
    wp_enqueue_script("jquery-ui-draggable");
    wp_enqueue_script("farbtastic");
	wp_enqueue_script("jquery-ui-dialog");
    wp_enqueue_script("jquery.Tooltip.js", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.Tooltip.js");
    wp_enqueue_script("jquery.dataTables.min", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.dataTables.min.js");
    wp_enqueue_script("jquery.validate.min", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.validate.min.js");
    wp_enqueue_script("bootstrap.js", CONTACT_BK_PLUGIN_URL ."/assets/js/bootstrap.js");
    wp_enqueue_script("jquery.prettyPhoto.js", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.prettyPhoto.js");
}
function frontend_plugin_js_scripts_contact_bank()
{
    wp_enqueue_script("jquery");
    wp_enqueue_script("jquery.Tooltip.js", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.Tooltip.js");
    wp_enqueue_script("jquery.validate.min", CONTACT_BK_PLUGIN_URL ."/assets/js/jquery.validate.min.js");
}
function backend_plugin_css_styles_contact_bank()
{
    wp_enqueue_style("farbtastic");
    wp_enqueue_style("wp-jquery-ui-dialog");
    wp_enqueue_style("stylesheet", CONTACT_BK_PLUGIN_URL ."/assets/css/stylesheet.css");
    wp_enqueue_style("font-awesome", CONTACT_BK_PLUGIN_URL ."/assets/css/font-awesome/css/font-awesome.css");
    wp_enqueue_style("system-message", CONTACT_BK_PLUGIN_URL ."/assets/css/system-message.css");
	wp_enqueue_style("css3_grid_style", CONTACT_BK_PLUGIN_URL ."/assets/css/css3_grid_style.css");
	wp_enqueue_style("prettyPhoto", CONTACT_BK_PLUGIN_URL ."/assets/css/prettyPhoto.css");
}
function frontend_plugin_css_styles_contact_bank()
{
    wp_enqueue_style("stylesheet", CONTACT_BK_PLUGIN_URL ."/assets/css/stylesheet.css");
    wp_enqueue_style("system-message", CONTACT_BK_PLUGIN_URL ."/assets/css/system-message.css");
}
if(isset($_REQUEST["action"]))
{
    switch($_REQUEST["action"])
    {
        case "add_contact_form_library":
            add_action( "admin_init", "add_contact_form_library");
            function add_contact_form_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_view-class.php";
            }
            break;
        case "frontend_contact_form_library":
            add_action( "admin_init", "frontend_contact_form_library");
            function frontend_contact_form_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_bank_frontend-class.php";
            }
            break;
        case "email_contact_form_library":
            add_action( "admin_init", "email_contact_form_library");
            function email_contact_form_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_bank_email-class.php";
            }
            break;
        case "email_management_contact_form_library":
            add_action( "admin_init", "email_management_contact_form_library");
            function email_management_contact_form_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_bank_email_management.php";
            }
            break;
        case "frontend_data_contact_library":
            add_action( "admin_init", "frontend_data_contact_library");
            function frontend_data_contact_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_frontend_data_class.php";
            }
            break;
        
		case "show_form_control_data_contact_library":
            add_action( "admin_init", "show_form_control_data_contact_library");
            function show_form_control_data_contact_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_bank_show_form_control_data-class.php";
            }
            break;
            case "layout_settings_contact_library":
            add_action( "admin_init", "layout_settings_contact_library");
            function layout_settings_contact_library()
            {
                include_once CONTACT_BK_PLUGIN_DIR . "/lib/contact_bank_layout_settings-class.php";
            }
            break;
    }
}
/*
 * Description : THESE FUNCTIONS USED FOR REPLACING TABLE NAMES
 * Created in Version 1.0
 * Last Modified : 1.0
 * Reasons for change : None
 */
function contact_bank_contact_form()
{
    global $wpdb;
    return $wpdb->prefix . "cb_contact_form";
}
function contact_bank_dynamic_settings_form()
{
    global $wpdb;
    return $wpdb->prefix . "cb_dynamic_settings";
}
function create_control_Table()
{
    global $wpdb;
    return $wpdb->prefix . "cb_create_control_form";
}
function frontend_controls_data_Table()
{
    global $wpdb;
    return $wpdb->prefix . "cb_frontend_data_table";
}
function contact_bank_email_template_admin()
{
    global $wpdb;
    return $wpdb->prefix . "cb_email_template_admin";
}
function contact_bank_frontend_forms_Table()
{
    global $wpdb;
    return $wpdb->prefix . "cb_frontend_forms_table";
}
function contact_bank_form_settings_Table()
{
    global $wpdb;
    return $wpdb->prefix . "cb_form_settings_table";
}
function contact_bank_layout_settings_Table()
{
    global $wpdb;
    return $wpdb->prefix . "cb_layout_settings_table";
}
function contact_bank_licensing()
{
    global $wpdb;
    return $wpdb->prefix . "cb_licensing";
}
function contact_bank_roles_capability()
{
    global $wpdb;
    return $wpdb->prefix . "cb_roles_capability";
}
function contact_bank_short_code($atts)
{
    extract(shortcode_atts(array(
        "form_id" => "",
        "show_title" => "",
    ), $atts));
    return extract_short_code($form_id,$show_title);
}
function extract_short_code($form_id,$show_title)
{
    ob_start();
    require CONTACT_BK_PLUGIN_DIR."/frontend_views/contact_bank_forms.php";
    $contact_bank_output = ob_get_clean();
    wp_reset_query();
    return $contact_bank_output;
}
function add_contact_bank_icon($meta = TRUE)
{
    global $wp_admin_bar;
    if ( !is_user_logged_in() ) { return; }
	$wp_admin_bar->add_menu( array(
	    "id" => "contact_bank_links",
	    "title" =>  "<img src=\"".CONTACT_BK_PLUGIN_URL."/assets/images/icon.png\" width=\"25\" height=\"25\" style=\"vertical-align:text-top; margin-right:5px;\" />Contact Bank" ,
	    "href" => site_url() ."/wp-admin/admin.php?page=dashboard",
	));

	$wp_admin_bar->add_menu( array(
	    "parent" => "contact_bank_links",
	    "id"     => "dashboard_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=dashboard",
	    "title" => __( "Dashboard", contact_bank) )         /* set the sub-menu name */
    );
	$wp_admin_bar->add_menu( array(
        "parent" => "contact_bank_links",
	    "id"     => "short_code_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=short_code",
	    "title" => __( "Short-Codes", contact_bank))         /* set the sub-menu name */
	);
   $wp_admin_bar->add_menu( array(
        "parent" => "contact_bank_links",
	    "id"     => "frontend_data_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=frontend_data",
	    "title" => __( "Form Entries", contact_bank))         /* set the sub-menu name */
	);
	$wp_admin_bar->add_menu( array(
	    "parent" => "contact_bank_links",
	    "id"     => "email_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=contact_email",
	    "title" => __( "Email Settings", contact_bank) )         /* set the sub-menu name */
	);
	$wp_admin_bar->add_menu( array(
	    "parent" => "contact_bank_links",
	    "id"     => "form_settings_data_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=layout_settings",
	    "title" => __( "Global Settings", contact_bank))         /* set the sub-menu name */
	);	
 	
   
    $wp_admin_bar->add_menu( array(
        "parent" => "contact_bank_links",
	    "id"     => "system_status_data_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=system_status",
	    "title" => __( "System Status", contact_bank))         /* set the sub-menu name */
	);
	// $wp_admin_bar->add_menu( array(
	    // "parent" => "contact_bank_links",
	    // "id"     => "documents_data_links",
	    // "href"  => site_url() ."/wp-admin/admin.php?page=documentation",
	    // "title" => __( "Documentation", contact_bank))         /* set the sub-menu name */
	// );
	$wp_admin_bar->add_menu( array(
        "parent" => "contact_bank_links",
	    "id"     => "pro_version_data_links",
	    "href"  => site_url() ."/wp-admin/admin.php?page=pro_version",
	    "title" => __("Purchase PRO Edition", contact_bank))         /* set the sub-menu name */
	);
	
}
add_action( "media_buttons_context", "add_contact_shortcode_button", 1);
function add_contact_shortcode_button($context) {
    add_thickbox();
    $context .= "<a href=\"#TB_inline?width=300&height=400&inlineId=contact-bank\"  class=\"button thickbox\"  title=\"" . __("Add Contact Bank Form", contact_bank) . "\">
    <span class=\"contact_icon\"></span> Add Contact Bank Form</a>";
    return $context;
}
add_action("admin_footer","add_contact_mce_popup");

function add_contact_mce_popup(){
	?>
	<?php add_thickbox(); ?>
	<div id="contact-bank" style="display:none;">
		<div class="fluid-layout responsive">
			<div style="padding:20px 0 10px 15px;">
			    <h3 class="label-shortcode"><?php _e("Insert Contact Bank Form", contact_bank); ?></h3>
					<span>
						<i><?php _e("Select a form below to add it to your post or page.", contact_bank); ?></i>
					</span>
			</div>
			<div class="layout-span12 responsive" style="padding:15px 15px 0 0;">
				<div class="layout-control-group">
					<label class="custom-layout-label" for="ux_form_name"><?php _e("Form Name", contact_bank); ?> : </label>
					<select id="add_contact_form_id" class="layout-span9">
						<option value="0"><?php _e("Select a Form", contact_bank); ?>  </option>
						<?php
						global $wpdb;
						$forms = $wpdb->get_results
						(
							"SELECT * FROM " .contact_bank_contact_form()
						);
						for($flag = 0;$flag<count($forms);$flag++)
						{
							?>
							<option value="<?php echo intval($forms[$flag]->form_id); ?>"><?php echo esc_html($forms[$flag]->form_name) ?></option>
						<?php
						}
						?>
					</select>
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label"><?php _e("Show Form Title", contact_bank); ?> : </label>
					<input type="checkbox" checked="checked" name="ux_form_title" id="ux_form_title"/>
				</div>
				<div class="layout-control-group">
					<label class="custom-layout-label"></label>
					<input type="button" class="button-primary" value="<?php _e("Insert Form", contact_bank); ?>"
						onclick="Insert_Contact_Form();"/>&nbsp;&nbsp;&nbsp;
					<a class="button" style="color:#bbb;" href="#"
						onclick="tb_remove(); return false;"><?php _e("Cancel", contact_bank); ?></a>
				</div>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		function Insert_Contact_Form()
		{
			var form_id = jQuery("#add_contact_form_id").val();
			var show_title = jQuery("#ux_form_title").prop("checked");
			if(form_id == 0)
			{
			    alert("<?php _e("Please choose a Form to insert into Shortcode", contact_bank) ?>");
			    return;
			}
			window.send_to_editor("[contact_bank form_id=" + form_id + " show_title=" + show_title +" ]");
		}
	</script>
<?php
}

function contact_bank_banner()
{
	echo'<div id="ux_buy_pro" class="updated">
     <div class="gb_buy_pro">
      <div class="gb_text_control">
       It\'s time to upgrade your <strong>Contact Bank Standard Edition</strong> to <strong>Premium</strong> Edition!<br />
       <span>Extend standard plugin functionality with 200+ awesome features! <br/>Go for Premium Version Now! Starting at <strong>10£/- only</strong></span>
      </div>
      <a class="button gb_message_buttons" href="admin.php?page=pro_version&msg=no">CLOSE</a>
      <a class="button gb_message_buttons" target="_blank" href="http://wordpress.org/support/view/plugin-reviews/contact-bank?filter=5">RATE US 5 ★</a>
      <a class="button gb_message_buttons" target="_blank" href="http://tech-banker.com/contact-bank/demo/">LIVE DEMO</a>
      <a class="button gb_message_buttons" target="_blank" href="http://tech-banker.com/contact-bank/">UPGRADE NOW</a>
     </div>
    </div>';
}
$show_banner = get_option("contact-bank-banner");
if($show_banner == "")
{
 add_action("admin_notices", "contact_bank_banner",1);
}


function contact_bank_enqueue_pointer_script_style()
{

    $enqueue_pointer_script_style = false;
    $dismissed_pointers = explode( ",", get_user_meta( get_current_user_id(), "dismissed_wp_pointers", true ) );

    // Check if our pointer is not among dismissed ones
    if( !in_array( "thsp_contact_bank_pointer", $dismissed_pointers))
    {
        $enqueue_pointer_script_style = true;
        // Add footer scripts using callback function
        add_action( "admin_print_footer_scripts", "thsp_pointer_print_scripts" );
    }
    if( $enqueue_pointer_script_style )
    {
        wp_enqueue_style( "wp-pointer" );
        wp_enqueue_script( "wp-pointer" );
    }
}
add_action( "admin_enqueue_scripts", "contact_bank_enqueue_pointer_script_style" );

function thsp_pointer_print_scripts() {

    $pointer_content  = "<h3>Contact Bank</h3>";
    $pointer_content .= "<p>If you are using Contact Bank for the first time, you can view this <a href='http://tech-banker.com/contact-bank/' target='_blank'>link</a> to know about the features.</p>";
    ?>
    <script type="text/javascript">
        jQuery(document).ready( function($) {
            $("#toplevel_page_dashboard").pointer({
                content:"<?php echo $pointer_content; ?>",
                position:{
                    edge:   "left", // arrow direction
                    align:  "center" // vertical alignment
                },
                pointerWidth: 350,
                close:function() {
                    $.post(ajaxurl, {
                        pointer: "thsp_contact_bank_pointer", // pointer ID
                        action: "dismiss-wp-pointer"
                    });
                }
            }).pointer("open");
        });
    </script>
<?php
}
function plugin_load_textdomain()
{
    if(function_exists( "load_plugin_textdomain" ))
    {
        load_plugin_textdomain(contact_bank, false, CONTACT_BK_PLUGIN_DIRNAME ."/languages");
    }
}
add_action("plugins_loaded", "plugin_load_textdomain");
$version = get_option("contact-bank-version-number");
if($version != "")
{
	add_action('admin_init', 'plugin_install_script_for_contact_bank');
}

/*************************************************************************************/
add_action("admin_bar_menu", "add_contact_bank_icon",100);
// add_action Hook called for function frontend_plugin_css_scripts_contact_bank
add_action("init","frontend_plugin_css_styles_contact_bank");
// add_action Hook called for function backend_plugin_css_scripts_contact_bank
add_action("admin_init","backend_plugin_css_styles_contact_bank");
// add_action Hook called for function frontend_plugin_js_scripts_contact_bank
add_action("init","frontend_plugin_js_scripts_contact_bank");
// add_action Hook called for function backend_plugin_js_scripts_contact_bank
add_action("admin_init","backend_plugin_js_scripts_contact_bank");
// add_action Hook called for function create_global_menus_for_contact_bank
add_action("admin_menu","create_global_menus_for_contact_bank");
// Activation Hook called for function plugin_install_script_for_contact_bank
register_activation_hook(__FILE__,"plugin_install_script_for_contact_bank");
// add_Shortcode Hook called for function contact_bank_short_code for FrontEnd
add_shortcode("contact_bank", "contact_bank_short_code");
// Uninstall Hook called for function plugin_install_script_for_contact_bank
register_uninstall_hook(__FILE__,"plugin_uninstall_script_for_contact_bank");

?>