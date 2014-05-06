<?php
global $wpdb;
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
update_option("contact-bank-updation-check-url","http://tech-banker.com/wp-admin/admin-ajax.php");
$version = get_option("contact-bank-version-number");
if($version == "" || $version == "1.0")
{
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_form_settings_Table() . '"')) == 0)
    {
        create_contact_bank_form_settings();
    }
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_contact_form() . '"')) == 0)
    {
        create_table_contact_bank_forms();
    }
    else
    {
        $contact_forms = $wpdb->get_results
        (
           "SELECT * FROM ".contact_bank_contact_form()
        );
        $sql = "DROP TABLE " . contact_bank_contact_form();
        $wpdb->query($sql);

        create_table_contact_bank_forms();

        if(count($contact_forms) > 0)
        {
            for($flag = 0; $flag < count($contact_forms); $flag++)
            {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . contact_bank_contact_form() . "(form_id, form_name) VALUES(%d, %s)",
                        $contact_forms[$flag]->form_id,
                        $contact_forms[$flag]->form_name
                    )
                );
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                        $contact_forms[$flag]->form_id,
                        "redirect",
                        $contact_forms[$flag]->chk_url
                    )
                );
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                        $contact_forms[$flag]->form_id,
                        "redirect_url",
                        $contact_forms[$flag]->redirect_url
                    )
                );
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                        $contact_forms[$flag]->form_id,
                        "success_message",
                        $contact_forms[$flag]->success_message
                    )
                );
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                        $contact_forms[$flag]->form_id,
                        "blank_field_message",
                        "Required field must not be blank"
                    )
                );
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                         $contact_forms[$flag]->form_id,
                        "incorrect_email_message",
                        "Please enter a valid email address"
                    )
                );
				$wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
                         $contact_forms[$flag]->form_id,
                        "form_description",
                        ""
                    )
                );
            }
        }
	}
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . create_control_Table() . '"')) == 0)
    {
        create_table_contact_bank_controls();
    }
    else
    {
        $contact_forms_controls = $wpdb->get_results
        (
           
                "SELECT * FROM ".create_control_Table()." where field_id not in (9,12,13,14,15)"
           
        );

        $sql = "DROP TABLE " . create_control_Table();
        $wpdb->query($sql);

        create_table_contact_bank_controls();

        if(count($contact_forms_controls) > 0)
        {
            for($flag = 0; $flag < count($contact_forms_controls); $flag++)
            {
                $wpdb->query
                    (
                        $wpdb->prepare
                            (
                                "INSERT INTO " . create_control_Table() . "(field_id,form_id,
                        		column_dynamicId, sorting_order) VALUES(%d, %d, %d, %d)",
                                $contact_forms_controls[$flag]->field_id,
                                $contact_forms_controls[$flag]->form_id,
                                $contact_forms_controls[$flag]->column_dynamicId,
                                $contact_forms_controls[$flag]->sorting_order
                            )
                    );
            }
        }

    }
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_dynamic_settings_form() . '"')) == 0)
    {
        create_table_contact_bank_dynamic_settings();
    }
    else
    {
        $contact_forms_dynamic_settings = $wpdb->get_results
        (
           "SELECT * FROM ". contact_bank_dynamic_settings_form(). " JOIN " . create_control_Table(). " ON " . contact_bank_dynamic_settings_form().".dynamicId  = ". create_control_Table(). ".column_dynamicId"
        );
        $contact_forms_email_dynamic_settings = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "SELECT control_id,column_dynamicId FROM ".create_control_Table()." where field_id = 3 ",""
            )
        );

        $sql = "DROP TABLE " . contact_bank_dynamic_settings_form();
        $wpdb->query($sql);

        create_table_contact_bank_dynamic_settings();
        
        $column_dynamicId = Array();
        for($flag = 0; $flag < count($contact_forms_email_dynamic_settings);$flag++)
        {
            array_push($column_dynamicId,$contact_forms_email_dynamic_settings[$flag]->column_dynamicId);
            $wpdb->query
            (
                $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_dynamic_settings_form() ."(dynamicId,dynamic_settings_key,
                            dynamic_settings_value)VALUES(%d, %s, %s)",
                        $contact_forms_email_dynamic_settings[$flag]->control_id,
                        "cb_default_txt_val",
                        ""
                    )
            );
        }

        if(count($contact_forms_dynamic_settings) > 0)
        {
            $settings_keys = array();
            array_push($settings_keys, "");
            array_push($settings_keys, "cb_button_set_outer_label");
            array_push($settings_keys, "cb_button_set_description");
            array_push($settings_keys, "cb_button_set_options_outer_wrapper");
            array_push($settings_keys, "cb_button_set_options_wrapper");
            array_push($settings_keys, "cb_button_set_options_label");
            array_push($settings_keys, "cb_button_set_txt_input");
            array_push($settings_keys, "cb_date_day_dropdown");
            array_push($settings_keys, "cb_date_month_dropdown");
            array_push($settings_keys, "cb_date_year_dropdown");
            array_push($settings_keys, "cb_button_set_dropdown_menu");
            array_push($settings_keys, "cb_button_set_txt_description");
            array_push($settings_keys, "cb_uploaded_file_email_db");
            array_push($settings_keys, "cb_button_set_outer_label_file");
            array_push($settings_keys, "cb_button_set_outer_description_fileuplod");
            array_push($settings_keys, "cb_button_set_time_hour_dropdown");
            array_push($settings_keys, "cb_button_set_time_minute_dropdown");
            array_push($settings_keys, "cb_button_set_time_am_pm_dropdown");
            array_push($settings_keys, "cb_error_invalid");

            $settings_keys_email = array();

            array_push($settings_keys_email, "");
            array_push($settings_keys_email, "cb_checkbox_alpha_filter");
            array_push($settings_keys_email, "cb_ux_checkbox_alpha_num_filter");
            array_push($settings_keys_email, "cb_checkbox_digit_filter");
            array_push($settings_keys_email, "cb_checkbox_strip_tag_filter");
            array_push($settings_keys_email, "cb_checkbox_trim_filter");
            
			$settings_keys_multiple_options = array();
			
			array_push($settings_keys_multiple_options, "");
            array_push($settings_keys_multiple_options, "cb_dropdown_option_id");
            array_push($settings_keys_multiple_options, "cb_checkbox_option_id");
            array_push($settings_keys_multiple_options, "cb_radio_option_id");
            array_push($settings_keys_multiple_options, "cb_dropdown_option_val");
            array_push($settings_keys_multiple_options, "cb_checkbox_option_val");
			array_push($settings_keys_multiple_options, "cb_radio_option_val");
			
			
			
            for($flag = 0; $flag < count($contact_forms_dynamic_settings); $flag++)
            {
                $position_keys = array_search($contact_forms_dynamic_settings[$flag]->dynamic_settings_key,$settings_keys);
                $position_email_keys = array_search($contact_forms_dynamic_settings[$flag]->dynamic_settings_key,$settings_keys_email);
                if($position_keys == false && !($position_email_keys != false && in_array($contact_forms_dynamic_settings[$flag]->dynamicId,$column_dynamicId)))
                {
                    $wpdb->query
                    (
                        $wpdb->prepare
                        (
                            "INSERT INTO " . contact_bank_dynamic_settings_form() . "(dynamicId,
                            dynamic_settings_key,dynamic_settings_value) VALUES(%d, %s, %s)",
                            $contact_forms_dynamic_settings[$flag]->control_id,
                            $contact_forms_dynamic_settings[$flag]->dynamic_settings_key,
                            array_search($contact_forms_dynamic_settings[$flag]->dynamic_settings_key,$settings_keys_multiple_options) 
                            ? serialize(explode(";",$contact_forms_dynamic_settings[$flag]->dynamic_settings_value)) : $contact_forms_dynamic_settings[$flag]->dynamic_settings_value
                        )
                    );
                }
            }
        }
    }

    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_email_template_admin() . '"')) == 0)
    {
        create_table_contact_bank_email_templates();
    }
    else
    {
        $contact_forms_emails = $wpdb->get_results
        (
          "SELECT * FROM ".contact_bank_email_template_admin()
        );

        $sql = "DROP TABLE " . contact_bank_email_template_admin();
        $wpdb->query($sql);

        create_table_contact_bank_email_templates();

        if(count($contact_forms_emails) > 0)
        {
            for($flag = 0; $flag < count($contact_forms_emails); $flag++)
            {
                $wpdb->query
                    (
                        $wpdb->prepare
                            (
                                "INSERT INTO " . contact_bank_email_template_admin() . "(email_id, email_to, email_from,
		                        body_content, subject, send_to, form_id, from_name, reply_to, cc, bcc, name) VALUES(%d, %s,
		                        %s, %s, %s, %d, %d, %s, %s, %s, %s, %s)",
                                $contact_forms_emails[$flag]->email_id,
                                $contact_forms_emails[$flag]->email_to,
                                $contact_forms_emails[$flag]->email_from,
                                $contact_forms_emails[$flag]->body_content,
                                $contact_forms_emails[$flag]->subject,
                                isset($contact_forms_emails[$flag]->send_to) ?  $contact_forms_emails[$flag]->send_to : "",
                                $contact_forms_emails[$flag]->form_id,
                                isset($contact_forms_emails[$flag]->from_name) ? $contact_forms_emails[$flag]->from_name :  "",
                                isset($contact_forms_emails[$flag]->reply_to) ? $contact_forms_emails[$flag]->reply_to : "",
                                isset($contact_forms_emails[$flag]->cc) ? $contact_forms_emails[$flag]->cc : "",
                                isset($contact_forms_emails[$flag]->bcc) ? $contact_forms_emails[$flag]->bcc : "",
                                isset($contact_forms_emails[$flag]->name) ? $contact_forms_emails[$flag]->name  : ""
                            )
                    );
            }
        }

    }
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_frontend_forms_Table() . '"')) == 0)
    {
        create_table_contact_bank_front_end_forms();
    }
    else
    {
        $contact_front_end_forms = $wpdb->get_results
        (
           "SELECT * FROM ".contact_bank_frontend_forms_Table()
        );

        $sql = "DROP TABLE " . contact_bank_frontend_forms_Table();
        $wpdb->query($sql);

        create_table_contact_bank_front_end_forms();

        for($flag = 0; $flag < count($contact_front_end_forms);$flag++)
        {
            $wpdb->query
            (
                $wpdb->prepare
                    (
                        "INSERT INTO ". contact_bank_frontend_forms_Table() ."(form_id,submit_id)VALUES(%d, %d)",
                        $contact_front_end_forms[$flag]->form_id,
                        $contact_front_end_forms[$flag]->submit_id
                    )
            );
        }

    }
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . frontend_controls_data_Table() . '"')) == 0)
    {
        create_table_front_end_data();
    }
    else
    {
        $contact_front_end_forms_data = $wpdb->get_results
        (
           "SELECT * FROM ".frontend_controls_data_Table() . " JOIN " . create_control_Table(). " ON " . frontend_controls_data_Table().".dynamic_control_id  = ". create_control_Table(). ".column_dynamicId"
        );

        $sql = "DROP TABLE " . frontend_controls_data_Table();
        $wpdb->query($sql);

        create_table_front_end_data();

        for($flag = 0; $flag < count($contact_front_end_forms_data);$flag++)
        {
            $wpdb->query
            (
                $wpdb->prepare
                (
                    "INSERT INTO ". frontend_controls_data_Table() ."(form_id,field_Id,dynamic_control_id,
                    dynamic_frontend_value,form_submit_id)VALUES(%d, %d, %d, %s, %d)",
                    $contact_front_end_forms_data[$flag]->form_id,
                    $contact_front_end_forms_data[$flag]->field_Id,
                    $contact_front_end_forms_data[$flag]->control_id,
                    $contact_front_end_forms_data[$flag]->dynamic_frontend_value,
                    $contact_front_end_forms_data[$flag]->form_submit_id
                )
            );
        }
    }
    if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_layout_settings_Table() . '"')) == 0)
    {
        create_contact_bank_layout_settings();
        $settings = array();
        $settings["label_setting_font_family"] = "inherit";
        $settings["label_setting_font_color"] = "#000000";
        $settings["label_setting_font_style"] =  "normal";
        $settings["label_setting_font_size"] = "16";
        $settings["label_setting_font_align_left"] =  "0";
        $settings["label_setting_label_position"] =  "top";
        $settings["label_setting_field_size"] = "11";
        $settings["label_setting_field_align"] = "left";
        $settings["label_setting_hide_label"] = "0";
        $settings["label_setting_text_direction"] = "inherit";

        $settings["input_field_font_family"] = "inherit";
        $settings["input_field_font_color"] = "#000000";
        $settings["input_field_font_style"] = "normal";
        $settings["input_field_font_size"] = "14";
        $settings["input_field_border_radius"] = "0";
        $settings["input_field_border_color"] = "#e5e5e5";
        $settings["input_field_border_size"] = "1";
        $settings["input_field_border_style"] = "solid";
        $settings["input_field_clr_bg_color"] = "#ffffff";
        $settings["input_field_rdl_multiple_row"] = "1";
        $settings["input_field_rdl_text_align"] = "0";
        $settings["input_field_text_direction"] = "inherit";
        $settings["input_field_input_size"] = "layout-span6";

        $settings["submit_button_font_family"] = "inherit";
        $settings["submit_button_text"] = "Save ";
        $settings["submit_button_font_style"] = "normal";
        $settings["submit_button_font_size"] = "12";
        $settings["submit_button_button_width"] = "100";
        $settings["submit_button_bg_color"] =  "#24890d";
        $settings["submit_button_hover_bg_color"] = "#3dd41a";
        $settings["submit_button_text_color"] =  "#ffffff";
        $settings["submit_button_border_color"] = "#000000";
        $settings["submit_button_border_size"] = "0";
        $settings["submit_button_border_radius"] = "0";
        $settings["submit_button_rdl_text_align"] = "0";
        $settings["submit_button_text_direction"] = "inherit";

        $settings["success_msg_font_family"] = "inherit";
        $settings["success_msg_font_size"] = "12";
        $settings["success_msg_bg_color"] = "#e5ffd5";
        $settings["success_msg_border_color"] =  "#e5ffd5";
        $settings["success_msg_text_color"] =  "#6aa500";
        $settings["success_msg_rdl_text_align"] = "0";
        $settings["success_msg_text_direction"] = "inherit";

        $settings["error_msg_font_family"] =  "inherit";
        $settings["error_msg_font_size"] = "12";
        $settings["error_msg_bg_color"] = "#ffcaca";
        $settings["error_msg_border_color"] = "#ffcaca";
        $settings["error_msg_text_color"] = "#ff2c38";
        $settings["error_msg_rdl_text_align"] = "0";
        $settings["error_msg_text_direction"] = "inherit";

        $contact_forms_for_settings = $wpdb->get_results
        (
          "SELECT * FROM ".contact_bank_contact_form()
        );
		 for($flag = 0; $flag < count($contact_forms_for_settings); $flag++)
		 {
             $sql = "";
		        foreach($settings as $key => $value)
		        {
		            $sql[] = '('.$contact_forms_for_settings[$flag]->form_id.',"'.mysql_real_escape_string($key).'", "'.mysql_real_escape_string($value).'")';
		        }
		        $wpdb->query
		        (
		            $wpdb->prepare
                    (
                        "INSERT INTO " . contact_bank_layout_settings_Table() . "(form_id,form_settings_key,form_settings_value) VALUES ".implode(',', $sql),""
                    )
		        );
		 }
    }
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_licensing() . "'")) == 0)
    {
        create_cb_table_licensing();
    }
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_roles_capability() . "'")) == 0)
    {
        create_table_roles_capability();
		$settings_roles = array();
		$settings_roles["admin_full_control"] =  "1";
		$settings_roles["admin_read_control"] =  "0";
		$settings_roles["admin_write_control"] = "0";
		$settings_roles["editor_full_control"] = "0";
		$settings_roles["editor_read_control"] = "1";
		$settings_roles["editor_write_control"] = "0";
		$settings_roles["author_full_control"] = "0";
		$settings_roles["author_read_control"] = "1";
		$settings_roles["author_write_control"] = "0";
		$settings_roles["contributor_full_control"] = "0";
		$settings_roles["contributor_read_control"] = "1";
		$settings_roles["contributor_write_control"] = "0";
		$settings_roles["subscriber_full_control"] = "0";
		$settings_roles["subscriber_read_control"] = "1";
		$settings_roles["subscriber_write_control"] = "0";
		foreach($settings_roles as $key => $value)
        {
                $sql1[] = '("'.mysql_real_escape_string($key).'", "'.mysql_real_escape_string($value).'")';
        }
        $wpdb->query
        (
            "INSERT INTO " . contact_bank_roles_capability() . "(roles_capability_key,roles_capability_value) VALUES ".implode(',', $sql1),""
        );
    }
	update_option("contact-bank-version-number","2.1");
	 
}
else if($version == "2.0")
{
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_licensing() . "'")) == 0)
    {
        create_cb_table_licensing();
    }
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_roles_capability() . "'")) == 0)
    {
        create_table_roles_capability();
		$settings_roles = array();
		$settings_roles["admin_full_control"] =  "1";
		$settings_roles["admin_read_control"] =  "0";
		$settings_roles["admin_write_control"] = "0";
		$settings_roles["editor_full_control"] = "0";
		$settings_roles["editor_read_control"] = "1";
		$settings_roles["editor_write_control"] = "0";
		$settings_roles["author_full_control"] = "0";
		$settings_roles["author_read_control"] = "1";
		$settings_roles["author_write_control"] = "0";
		$settings_roles["contributor_full_control"] = "0";
		$settings_roles["contributor_read_control"] = "1";
		$settings_roles["contributor_write_control"] = "0";
		$settings_roles["subscriber_full_control"] = "0";
		$settings_roles["subscriber_read_control"] = "1";
		$settings_roles["subscriber_write_control"] = "0";
		foreach($settings_roles as $key => $value)
        {
                $sql1[] = '("'.mysql_real_escape_string($key).'", "'.mysql_real_escape_string($value).'")';
        }
        $wpdb->query
        (
            "INSERT INTO " . contact_bank_roles_capability() . "(roles_capability_key,roles_capability_value) VALUES ".implode(',', $sql1),""
        );
    }
	if (count($wpdb->get_var('SHOW TABLES LIKE "' . contact_bank_form_settings_Table() . '"')) == 0)
    {
        create_contact_bank_form_settings();
    }
	else 
	{
		$contact_forms_settings_table = $wpdb->get_results
        (
           "SELECT * FROM ".contact_bank_form_settings_Table()
        );
		
		$sql = "DROP TABLE " . contact_bank_form_settings_Table();
        $wpdb->query($sql);
		
		
		$contact_forms_count = $wpdb->get_results
        (
             "SELECT * FROM ".contact_bank_contact_form()
        );
        

        create_contact_bank_form_settings();
		
		if(count($contact_forms_settings_table) > 0)
        {
            for($flag = 0; $flag < count($contact_forms_settings_table); $flag++)
            {
            	
			   $wpdb->query
	           (
	                $wpdb->prepare
	                (
	                    "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
	                    $contact_forms_settings_table[$flag]->form_id,
	                    $contact_forms_settings_table[$flag]->form_message_key,
	                    $contact_forms_settings_table[$flag]->form_message_value
	                )
           		);
			}
			for($flag = 0; $flag < count($contact_forms_count); $flag++)
			{
				$wpdb->query
	            (
	                $wpdb->prepare
	                (
	                    "INSERT INTO ". contact_bank_form_settings_Table() ."(form_id,form_message_key,form_message_value)VALUES(%d, %s, %s)",
	                    $contact_forms_count[$flag]->form_id,
	                    "form_description",
	                    ""
	                )
	       		);
			}
		}
	}
    update_option("contact-bank-version-number","2.1");
}
else if($version == "2.1")
{
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_licensing() . "'")) == 0)
    {
        create_cb_table_licensing();
    }
	if (count($wpdb->get_var("SHOW TABLES LIKE '" . contact_bank_roles_capability() . "'")) == 0)
    {
        create_table_roles_capability();
		$settings_roles = array();
		$settings_roles["admin_full_control"] =  "1";
		$settings_roles["admin_read_control"] =  "0";
		$settings_roles["admin_write_control"] = "0";
		$settings_roles["editor_full_control"] = "0";
		$settings_roles["editor_read_control"] = "1";
		$settings_roles["editor_write_control"] = "0";
		$settings_roles["author_full_control"] = "0";
		$settings_roles["author_read_control"] = "1";
		$settings_roles["author_write_control"] = "0";
		$settings_roles["contributor_full_control"] = "0";
		$settings_roles["contributor_read_control"] = "1";
		$settings_roles["contributor_write_control"] = "0";
		$settings_roles["subscriber_full_control"] = "0";
		$settings_roles["subscriber_read_control"] = "1";
		$settings_roles["subscriber_write_control"] = "0";
		foreach($settings_roles as $key => $value)
        {
                $sql1[] = '("'.mysql_real_escape_string($key).'", "'.mysql_real_escape_string($value).'")';
        }
        $wpdb->query
        (
            "INSERT INTO " . contact_bank_roles_capability() . "(roles_capability_key,roles_capability_value) VALUES ".implode(',', $sql1),""
        );
    }
	 update_option("contact-bank-version-number","2.1");
}
function create_table_contact_bank_forms()
{
    $sql = 'CREATE TABLE ' . contact_bank_contact_form() . '(
	form_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	form_name VARCHAR(200) NOT NULL,
	PRIMARY KEY (form_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);

}
function create_table_contact_bank_dynamic_settings()
{
    $sql = 'CREATE TABLE ' . contact_bank_dynamic_settings_form() . '(
	dynamic_settings_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	dynamicId INTEGER(10) NOT NULL,
	dynamic_settings_key VARCHAR(100) NOT NULL,
	dynamic_settings_value TEXT NOT NULL,
	PRIMARY KEY (dynamic_settings_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_table_contact_bank_controls()
{
    $sql = 'CREATE TABLE '.create_control_Table(). '(
	control_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	field_id INTEGER(50) NOT NULL,
	form_id INTEGER(10) NOT NULL,
	column_dynamicId INTEGER(10) NOT NULL,
	sorting_order INTEGER(10) NOT NULL,
	PRIMARY KEY(control_id)
	)ENGINE = MyISAM DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}

function create_table_front_end_data()
{
    $sql = 'CREATE TABLE ' . frontend_controls_data_Table() . '(
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	form_id INTEGER(10) NOT NULL,
	field_Id INTEGER(10) NOT NULL,
	dynamic_control_id INTEGER(10) NOT NULL,
	dynamic_frontend_value TEXT NOT NULL,
	form_submit_id INTEGER(10) NOT NULL,
	PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_table_contact_bank_email_templates()
{
    $sql = 'CREATE TABLE ' . contact_bank_email_template_admin() . '(
	email_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	email_to VARCHAR(100) NOT NULL,
	email_from VARCHAR(100) NOT NULL,
	body_content TEXT NOT NULL,
	subject VARCHAR(400) NOT NULL,
	send_to  INTEGER(1) NOT NULL,
	form_id INTEGER(10) NOT NULL,
	from_name  VARCHAR(200) NOT NULL,
	reply_to  VARCHAR(200) NOT NULL,
	cc  VARCHAR(200) NOT NULL,
	bcc  VARCHAR(200) NOT NULL,
	name VARCHAR(100) NOT NULL,
	PRIMARY KEY (email_id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_table_contact_bank_front_end_forms()
{
    $sql = 'CREATE TABLE ' . contact_bank_frontend_forms_Table() . '(
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	form_id INTEGER(10) NOT NULL,
	submit_id INTEGER(10) NOT NULL,
	PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_contact_bank_layout_settings()
{
    $sql = 'CREATE TABLE ' . contact_bank_layout_settings_Table() . '(
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	form_id INTEGER(10) NOT NULL,
	form_settings_key VARCHAR(200) NOT NULL,
	form_settings_value VARCHAR(200) NOT NULL,
	PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_contact_bank_form_settings()
{
    $sql = 'CREATE TABLE ' . contact_bank_form_settings_Table() . '(
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	form_id INTEGER(10) NOT NULL,
	form_message_key VARCHAR(200) NOT NULL,
	form_message_value TEXT NOT NULL,
	PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
function create_cb_table_licensing()
{
    global $wpdb;
    $sql = "CREATE TABLE " . contact_bank_licensing() . "(
        licensing_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
        version VARCHAR(10) NOT NULL,
        type VARCHAR(100) NOT NULL,
        url TEXT NOT NULL,
        api_key TEXT NOT NULL,
        order_id VARCHAR(100) NOT NULL,
        PRIMARY KEY (licensing_id)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
    dbDelta($sql);

    $wpdb->query
    (
        $wpdb->prepare
            (
                "INSERT INTO " . contact_bank_licensing() . "(version, type, url) VALUES(%s, %s, %s)",
                "2.1.0",
                "Contact Bank Eco Edition",
                "" . site_url() . ""
            )
    );
}
function create_table_roles_capability()
{
    $sql = 'CREATE TABLE ' . contact_bank_roles_capability() . '(
	id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
	roles_capability_key VARCHAR(200) NOT NULL,
	roles_capability_value VARCHAR(200) NOT NULL,
	PRIMARY KEY (id)
	) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci';
    dbDelta($sql);
}
?>