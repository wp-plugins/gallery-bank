<?php
global $wpdb;
require_once(ABSPATH . "wp-admin/includes/upgrade.php");
include_once (GALLERY_BK_PLUGIN_DIR . "/lib/class-tracking.php");
update_option("gallery-bank-updation-check-url","http://tech-banker.com/wp-admin/admin-ajax.php");
$version = get_option("gallery-bank-pro-edition");
if($version != "3.0")
{
    if (count($wpdb->get_var("SHOW TABLES LIKE '" . gallery_bank_albums() . "'")) == 0)
    {
        create_table_albums();
    }
    else
    {
        $albums = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "Select * FROM " . gallery_bank_albums(), ""
            )
        );

        $sql = "DROP TABLE " . gallery_bank_albums();
        $wpdb->query($sql);

        create_table_albums();

        if(count($albums) > 0)
        {
            for($flag = 0; $flag < count($albums); $flag++)
            {
                $wpdb->query
                (
                    $wpdb->prepare
                    (
                        "INSERT INTO " . gallery_bank_albums() . "(album_id, album_name, author, album_date,
                        description, album_order) VALUES(%d, %s, %s, %s, %s, %d)",
                        $albums[$flag]->album_id,
                        $albums[$flag]->album_name,
                        $albums[$flag]->author,
                        $albums[$flag]->album_date,
                        $albums[$flag]->description,
                        $albums[$flag]->album_id
                    )
                );
            }
        }
    }
    if (count($wpdb->get_var("SHOW TABLES LIKE '" . gallery_bank_pics() . "'")) == 0)
    {
        create_table_album_pics();
    }
    else
    {
        $album_pics = $wpdb->get_results
        (
            $wpdb->prepare
            (
                "Select * FROM " . gallery_bank_pics(), ""
            )
        );

        $sql = "DROP TABLE " . gallery_bank_pics();
        $wpdb->query($sql);

        create_table_album_pics();
        
        if(count($album_pics) > 0)
        {
            $album_id = 0;
            for($flag = 0; $flag < count($album_pics); $flag++)
            {
                if(isset($album_pics[$flag]->video))
                {
                    if($album_pics[$flag]->video == 1)
                    {
                        $wpdb->query
                        (
                            $wpdb->prepare
                            (
                                "INSERT INTO " . gallery_bank_pics() . "(pic_id, album_id, title, description, thumbnail_url,
                                sorting_order, date, url, video, tags, pic_name, album_cover) VALUES(%d, %d, %s, %s, %s, %d, %s,
                                %s, %d, %s, %s, %d)",
                                $album_pics[$flag]->pic_id,
                                $album_pics[$flag]->album_id,
                                $album_pics[$flag]->title,
                                $album_pics[$flag]->description,
                                $album_pics[$flag]->thumbnail_url,
                                $album_pics[$flag]->sorting_order,
                                $album_pics[$flag]->date,
                                $album_pics[$flag]->url,
                                $album_pics[$flag]->video,
                                $album_pics[$flag]->tags,
                               isset($album_pics[$flag]->pic_path) ?  $album_pics[$flag]->pic_path : "",
                               0
                            )
                        );
                    }
                    else
                    {
                        $file_path = explode("/", $album_pics[$flag]->pic_path);
                        $destination = UPLOADED_IMAGE_DESTINATION . $file_path[count($file_path) - 1];

                        if (PHP_VERSION > 5)
                        {
                            copy($album_pics[$flag]->pic_path, $destination);
                        }
                        else
                        {
                            $content = file_get_contents($album_pics[$flag]->pic_path);
                            $fp = fopen($destination, "w");
                            fwrite($fp, $content);
                            fclose($fp);
                        }
                        if(file_exists($destination))
                        {
                            process_image_upload($file_path[count($file_path) - 1], 160, 120);
                        }

                        $wpdb->query
                        (
                            $wpdb->prepare
                            (
                                "INSERT INTO " . gallery_bank_pics() . "(pic_id, album_id, title, description, thumbnail_url,
                        sorting_order, date, url, video, tags, pic_name, album_cover) VALUES(%d, %d, %s, %s, %s, %d, %s,
                        %s, %d, %s, %s, %d)",
                                $album_pics[$flag]->pic_id,
                                $album_pics[$flag]->album_id,
                                $album_pics[$flag]->title,
                                $album_pics[$flag]->description,
                                $file_path[count($file_path) - 1],
                                $album_pics[$flag]->sorting_order,
                                $album_pics[$flag]->date,
                                $album_pics[$flag]->url,
                                $album_pics[$flag]->video,
                                $album_pics[$flag]->tags,
                                $file_path[count($file_path) - 1],
                                $album_id == $album_pics[$flag]->album_id ? 0 : 1
                            )
                        );
                        if($album_id != $album_pics[$flag]->album_id)
                        {
                            process_album_upload($file_path[count($file_path) - 1], 160, 120);
                        }
                        $album_id = $album_pics[$flag]->album_id;
                    }
                }
                else
                {
                    $file_path = explode("/", $album_pics[$flag]->pic_path);
                    $destination = UPLOADED_IMAGE_DESTINATION . $file_path[count($file_path) - 1];

                    if (PHP_VERSION > 5)
                    {
                        copy($album_pics[$flag]->pic_path, $destination);
                    }
                    else
                    {
                        $content = file_get_contents($album_pics[$flag]->pic_path);
                        $fp = fopen($destination, "w");
                        fwrite($fp, $content);
                        fclose($fp);
                    }
                    if(file_exists($destination))
                    {
                        process_image_upload($file_path[count($file_path) - 1], 160, 120);
                    }

                    $wpdb->query
                        (
                            $wpdb->prepare
                                (
                                    "INSERT INTO " . gallery_bank_pics() . "(pic_id, album_id, title, description, thumbnail_url,
                            sorting_order, date, url, video, tags, pic_name, album_cover) VALUES(%d, %d, %s, %s, %s, %d, %s,
                            %s, %d, %s, %s, %d)",
                                    $album_pics[$flag]->pic_id,
                                    $album_pics[$flag]->album_id,
                                    $album_pics[$flag]->title,
                                    $album_pics[$flag]->description,
                                    $file_path[count($file_path) - 1],
                                    $album_pics[$flag]->sorting_order,
                                    $album_pics[$flag]->date,
                                    $album_pics[$flag]->url,
                                    0,
                                    $album_pics[$flag]->tags,
                                    $file_path[count($file_path) - 1],
                                    $album_id == $album_pics[$flag]->album_id ? 0 : 1
                                )
                        );
                    if($album_id != $album_pics[$flag]->album_id)
                    {
                        process_album_upload($file_path[count($file_path) - 1], 160, 120);
                    }
                    $album_id = $album_pics[$flag]->album_id;
                }
            }
        }
    }
    if (count($wpdb->get_var("SHOW TABLES LIKE '" . gallery_bank_settings() . "'")) == 0)
    {
        create_table_album_settings();
    }
    else
    {
        $sql = "DROP TABLE " . gallery_bank_settings();
        $wpdb->query($sql);

        create_table_album_settings();
    }
}

/******************************************Code for Thumbnails Creation**********************/
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
	            $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace('{\\.[^\\.]+$}', '.gif', $temp_image_name);
	            break;
	        case IMAGETYPE_JPEG:
	            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
	            move_uploaded_file($temp_image_path, $uploaded_image_path);
	            $type = explode(".", $image);
	            if ($type[1] == "jpg" || $type[1] == "JPG") {
	                $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace('{\\.[^\\.]+$}', '.jpg', $temp_image_name);
	            } else {
	                $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace('{\\.[^\\.]+$}', '.jpeg', $temp_image_name);
	            }
	            break;
	        case IMAGETYPE_PNG:
	            $uploaded_image_path = UPLOADED_IMAGE_DESTINATION . $temp_image_name;
	            move_uploaded_file($temp_image_path, $uploaded_image_path);
	            $thumbnail_image_path = THUMBNAIL_IMAGE_DESTINATION . preg_replace('{\\.[^\\.]+$}', '.png', $temp_image_name);
	            break;
	        default:
	            return false;
	    }
	    $result = generate_thumbnail($uploaded_image_path, $thumbnail_image_path, $width, $height);
	    return $result ? array($uploaded_image_path, $thumbnail_image_path) : false;
    }
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
/****************************** COMMON FUNCTION TO GENERATE THUMBNAILS********************************/
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
	        (int)$real_height = $imageHeight;
	        (int)$real_width = $imageHeight * $source_aspect_ratio;
	    } else if ($source_image_height > $source_image_width) {
	        (int)$real_height = $imageWidth / $source_aspect_ratio;
	        (int)$real_width = $imageWidth;

	    } else {

	        (int)$real_height = $imageHeight > $imageWidth ? $imageHeight : $imageWidth;
	        (int)$real_width = $imageWidth > $imageHeight ? $imageWidth : $imageHeight;
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
    function create_table_albums()
    {
        $sql = "CREATE TABLE " . gallery_bank_albums() . "(
            album_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            album_name VARCHAR(100) NOT NULL,
            author VARCHAR(100) NOT NULL,
            album_date DATE,
            description TEXT NOT NULL,
            album_order INTEGER(10) NOT NULL,
            PRIMARY KEY (album_id)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
        dbDelta($sql);
    }
    function create_table_album_pics()
    {
        $sql = "CREATE TABLE " . gallery_bank_pics() . "(
            pic_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            album_id INTEGER(10) UNSIGNED NOT NULL,
            title TEXT NOT NULL,
            description TEXT NOT NULL,
            thumbnail_url TEXT NOT NULL,
            sorting_order INTEGER(20),
            date DATE,
            url VARCHAR(250) NOT NULL,
            video INTEGER(10) NOT NULL,
            tags TEXT NOT NULL,
            pic_name TEXT NOT NULL,
            album_cover INTEGER(1) NOT NULL,
            PRIMARY KEY(pic_id)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
        dbDelta($sql);
    }
    function create_table_album_settings()
    {
        global $wpdb;
        $sql = "CREATE TABLE " . gallery_bank_settings() . "(
            setting_id INTEGER(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            setting_key VARCHAR(100) NOT NULL,
            setting_value TEXT NOT NULL,
            PRIMARY KEY (setting_id)
            ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE utf8_general_ci";
        dbDelta($sql);

        include_once (GALLERY_BK_PLUGIN_DIR . "/lib/include_settings.php");

    }
    update_option("gallery-bank-pro-edition", "3.0");
?>