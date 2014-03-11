<?php
global $wpdb,$current_user,$user_role_permission;
$dynamicArray = array();
$dynamicId = mt_rand(10, 10000);
$role = $wpdb->prefix . "capabilities";
$current_user->role = array_keys($current_user->$role);
$role = $current_user->role[0];
	switch($role)
	{
		case "administrator":
			$user_role_permission = "manage_options";
		break;
		case "editor":
			$user_role_permission = "publish_pages";
		break;
		case "author":
			$user_role_permission = "publish_posts";
		break;
		case "contributor":
			$user_role_permission = "edit_posts";
		break;
		case "subscriber":
			$user_role_permission = "read";
		break;
	}

if (!current_user_can($user_role_permission))
{
	return;
}
else
{
	if (isset($_REQUEST["param"])) {
	    if ($_REQUEST["param"] == "add_new_dynamic_row_for_image") {
	        $img_path = esc_attr($_REQUEST["img_path"]);
	        $img_name = esc_attr($_REQUEST["img_name"]);
	        $img_width = intval($_REQUEST["image_width"]);
	        $img_height = intval($_REQUEST["image_height"]);
	
	
	        process_image_upload($img_path, $img_width, $img_height);
	
	        $column1 = "<input type=\"checkbox\" id=\"ux_grp_select_items_" . $dynamicId . "\" name=\"ux_grp_select_items_" . $dynamicId . "\" value=\"" . $dynamicId . "\" />";
	        array_push($dynamicArray, $column1);
	
	        $column2 = "<a  href=\"javascript:void(0);\" title=\"" . $img_name . "\" >
					<img type=\"image\" imgPath=\"" . $img_path . "\"  src=\"" . GALLERY_BK_THUMB_SMALL_URL . $img_path . "\" id=\"ux_gb_img\" name=\"ux_gb_img\" class=\"img dynamic_css\" imageid=\"" . $dynamicId . "\" width=\"" . $img_width . "\"/></a><br/>
					<label><strong>" . $img_name . "</strong></label><br/><label>" . date("F j, Y") . "</label><br/>
					<input type=\"radio\" style=\"cursor: pointer;\" id=\"ux_rdl_cover\" name=\"ux_album_cover\" /><label>" . __(" Set as Album Cover", gallery_bank) . "</label>";
	        array_push($dynamicArray, $column2);
	
	        $column3 = "<input placeholder=\"" . __("Enter your Title", gallery_bank) . "\" class=\"layout-span12\" type=\"text\" name=\"ux_img_title_" . $dynamicId . "\" id=\"ux_img_title_" . $dynamicId . "\" />
					<textarea placeholder=\"" . __("Enter your Description ", gallery_bank) . "\" style=\"margin-top:20px\" rows=\"5\" class=\"layout-span12\" name=\"ux_txt_desc_" . $dynamicId . "\"  id=\"ux_txt_desc_" . $dynamicId . "\"></textarea>";
	        array_push($dynamicArray, $column3);
	        $column4 = "<input placeholder=\"" . __("Enter your Tags", gallery_bank) . "\" class=\"layout-span12\" readonly=\"readonly\" type=\"text\" onkeypress=\"return preventDot(event);\" name=\"ux_txt_tags_" . $dynamicId . "\" id=\"ux_txt_tags_" . $dynamicId . "\" />";
	        array_push($dynamicArray, $column4);
	        $column5 = "<input value=\"http://\" type=\"text\" id=\"ux_txt_url_" . $dynamicId . "\" name=\"ux_txt_url_" . $dynamicId . "\" class=\"layout-span12\" />";
	        array_push($dynamicArray, $column5);
	        $column6 = "<a class=\"btn hovertip\" id=\"ux_btn_delete\" style=\"cursor: pointer;\" data-original-title=\"" . __("Delete Image", gallery_bank) . "\" onclick=\"deleteImage(this);\"><i class=\"icon-trash\"></i></a>";
	        array_push($dynamicArray, $column6);
	        echo json_encode($dynamicArray);
	        die();
	    } 
	    else if ($_REQUEST["param"] == "add_new_album") {
	        $ux_album_name1 = htmlspecialchars(esc_attr($_REQUEST["album_name"]));
	        $ux_album_name = ($ux_album_name1 == "") ? "Untitled Album" : $ux_album_name1;
	        $ux_description = html_entity_decode(esc_attr($_REQUEST["uxDescription"]));
	        $wpdb->query
	            (
	                $wpdb->prepare
	                    (
	                        "INSERT INTO " . gallery_bank_albums() . "(album_name, description, album_date, author)
							VALUES(%s, %s, CURDATE(), %s)",
	                        $ux_album_name,
	                        $ux_description,
	                        $current_user->display_name
	                    )
	            );
	        echo $EventLastId = $wpdb->insert_id;
	        $wpdb->query
	            (
	                $wpdb->prepare
	                    (
	                        "UPDATE " . gallery_bank_albums() . " SET album_order = %d WHERE album_id = %d",
	                        $EventLastId,
	                        $EventLastId
	                    )
	            );
	        die();
	    }
	    else if ($_REQUEST["param"] == "add_pic") {
	        $ux_albumid = intval($_REQUEST["album_id"]);
	        $ux_controlType = esc_attr($_REQUEST["controlType"]);
	        $ux_img_name = esc_attr(html_entity_decode($_REQUEST["imagename"]));
	        $ux_albumCover = esc_attr($_REQUEST["isAlbumCoverSet"]);
	        $ux_title = html_entity_decode(esc_attr($_REQUEST["title"]));
	        $ux_description = html_entity_decode(esc_attr($_REQUEST["description"]));
	        $ux_tags = html_entity_decode(esc_attr($_REQUEST["tags"]));
	        $ux_urlRedirect = esc_attr($_REQUEST["urlRedirect"]);
	        $img_gb_path = esc_attr($_REQUEST["img_gb_path"]);
	        $cover_width = intval($_REQUEST["cover_width"]);
	        $cover_height = intval($_REQUEST["cover_height"]);
	
	        if ($ux_controlType == "image") {
	            if ($ux_albumCover == "checked")
	            {
	                $wpdb->query
	                    (
	                        $wpdb->prepare
	                            (
	                                "INSERT INTO " . gallery_bank_pics() . " (album_id,thumbnail_url,title,description,url,video,date,tags,pic_name,album_cover)
								VALUES(%d,%s,%s,%s,%s,%d,CURDATE(),%s,%s,%d)",
	                                $ux_albumid,
	                                $img_gb_path,
	                                $ux_title,
	                                $ux_description,
	                                $ux_urlRedirect,
	                                0,
	                                $ux_tags,
	                                $ux_img_name,
	                                1
	                            )
	                    );
	                process_album_upload($img_gb_path, $cover_width, $cover_height);
	            } 
	            else
	            {
	                $wpdb->query
	                    (
	                        $wpdb->prepare
	                            (
	                                "INSERT INTO " . gallery_bank_pics() . " (album_id,thumbnail_url,title,description,url,video,date,tags,pic_name,album_cover)
								VALUES(%d,%s,%s,%s,%s,%d,CURDATE(),%s,%s,%d)",
	                                $ux_albumid,
	                                $img_gb_path,
	                                $ux_title,
	                                $ux_description,
	                                $ux_urlRedirect,
	                                0,
	                                $ux_tags,
	                                $ux_img_name,
	                                0
	                            )
	                    );
	            }
	            $pic_id = $wpdb->insert_id;
	            $wpdb->query
	                (
	                    $wpdb->prepare
	                        (
	                            "UPDATE " . gallery_bank_pics() . " SET sorting_order = %d WHERE pic_id = %d",
	                            $pic_id,
	                            $pic_id
	                        )
	                );
	        }
	        else
	        {
	            $wpdb->query
	                (
	                    $wpdb->prepare
	                        (
	                            "INSERT INTO " . gallery_bank_pics() . " (album_id,thumbnail_url,title,description,url,video,date,tags,pic_name)
							VALUES(%d,%s,%s,%s,%s,%d,CURDATE(),%s,%s)",
	                            $ux_albumid,
	                            "",
	                            $ux_title,
	                            $ux_description,
	                            $ux_urlRedirect,
	                            1,
	                            $ux_tags,
	                            $ux_img_name
	                        )
	                );
	            $pic_id = $wpdb->insert_id;
	            $wpdb->query
	                (
	                    $wpdb->prepare
	                        (
	                            "UPDATE " . gallery_bank_pics() . " SET sorting_order = %d WHERE pic_id = %d",
	                            $pic_id,
	                            $pic_id
	                        )
	                );
	        }
	        die();
	    }
	    else if ($_REQUEST["param"] == "update_album")
	    {
	        $albumId = intval($_REQUEST["albumid"]);
	        $ux_edit_album_name1 = htmlspecialchars(esc_attr($_REQUEST["edit_album_name"]));
	        $ux_edit_album_name = ($ux_edit_album_name1 == "") ? "Untitled Album" : $ux_edit_album_name1;
	        $ux_edit_description = html_entity_decode(esc_attr($_REQUEST["uxEditDescription"]));
	        $wpdb->query
	            (
	                $wpdb->prepare
	                    (
	                        "UPDATE " . gallery_bank_albums() . " SET album_name = %s, description = %s WHERE album_id = %d",
	                        $ux_edit_album_name,
	                        $ux_edit_description,
	                        $albumId
	                    )
	            );
	        die();
	    }
	    else if ($_REQUEST["param"] == "update_pic")
	    {
	        $ux_picId = intval($_REQUEST["picId"]);
	        $ux_controlType = esc_attr($_REQUEST["controlType"]);
	        $ux_albumCover = $_REQUEST["isAlbumCoverSet"];
	        $ux_title = html_entity_decode(esc_attr($_REQUEST["title"]));
	        $ux_description = html_entity_decode(esc_attr($_REQUEST["description"]));
	        $ux_tags = html_entity_decode(esc_attr($_REQUEST["tags"]));
	        $ux_urlRedirect = esc_attr($_REQUEST["urlRedirect"]);
	        $img_gb_path = esc_attr($_REQUEST["img_gb_path"]);
	        $cover_width = intval($_REQUEST["cover_width"]);
	        $cover_height = intval($_REQUEST["cover_height"]);
	        if ($ux_controlType == "image") {
	            if ($ux_albumCover == "checked") {
	                $wpdb->query
	                    (
	                        $wpdb->prepare
	                            (
	                                "UPDATE " . gallery_bank_pics() . " SET title = %s, description = %s, url = %s, date = CURDATE(), tags = %s, album_cover = %d WHERE pic_id = %d",
	                                $ux_title,
	                                $ux_description,
	                                $ux_urlRedirect,
	                                $ux_tags,
	                                1,
	                                $ux_picId
	                            )
	                    );
	                process_album_upload($img_gb_path, $cover_width, $cover_height);
	            } else {
	                $wpdb->query
	                    (
	                        $wpdb->prepare
	                            (
	                                "UPDATE " . gallery_bank_pics() . " SET title = %s, description = %s, url = %s, date = CURDATE(), tags = %s, album_cover = %d WHERE pic_id = %d",
	                                $ux_title,
	                                $ux_description,
	                                $ux_urlRedirect,
	                                $ux_tags,
	                                0,
	                                $ux_picId
	                            )
	                    );
	            }
	        } else {
	            $wpdb->query
	                (
	                    $wpdb->prepare
	                        (
	                            "UPDATE " . gallery_bank_pics() . " SET title = %s, description = %s, date = CURDATE(), tags = %s, album_cover = %d WHERE pic_id = %d",
	                            $ux_title,
	                            $ux_description,
	                            $ux_tags,
	                            0,
	                            $ux_picId
	                        )
	                );
	        }
	        die();
	    }
	    else if ($_REQUEST["param"] == "delete_pic")
	    {
	        $delete_array = (html_entity_decode($_REQUEST["delete_array"]));
	        $albumId = intval($_REQUEST["albumid"]);
	
	        $wpdb->query
	        (
	            $wpdb->prepare
	                (
	                    "DELETE FROM " . gallery_bank_pics() . " WHERE pic_id in ($delete_array)",
	                    ""
	                )
	        );
	        die();
	    }
	    else if ($_REQUEST["param"] == "Delete_album")
	    {
	        $album_id = intval($_REQUEST["album_id"]);
	        $wpdb->query
	        (
	            $wpdb->prepare
	                (
	                    "DELETE FROM " . gallery_bank_pics() . " WHERE album_id = %d",
	                    $album_id
	                )
	        );
	        $wpdb->query
	        (
	            $wpdb->prepare
	                (
	                    "DELETE FROM " . gallery_bank_albums() . " WHERE album_id = %d",
	                    $album_id
	                )
	        );
	        die();
	    }
	}
}
function process_image_upload($image, $width, $height)
{
    $temp_image_path = UPLOADED_IMAGE_DESTINATION . $image;
    $temp_image_name = $image;
    list(, , $temp_image_type) = getimagesize($temp_image_path);
    if ($temp_image_type === NULL) {
        return false;
    }
    switch ($temp_image_type) {

        case IMAGETYPE_GIF:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".gif", $temp_image_name);
            break;
        case IMAGETYPE_JPEG:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $type = explode(".", $image);
            if ($type[1] == "jpg" || $type[1] == "JPG") {
                $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".jpg", $temp_image_name);
            } else {
                $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".jpeg", $temp_image_name);
            }
            break;
        case IMAGETYPE_PNG:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".png", $temp_image_name);
            break;
        default:
            return false;
    }
    $result = generate_thumbnail($uploaded_image_path, $thumbnail_image_path, $width, $height);
    return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
}

/******************************************Code for Album cover thumbs Creation**********************/
function process_album_upload($album_image, $width, $height)
{
    $temp_image_path = UPLOADED_IMAGE_DESTINATION . $album_image;
    $temp_image_name = $album_image;
    list(, , $temp_image_type) = getimagesize($temp_image_path);
    if ($temp_image_type === NULL) {
        return false;
    }
    switch ($temp_image_type) {
        case IMAGETYPE_GIF:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $thumbnail_image_path = THUMBNAIL_ALBUM_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".gif", $temp_image_name);
            break;
        case IMAGETYPE_JPEG:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $type = explode(".", $album_image);
            if ($type[1] == "jpg" || $type[1] == "JPG") {
                $thumbnail_image_path = THUMBNAIL_ALBUM_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".jpg", $temp_image_name);
            } else {
                $thumbnail_image_path = THUMBNAIL_ALBUM_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".jpeg", $temp_image_name);
            }
            break;
        case IMAGETYPE_PNG:
            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
            move_uploaded_file($temp_image_path, $uploaded_image_path);
            $thumbnail_image_path = THUMBNAIL_ALBUM_DESTINATION . preg_replace("{\\.[^\\.]+$}", ".png", $temp_image_name);
            break;
        default:
            return false;
    }
    $result = generate_thumbnail($uploaded_image_path, $thumbnail_image_path, $width, $height);
    return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
}

function generate_thumbnail($source_image_path, $thumbnail_image_path, $imageWidth, $imageHeight)
{
    list($source_image_width, $source_image_height, $source_image_type) = getimagesize($source_image_path);
    $source_gd_image = false;
    switch ($source_image_type) {
        case IMAGETYPE_GIF:
            $source_gd_image = imagecreatefromgif($source_image_path);
            break;
        case IMAGETYPE_JPEG:
            $source_gd_image = imagecreatefromjpeg($source_image_path);
            break;
        case IMAGETYPE_PNG:
            $source_gd_image = imagecreatefrompng($source_image_path);
            break;
    }
    if ($source_gd_image === false) {
        return false;
    }
    $source_aspect_ratio = $source_image_width / $source_image_height;
    if ($source_image_width > $source_image_height) {
        $real_height = $imageHeight;
        $real_width = $imageHeight * $source_aspect_ratio;
    } else if ($source_image_height > $source_image_width) {
        $real_height = $imageWidth / $source_aspect_ratio;
        $real_width = $imageWidth;

    } else {

        $real_height = $imageHeight > $imageWidth ? $imageHeight : $imageWidth;
        $real_width = $imageWidth > $imageHeight ? $imageWidth : $imageHeight;
    }


    $thumbnail_gd_image = imagecreatetruecolor($real_width, $real_height);
    $bg_color = imagecolorallocate($thumbnail_gd_image, 255, 255, 255);
    imagefilledrectangle($thumbnail_gd_image, 0, 0, $real_width, $real_height, $bg_color);
    imagecopyresampled($thumbnail_gd_image, $source_gd_image, 0, 0, 0, 0, $real_width, $real_height, $source_image_width, $source_image_height);
    imagejpeg($thumbnail_gd_image, $thumbnail_image_path, 100);
    imagedestroy($source_gd_image);
    imagedestroy($thumbnail_gd_image);
    return true;
}

?>