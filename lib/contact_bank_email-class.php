<?php
	global $wpdb,$current_user,$cb_user_role_permission;
	$cb_role = $wpdb->prefix . "capabilities";
	$current_user->role = array_keys($current_user->$cb_role);
	$cb_role = $current_user->role[0];
	switch($cb_role)
	{
		case "administrator":
			$cb_user_role_permission = "manage_options";
		break;
		case "editor":
			$cb_user_role_permission = "publish_pages";
		break;
		case "author":
			$cb_user_role_permission = "publish_posts";
		break;
		case "contributor":
			$cb_user_role_permission = "edit_posts";
		break;
		case "subscriber":
			$cb_user_role_permission = "read";
		break;
	}
if (!current_user_can($cb_user_role_permission))
{
	return;
}
else
{
	if(isset($_REQUEST["param"]))
	{
		if($_REQUEST["param"] == "email_settings")
		{
			$form_id = intval($_REQUEST["form_id"]);
			$email_settings = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT email_id,subject,name FROM ".contact_bank_email_template_admin()." WHERE form_id = %d",
					$form_id
				)
			);
			for($flag=0;$flag<count($email_settings);$flag++)
			{
				?>
				<tr>
				<?php
					?>
					<td ><?php echo $email_settings[$flag]->name;?></td>
					<td ><?php echo $email_settings[$flag]->subject;?></td>
					<td>
						<a href="admin.php?page=add_contact_email_settings&email_id=<?php echo $email_settings[$flag]->email_id;?>&form_id=<?php echo $form_id ?>" class="btn hovertip" data-original-title="<?php _e("Edit Email Settings",contact_bank)?>">
							<i class="icon-pencil"></i>
						</a>
						<a herf="#" onclick="delete_email_settings(<?php echo $email_settings[$flag]->email_id;?>)" class="btn hovertip" data-original-title="<?php _e("Delete Email Settings",contact_bank)?>">
							<i class="icon-trash"></i>
						</a>
					</td>
				</tr>
				<?php
			}
			?>
			<script type="text/javascript">
				oTable = jQuery("#data-table-email-settings").dataTable
				({
					"bJQueryUI": false,
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",
					"sDom": "<\"datatable-header\"fl>t<\"datatable-footer\"ip>",
					"oLanguage": 
					{
						"sLengthMenu": "<span>Show entries:</span> _MENU_"
					},
					"aaSorting": [[ 0, "asc" ]]
				});
			</script>
			<?php
			die();
		}
		else if($_REQUEST["param"] == "insert_email_controls")
		{
			$form_id = isset($_REQUEST["form_id"]) ? intval($_REQUEST["form_id"]) : "";
			$email_id = isset($_REQUEST["email_id"]) ? intval($_REQUEST["email_id"]) : "";
			if($email_id == "")
			{
				$email_name = esc_attr($_REQUEST["ux_txt_name"]);
				$send_to = intval($_REQUEST["ux_rdl_send_to"]);
				if($send_to == 0)
				{
					$email_address = esc_attr($_REQUEST["ux_txt_email"]);
				}
				else
				{
					$email_address = esc_attr($_REQUEST["ux_txt_send_to_field"]);
				}
				$email_from_name = esc_attr($_REQUEST["ux_txt_from_name"]);
				$email_from_email = esc_attr($_REQUEST["ux_txt_from_email"]);
				$email_reply_to = esc_attr($_REQUEST["ux_txt_reply_to"]);
				$email_cc = esc_attr($_REQUEST["ux_txt_cc"]);
				$email_bcc  = esc_attr($_REQUEST["ux_txt_bcc"]);
				$email_subject  = esc_attr($_REQUEST["ux_txt_subject"]);
				$uxDescription_email = html_entity_decode($_REQUEST["uxEmailTemplate"]);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"INSERT INTO " . contact_bank_email_template_admin(). " (email_to,email_from,body_content,subject,form_id,from_name,reply_to,cc,bcc,name,send_to) VALUES(%s,%s,%s,%s,%d,%s,%s,%s,%s,%s,%d)",
						$email_address,
						$email_from_email,
						$uxDescription_email,
						$email_subject,
						$form_id,
						$email_from_name,
						$email_reply_to,
						$email_cc,
						$email_bcc,
						$email_name,
						$send_to
					)
				);
			}
			else 
			{
				$email_id = intval($_REQUEST["email_id"]);
				$email_name = esc_attr($_REQUEST["ux_txt_name"]);
				$send_to = intval($_REQUEST["ux_rdl_send_to"]);
				if($send_to == 0)
				{
					$email_address = esc_attr($_REQUEST["ux_txt_email"]);
				}
				else
				{
					$email_address = esc_attr($_REQUEST["ux_txt_send_to_field"]);
				}
				$email_from_name = esc_attr($_REQUEST["ux_txt_from_name"]);
				$email_from_email = esc_attr($_REQUEST["ux_txt_from_email"]);
				$email_reply_to = esc_attr($_REQUEST["ux_txt_reply_to"]);
				$email_cc = esc_attr($_REQUEST["ux_txt_cc"]);
				$email_bcc  = esc_attr($_REQUEST["ux_txt_bcc"]);
				$email_subject  = esc_attr($_REQUEST["ux_txt_subject"]);
				$uxDescription_email = html_entity_decode($_REQUEST["uxEmailTemplate"]);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE " . contact_bank_email_template_admin(). " SET email_to = %s,email_from = %s,body_content = %s,subject = %s,form_id = %d,from_name = %s,reply_to = %s,cc = %s,bcc = %s,name = %s,send_to = %d WHERE email_id = %d",
						$email_address,
						$email_from_email,
						$uxDescription_email,
						$email_subject,
						$form_id,
						$email_from_name,
						$email_reply_to,
						$email_cc,
						$email_bcc,
						$email_name,
						$send_to,
						$email_id
					)
				);
			}
			die();
		}
		else if($_REQUEST["param"] == "update_email_controls")
		{
			$form_id = intval($_REQUEST["form_id"]);
			$email_id = intval($_REQUEST["email_id"]);
			$email_name = esc_attr($_REQUEST["ux_txt_name"]);
			$send_to = intval($_REQUEST["ux_rdl_send_to"]);
			if($send_to == 0)
			{
				$email_address = esc_attr($_REQUEST["ux_txt_email"]);
			}
			else
			{
				$email_address = esc_attr($_REQUEST["ux_txt_send_to_field"]);
			}
			$email_from_name = esc_attr($_REQUEST["ux_txt_from_name"]);
			$email_from_email = esc_attr($_REQUEST["ux_txt_from_email"]);
			$email_reply_to = esc_attr($_REQUEST["ux_txt_reply_to"]);
			$email_cc = esc_attr($_REQUEST["ux_txt_cc"]);
			$email_bcc  = esc_attr($_REQUEST["ux_txt_bcc"]);
			$email_subject  = esc_attr($_REQUEST["ux_txt_subject"]);
			$uxDescription_email = html_entity_decode($_REQUEST["uxEmailTemplate"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " . contact_bank_email_template_admin(). " SET email_to = %s,email_from = %s,body_content = %s,subject = %s,form_id = %d,from_name = %s,reply_to = %s,cc = %s,bcc = %s,name = %s,send_to = %d WHERE email_id = %d",
					$email_address,
					$email_from_email,
					$uxDescription_email,
					$email_subject,
					$form_id,
					$email_from_name,
					$email_reply_to,
					$email_cc,
					$email_bcc,
					$email_name,
					$send_to,
					$email_id
				)
			);
			die();
		}
		else if($_REQUEST["param"] == "delete_email_settings")
		{
			$email_id = intval($_REQUEST["email_id"]);
			$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM " .contact_bank_email_template_admin()." WHERE email_id = %d ",
						$email_id
					)
				);
			die();
		}
	}
}
?>