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
	if(isset($_REQUEST["param"]))
	{
		if($_REQUEST["param"] == "add_new_album")
		{
			$ux_album_name = html_entity_decode(esc_attr($_REQUEST["album_name"]));
			$ux_desciption = html_entity_decode(esc_attr($_REQUEST["ux_description"]));
			$cover = esc_attr($_REQUEST["cover_array"]);
			$album_count = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT count(album_id) FROM ".gallery_bank_albums(),""
				)
			);
			if($album_count < 2)
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"INSERT INTO ".gallery_bank_albums()."(album_name, description, album_date, author)
						VALUES(%s, %s, CURDATE(), %s)",
						$ux_album_name,
						$ux_desciption,
						$current_user->display_name
					)
				);
				echo $EventLastId=$wpdb->insert_id;
				$setting = "image_size:1;width:160px;height:120px;images_in_row:3;image_opacity:1;image_border_size:2px;image_border_radius:2px;image_border_color:rgb(0, 0, 0);/cover_size:1;width:160px;height:120px;cover_opacity:1;cover_border_size:2px;cover_border_radius:2px;border_color:rgb(0, 0, 0);/overlay_opacity:0.6;overlay_border_size:0px;overlay_border_radius:0px;text_color:rgb(0, 0, 0);overlay_border_color:rgb(255, 255, 255);inline_bg_color:rgb(255, 255, 255);overlay_bg_color:rgb(0, 0, 0);/autoplay:0;slide_interval:2;/pagination:0;";
				if($cover == "undefined")
				{
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO ".gallery_bank_settings()."(album_id, album_settings, setting_content)
							VALUES(%d, %d, %s)",
							$EventLastId,
							1,
							$setting
						)
					);
				}
				else
				{
					$wpdb->query
					(
						$wpdb->prepare
						(
							"INSERT INTO ".gallery_bank_settings()."(album_id, album_settings, setting_content,album_cover)
							VALUES(%d, %d, %s, %s)",
							$EventLastId,
							1,
							$setting,
							$cover
						)
					);
				}
			}
			die();
		}
		elseif($_REQUEST["param"] == "update_album")
		{
			$albumId = intval($_REQUEST["albumId"]);
			$ux_edit_album_name = html_entity_decode(esc_attr($_REQUEST["edit_album_name"]));
			$ux_edit_desciption = html_entity_decode(esc_attr($_REQUEST["ux_edit_description"]));
			$edit_cover = esc_attr($_REQUEST["cover_array"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " .gallery_bank_albums(). " SET album_name = %s, description = %s WHERE album_id = %d",
					$ux_edit_album_name,
					$ux_edit_desciption,
					$albumId
				)
			);
			$new_settings = "image_size:1;width:160px;height:120px;images_in_row:3;image_opacity:1;image_border_size:2px;image_border_radius:2px;image_border_color:rgb(0, 0, 0);/cover_size:1;width:160px;height:120px;cover_opacity:1;cover_border_size:2px;cover_border_radius:2px;border_color:rgb(0, 0, 0);/overlay_opacity:0.6;overlay_border_size:0px;overlay_border_radius:0px;text_color:rgb(0, 0, 0);overlay_border_color:rgb(255, 255, 255);inline_bg_color:rgb(255, 255, 255);overlay_bg_color:rgb(0, 0, 0);/autoplay:0;slide_interval:2;/pagination:0;";
			if($edit_cover == "undefined")
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE ".gallery_bank_settings()." SET setting_content = %s, album_settings = %d WHERE album_id = %d",
						$new_settings,
						1,
						$albumId
					)
				);
			}
			else
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE ".gallery_bank_settings()." SET setting_content = %s, album_settings = %d, album_cover = %s WHERE album_id = %d",
						$new_settings,
						1,
						$edit_cover,
						$albumId
					)
				);
			}
			die();
		}
		else if($_REQUEST['param'] == "Delete_album")
		{
			$album_id = intval($_REQUEST['album_id']);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_pics()." WHERE album_id = %d",
						$album_id
					)
				);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_albums()." WHERE album_id = %d",
						$album_id
					)
				);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_settings()." WHERE album_id = %d",
						$album_id
					)
				);
			
			die();
		}
		else if($_REQUEST["param"] == "add_pic")
		{
			$ux_path = esc_attr($_REQUEST["path"]);
			$ux_albumid = intval($_REQUEST["album_id"]);
			$ux_title = html_entity_decode($_REQUEST["title"]);
			$ux_detail = html_entity_decode($_REQUEST["detail"]);
			$thumbnail_url = esc_attr($_REQUEST["thumb"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"INSERT INTO ".gallery_bank_pics()."(album_id,pic_path,thumbnail_url,title,description,date)
					VALUES(%d,%s,%s,%s,%s,CURDATE())",
					$ux_albumid,
					$ux_path,
					$thumbnail_url,
					$ux_title,
					$ux_detail
				)
			);
			$pic_id = $wpdb->insert_id;
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " .gallery_bank_pics(). " SET sorting_order = %d WHERE pic_id = %d",
					$pic_id,
					$pic_id
				)
			);
			die();
		}
		else if($_REQUEST["param"] == "update_pic")
		{
			$pic_id = intval($_REQUEST['picId']);
			$albumId = intval($_REQUEST['albumId']);
			$edit_title = html_entity_decode($_REQUEST['edit_title']);
			$edit_detail = html_entity_decode($_REQUEST['edit_detail']);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " .gallery_bank_pics(). " SET title = %s, description= %s WHERE pic_id = %d",
					$edit_title,
					$edit_detail,
					$pic_id
				)
			);
			
			die();
		}
		else if($_REQUEST['param'] == "delete_pic")
		{
			$picture_id = $_REQUEST['id'];
			$count_pic = intval($_REQUEST['count_pic']);
			$pic_id =  (explode(",",$picture_id));
			for($flag = 0; $flag < $count_pic; $flag++ )
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_pics()." WHERE pic_id = %d",
						$pic_id[$flag]
					)
				);
			}
			die();
		}
		else if($_REQUEST['param'] == "delete_album")
		{
			$album_id = intval($_REQUEST['album_id']);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"DELETE FROM ".gallery_bank_pics()." WHERE album_id = %d",
					$album_id
				)
			);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"DELETE FROM ".gallery_bank_settings()." WHERE album_id = %d",
					$album_id
				)
			);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"DELETE FROM ".gallery_bank_albums()." WHERE album_id = %d",
					$album_id
				)
			);
			die();
		}
		
		else if($_REQUEST["param"] == "reorderControls")
		{
			
			$updateRecordsArray = $_POST['recordsArray'];
			$listingCounter = 1;
			foreach ($updateRecordsArray as $recordIDValue)
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"UPDATE ".gallery_bank_pics()." SET sorting_order = %d WHERE pic_id = %d",
						$listingCounter,
						$recordIDValue
					)
				);
				$listingCounter = $listingCounter + 1;	
			}
			die();
		}
		
		else if($_REQUEST['param'] == "delete_all_albums")
		{
			$album = $wpdb->get_results
			(
				$wpdb->prepare
				(
					"SELECT * FROM ".gallery_bank_albums(),""
				)
			);
			for($flag=0; $flag < count($album); $flag++)
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_pics()." WHERE album_id = %d",
						$album[$flag]->album_id
					)
				);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_settings()." WHERE album_id = %d",
						$album[$flag]->album_id
					)
				);
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_albums()." WHERE album_id = %d",
						$album[$flag]->album_id
					)
				);
			}
			die();
		}
	}
}
?>