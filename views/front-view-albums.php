<?php

global $wpdb;
	$albums = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(*) FROM ". gallery_bank_albums() . " WHERE album_id = %d",
			$album_id
		)
	);
	if($albums > 0)
	{
	$album = $wpdb->get_row
	(
		$wpdb->prepare
		(
		"SELECT * FROM ".gallery_bank_albums()." where album_id = %d",
		$album_id
		)
	);
	$unique_id = rand(100,10000);
	$get_settings = $wpdb->get_var
	(
		$wpdb->prepare
		(
		"SELECT album_settings FROM ". gallery_bank_settings(). " WHERE album_id = %d ",
		$album_id
		)
	);
	if($get_settings == 1)
	{
		$sett_val = $wpdb->get_row
		(
			$wpdb->prepare
			(
			"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id = %d",
			$get_settings,
			0
			)
		);
	}
	else
	{
		$sett_val = $wpdb->get_row
		(
			$wpdb->prepare
			(
			"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id =%d",
			$get_settings,
			$album_id
			)
		);
	}
	$setting_cover = $wpdb->get_row
	(
		$wpdb->prepare
		(
			"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id = %d",
			$get_settings,
			$album_id
		)
	);
	$content = explode("/", $sett_val->setting_content);
	$cover_settings = explode(";", $content[1]);
	$cover_content = explode(":", $cover_settings[0]);
	$cover_width = explode(":", $cover_settings[1]);
	$cover_height = explode(":", $cover_settings[2]);
	$cover_opacity = explode(":", $cover_settings[3]);
	$cover_border_size = explode(":", $cover_settings[4]);
	$cover_border_radius = explode(":", $cover_settings[5]);
	$cover_border_color = explode(":", $cover_settings[6]);
	$cover_border_value = $cover_border_size[1] ." solid " . $cover_border_color[1];
	$pagination_settings = explode(";", $content[4]);
	$pagination = explode(":", $pagination_settings[0]);
?>
<button id="back_button<?php echo $unique_id;?>" style="margin-top:10px; display: none;" onclick="view_albums<?php echo $unique_id;?>();">
	<span style="color: #000;"> &laquo <?php _e('Back to Albums', gallery_bank); ?></span>
</button>
<?php
if(($setting_cover->album_cover == "undefined") || ($setting_cover->album_cover == "") )
{
	$url = GALLERY_BK_PLUGIN_URL."/album-cover.png";
	?>
	<div id="main_div<?php echo $unique_id;?>" style="display: block;" class="album-cover">
		<?php
			$album_custom_cover_css = "border:"  . $cover_border_value . ";-moz-border-radius:". $cover_border_radius[1] ."; -webkit-border-radius:". $cover_border_radius[1] . ";-khtml-border-radius:". $cover_border_radius[1] . ";-o-border-radius:" . $cover_border_radius[1] . ";border-radius:" . $cover_border_radius[1].";-moz-opacity:".$cover_opacity[1]. ";-khtml-opacity:".$cover_opacity[1]. ";-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=".($cover_opacity[1]* 100).")'". ";filter:alpha(opacity=".$cover_opacity[1] * 100 . ");opacity:". $cover_opacity[1]. ";";
		?>
		<img class="imgHolder" src="<?php echo stripcslashes($url); ?>" onclick="view_images_<?php echo $unique_id;?>(<?php echo $album_id;?>);" style="cursor:pointer;<?php echo $album_custom_cover_css; ?>" />	
		<div style="text-align: justify;display:inline-block;vertical-align:top;margin-left:20px;">
			<h3><?php echo stripcslashes($album->album_name); ?>&nbsp;</h3>
			<span><?php echo stripcslashes($album->description);?>&nbsp;</span><br/>
			<a style="cursor: pointer;" onclick="view_images_<?php echo $unique_id;?>(<?php echo $album_id;?>)">
				<?php _e("See Album images", gallery_bank); ?> &raquo
			</a>
		</div>
	</div>
	<?php
}
else 
{
	$domain = strstr($setting_cover->album_cover, '/wp-content');
	?>
	<div id="main_div<?php echo $unique_id;?>" style="display: block;" class="album-cover">
		<?php
		if($cover_content[1] == 1)
		{
			$album_custom_cover_css = "border:" .$cover_border_value.";-moz-border-radius:". $cover_border_radius[1] ."; -webkit-border-radius:". $cover_border_radius[1] . ";-khtml-border-radius:". $cover_border_radius[1] .";-o-border-radius:" . $cover_border_radius[1] . ";border-radius:" . $cover_border_radius[1].";-moz-opacity:".$cover_opacity[1]. ";-khtml-opacity:".$cover_opacity[1]. ";-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=".($cover_opacity[1]* 100).")'". ";filter:alpha(opacity=".$cover_opacity[1] * 100 . ");opacity:". $cover_opacity[1]. ";";
			?>
			<img class="imgHolder" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($setting_cover->album_cover).'&h=150&w=150&zc=1&q=100';?>" onclick="view_images_<?php echo $unique_id;?>(<?php echo $album_id;?>);" style="cursor:pointer;<?php echo $album_custom_cover_css; ?>" />
			<?php
			
		}
		else 
		{
			$album_cover_css = "border:" . $cover_border_value . ";-moz-border-radius:". $cover_border_radius[1] ."; -webkit-border-radius:". $cover_border_radius[1] . ";-khtml-border-radius:". $cover_border_radius[1] . ";-o-border-radius:" . $cover_border_radius[1] . ";border-radius:" . $cover_border_radius[1].";-moz-opacity:".$cover_opacity[1]. ";-khtml-opacity:".$cover_opacity[1]. ";-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=".($cover_opacity[1]* 100).")'". ";filter:alpha(opacity=".$cover_opacity[1] * 100 . ");opacity:". $cover_opacity[1]. ";";
			?>
			<img class="imgHolder" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($setting_cover->album_cover).'&h='.$cover_height[1].'&w='.$cover_width[1].'&zc=1&q=100';?>" onclick="view_images_<?php echo $unique_id;?>(<?php echo $album_id;?>);" style="cursor:pointer;<?php echo $album_cover_css; ?>" />
			<?php
			
		}
		?>
		<div style="text-align: justify;display:inline-block;vertical-align:top;margin-left:20px;">
			<h3><?php echo stripcslashes($album->album_name); ?>&nbsp;</h3>
			<span><?php echo stripcslashes($album->description);?>&nbsp;</span><br/>
			<a style="cursor: pointer;" onclick="view_images_<?php echo $unique_id;?>(<?php echo $album_id;?>)">
				<?php _e("See Album images", gallery_bank ); ?> &raquo
			</a>
		</div>
	</div>
	<?php
}
?>
<div id="image_show_div<?php echo $unique_id;?>" style="display: none;" class="images-cover">
	<h3 id="album_title<?php echo $unique_id;?>"><?php echo stripcslashes($album->album_name); ?>&nbsp;</h3>
	<div id="show_images_<?php echo $unique_id;?>" >
	</div>
</div>
<script type="text/javascript">
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	function view_images_<?php echo $unique_id;?>(album_id)
	{
		jQuery(".album-cover").css('display','none');
		jQuery("#main_div<?php echo $unique_id;?>").css('display','none');
		jQuery("#back_button<?php echo $unique_id;?>").css('display','none');
		jQuery("#image_show_div<?php echo $unique_id;?>").css('display','block');
		jQuery.post(ajaxurl, "album_id="+album_id+"&param=show_images&action=front_albums_gallery_library", function(data)
		{
			jQuery("#back_button<?php echo $unique_id;?>").css('display','block');
			jQuery('#show_images_<?php echo $unique_id;?>').html(data);
		});
		jQuery.post(ajaxurl, "album_id="+album_id+"&param=get_album_name&action=front_albums_gallery_library", function(data)
		{
			jQuery("#album_title<?php echo $unique_id;?>").html(data);
		});
	}
	function view_albums<?php echo $unique_id;?>()
	{
		jQuery(".album-cover").css('display','block');
		jQuery(".images-cover").css('display','none');
		jQuery("#back_button<?php echo $unique_id;?>").css('display','none');
	}
</script>
<?php
}
?>