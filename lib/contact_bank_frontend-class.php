<?php
global $wpdb;
if(isset($_REQUEST["param"]))
{
	if($_REQUEST["param"] == "frontend_submit_controls")
	{
		$form_id = intval($_REQUEST["form_id"]);
		$fields = $wpdb->get_results
		(
			$wpdb->prepare
			(
				"SELECT field_id,column_dynamicId,control_id FROM " .create_control_Table()."  WHERE form_id = %d",
				$form_id
			)
		);
		$wpdb->query
		(
			$wpdb->prepare
			(
				"INSERT INTO " . contact_bank_frontend_forms_Table(). " (form_id) VALUES(%d)",
				$form_id
			)
		);
		echo $form_submit_id = $wpdb->insert_id;
		$wpdb->query
		(
			$wpdb->prepare
			(
				"UPDATE " . contact_bank_frontend_forms_Table(). " SET submit_id = %d WHERE id = %d",
				$form_submit_id,
				$form_submit_id
			)
		);
		for($flag = 0;$flag<count($fields);$flag++)
		{
			$field_id = $fields[$flag]->field_id;
			$dynamicId = $fields[$flag]->column_dynamicId;
			$control_dynamicId = $fields[$flag]->control_id;
			switch($field_id)
			{
				case 1:
					$ux_txt = esc_attr($_REQUEST["ux_txt_control_".$dynamicId]);
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$ux_txt,
							$form_submit_id
						)
					);
				break;
				case 2:
					$ux_textarea = esc_attr($_REQUEST["ux_textarea_control_".$dynamicId]);
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$ux_textarea,
							$form_submit_id
						)
					);
				break;
				case 3:
					$ux_email = esc_attr($_REQUEST["ux_txt_email_".$dynamicId]);
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$ux_email,
							$form_submit_id
						)
					);
				break;
				case 4:
					$ux_dropdown = esc_attr($_REQUEST["ux_select_default_".$dynamicId]);
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$ux_dropdown,
							$form_submit_id
						)
					);
				break;
				case 5:
					$ux_checkbox = $_REQUEST[$dynamicId."_chk"];
					$checkbox_options = "";
					for($flag1 =0;$flag1<count($ux_checkbox);$flag1++)
					{
						$checkbox_options .= $ux_checkbox[$flag1];
						if($flag1 < count($ux_checkbox)-1)
						{
							$checkbox_options .= "-";
						}
					}
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$checkbox_options,
							$form_submit_id
						)
					);
				break;
				case 6:
					$ux_multiple = esc_attr($_REQUEST[$dynamicId."_rdl"]);
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO " . frontend_controls_data_Table(). " (form_id,field_id,dynamic_control_id,dynamic_frontend_value,form_submit_id) VALUES(%d,%d,%d,%s,%d)",
							$form_id,
							$field_id,
							$control_dynamicId,
							$ux_multiple,
							$form_submit_id
						)
					);
				break;
			}
		}
		die();
	}
}
?>
