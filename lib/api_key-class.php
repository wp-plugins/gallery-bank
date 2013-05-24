<?php
global $wpdb;
if (!current_user_can("edit_posts") && ! current_user_can("edit_pages") )
{
	return;
}
else
{
	if(isset($_REQUEST["param"]))
	{
		if($_REQUEST["param"] == "updateApi")
		{
			
			$uxApiKey = esc_attr($_REQUEST["uxApiKey"]);
			$wpdb->query
			(
						$wpdb->prepare
						(
							"UPDATE ".settingTable()." set SettingsValue = %s where SettingsKey = %s",
							$uxApiKey,
							"events_handler_api"
							
						)
			);
					
			die();
		}
	}
}
?>