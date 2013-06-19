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
			$ux_album_name = html_entity_decode($_REQUEST["album_name"]);
			$ux_desciption = html_entity_decode(esc_attr($_REQUEST["ux_description"]));
			$ux_image_width = 160;
			$ux_image_height = 120;
			$ux_border_width = 5;
			$ux_border_color = "#000";
			$ux_image_no = 3;
			$ux_slide_interval = 2;
			$ux_slide = 0;
			$ux_border = 1;
			$images_in_row = 3;
			$wpdb->query
			(
				$wpdb->prepare
				(
					"INSERT INTO ".gallery_bank_albums()."(album_name,description,image_width,image_height,border_enable,
					border_width,border_color,slideshow,slideshow_interval,album_date,author,images_in_row)
					VALUES(%s,%s,%d,%d,%d,%d,%s,%d,%d,CURDATE(),%s,%d)",
					$ux_album_name,
					$ux_desciption,
					$ux_image_width,
					$ux_image_height,
					$ux_border,
					$ux_border_width,
					$ux_border_color,
					$ux_slide,
					$ux_slide_interval,
					$current_user->display_name,
					$images_in_row
				)
			);
			echo $EventLastId=$wpdb->insert_id;
			
			die();
		}
		elseif($_REQUEST["param"] == "update_album")
		{
			$albumId = intval($_REQUEST["albumId"]);
			echo $ux_edit_album_name = html_entity_decode($_REQUEST["edit_album_name"]);
			$ux_edit_desciption = html_entity_decode(esc_attr($_REQUEST["ux_edit_description"]));
			$ux_edit_image_width = 160;
			$ux_edit_image_height = 120;
			$ux_edit_border_width = 5;
			$ux_edit_border_color = "#000";
			$ux_edit_image_no = 3;
			$ux_edit_slide_interval = 2;
			$ux_edit_slide = 0;
			$ux_edit_image_border = 1;
			
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " .gallery_bank_albums(). " SET album_name = %s, description = %s, image_width = %d, image_height = %d,
					border_enable = %d, border_width = %d, border_color = %s, slideshow = %d, slideshow_interval = %d WHERE album_id = %d",
					$ux_edit_album_name,
					$ux_edit_desciption,
					$ux_edit_image_width,
					$ux_edit_image_height,
					$ux_edit_image_border,
					$ux_edit_border_width,
					$ux_edit_border_color,
					$ux_edit_slide,
					$ux_edit_slide_interval,
					$albumId
				)
			);
			die();
		}
		else if($_REQUEST['param'] == "Delete_album")
		{
			$album_id = intval($_REQUEST['album_id']);
			$pics = $wpdb->get_var
			(
				$wpdb->prepare
				(
					'SELECT count(pic_id) FROM ' . gallery_bank_pics() . ' where album_id =%d', 
					$album_id
				)
			);
			if($pics != 0)
			{
				echo "Pictures Exist";
			}
			else
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_albums()." WHERE album_id = %d",
						$album_id
					)
				);
			}
			die();
		}
		else if($_REQUEST["param"] == "add_pic")
		{
			$ux_path = esc_attr($_REQUEST["path"]);
			$ux_albumid = intval($_REQUEST["album_id"]);
			$ux_title = html_entity_decode($_REQUEST["title"]);
			$ux_detail = html_entity_decode($_REQUEST["detail"]);
			$thumbnail_url = esc_attr($_REQUEST["thumb"]);
			$alb_cover = intval($_REQUEST["alb_cover"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"INSERT INTO ".gallery_bank_pics()."(album_id,pic_path,thumbnail_url,title,description,date,album_cover)
					VALUES(%d,%s,%s,%s,%s,CURDATE(),%d)",
					$ux_albumid,
					$ux_path,
					$thumbnail_url,
					$ux_title,
					$ux_detail,
					$alb_cover
					
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
			$edit_cover = esc_attr($_REQUEST["edit_cover"]);
			$wpdb->query
			(
				$wpdb->prepare
				(
					"UPDATE " .gallery_bank_pics(). " SET title = %s, description= %s, album_cover = %d WHERE pic_id = %d",
					$edit_title,
					$edit_detail,
					$edit_cover,
					$pic_id
				)
			);
			
			die();
		}
		else if($_REQUEST['param'] == "delete_pic")
		{
			$picture_id = $_REQUEST['id'];
			$count_pic = intval($_REQUEST['count_pic']);
			$Pic_id =  (explode(",",$picture_id));
			for($flag = 0; $flag < $count_pic; $flag++ )
			{
				$wpdb->query
				(
					$wpdb->prepare
					(
						"DELETE FROM ".gallery_bank_pics()." WHERE pic_id = %d",
						$Pic_id[$flag]
					)
				);
			}
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
	}
}
?>