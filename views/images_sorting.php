<?php
	$album_id = $_REQUEST["album_id"];
	$pic_detail = $wpdb->get_results
	( 
		$wpdb->prepare
		(
			"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d order by sorting_order asc",
			$album_id 
		)
	);
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
		$album_css = $wpdb->get_row
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
		$album_css = $wpdb->get_row
		(
			$wpdb->prepare
			(
			"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id =%d",
			$get_settings,
			$album_id
			)
		);
	}
	$content = explode("/", $album_css->setting_content);
	$image_settings = explode(";", $content[0]);
	$image_content = explode(":", $image_settings[0]);
	$image_width = explode(":", $image_settings[1]);
	$image_height = explode(":", $image_settings[2]);
	$images_in_row = explode(":", $image_settings[3]);
	$image_opacity = explode(":", $image_settings[4]);
	$image_border_size_value = explode(":", $image_settings[5]);
	$image_radius_value = explode(":", $image_settings[6]);
	$border_color = explode(":", $image_settings[7]);
	
	$lightbox_settings = explode(";", $content[2]);
	$overlay_opacity = explode(":", $lightbox_settings[0]);
	$overlay_border_size_value = explode(":", $lightbox_settings[1]);
	$overlay_border_radius = explode(":", $lightbox_settings[2]);
	$lightbox_text_color = explode(":", $lightbox_settings[3]);
	$overlay_border_color = explode(":", $lightbox_settings[4]);
	$lightbox_inline_bg_color = explode(":", $lightbox_settings[5]);
	$lightbox_bg_color = explode(":", $lightbox_settings[6]);
	$litebox_bg_color_substring = str_replace("rgb","rgba",substr($lightbox_bg_color[1], 0, -1));
	$litebox_bg_color_with_opacity = $litebox_bg_color_substring. "," . $overlay_opacity[1] . ")";
	$lightbox_bg_color_value= $overlay_border_size_value[1] . " solid " . $overlay_border_color[1];
	
	$slideshow_settings = explode(";", $content[3]);
	$auto_play = explode(":", $slideshow_settings[0]);
	$slide_interval = explode(":", $slideshow_settings[1]);
?>
<div class="block well" style="min-height:400px;">
	<div class="navbar">
		<div class="navbar-inner">
			<h5><?php _e( "Re-order Images", gallery_bank ); ?></h5>
		</div>
	</div>
	<div class="body" style="margin:10px;">
		<a class="btn btn-inverse" href="admin.php?page=gallery_bank"><?php _e( "Back to Album Overview", gallery_bank ); ?></a>
		<div class="separator-doubled"></div>
		<div class="row-fluid">
			<div class="span12">
				<div class="block well">
					<div class="navbar">
						<div class="navbar-inner">
							<h5><?php echo stripcslashes($album->album_name);?></h5>
						</div>
					</div>
					<form id="sort_album" class="form-horizontal" method="post" action="">
						<div id="view_bank_album">
						<?php
							for ($flag = 0; $flag <count($pic_detail); $flag++)
							{
								if($pic_detail[$flag]->description == "")
								{
									if(($flag % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
										</br>
											<?php
											if($image_content[1] == 1)
											{
												$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
												<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>" style="margin:5px;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											else
											{
												$css_custom_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
												<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin:5px;<?php echo $css_custom_image_thumbnail; ?>"  />
												<?php
											}
									}
									else 
									{
										?>
											<?php
											if($image_content[1] == 1)
											{
												$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>" style="margin:5px;<?php echo $css_image_thumbnail; ?>"  />
												<?php
											}
											else 
											{
												$css_custom_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin:5px;<?php echo $css_custom_image_thumbnail; ?>"  />
												<?php
											}
									}
								}
								else 
								{
									if(($flag % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
										</br>
											<?php
											if($image_content[1] == 1)
											{
												$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>" style="margin:5px;<?php echo $css_image_thumbnail; ?>"  />
												<?php
											}
											else 
											{
												$css_custom_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin:5px;<?php echo $css_custom_image_thumbnail; ?>"  />
												<?php
											}
									}
									else
									{
										?>
										
											<?php
											if($image_content[1] == 1)
											{
												$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>" style="margin:5px;<?php echo $css_image_thumbnail; ?>"  />
												<?php
											}
											else 
											{
												$css_custom_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1];
												?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin:5px;<?php echo $css_custom_image_thumbnail; ?>"  />
												<?php
											}
									}
								}
								
							}
							
							?>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	img:hover
	{
		cursor:move;
	}
</style>
<script type="text/javascript">
	
	jQuery(document).ready(function()
	{
		jQuery("#view_bank_album").sortable
		({
			opacity: 0.6,
			cursor: 'move',
			update: function()
			{
				jQuery.post(ajaxurl, jQuery(this).sortable("serialize")+"&param=reorderControls&action=album_gallery_library", function(data)
				{
				});
			}
		});
	});
</script>