<?php
	if(isset($_REQUEST["param"]))
	{
		if($_REQUEST["param"] == "show_images")
		{
			global $wpdb;
			$album_id = intval($_REQUEST['album_id']);
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
					"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id = %d",
					$get_settings,
					$album_id
					)
				);
			}
			$unique_id = rand(100,10000);
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
			?>
			<div id="view_bank_album_<?php echo $unique_id;?>">
			<?php
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1].";filter:alpha(opacity=".$image_opacity[1] * 100 . ");-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=".$image_opacity[1] * 100 . ")';-moz-opacity:" . $image_opacity[1] . ";-khtml-opacity:".$image_opacity[1]. ";";
				if($pic_detail[$flag]->description == "")
				{
					if(($flag % $images_in_row[1] == 0) && $flag != 0)
					{
						if($pic_detail[$flag]->check_url == 1)
						{
							if($image_content[1] == 1)
							{
								?>
								<div class="imgContainerSingle">
									<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
									</a>
								</div>
							<?php
							}
							
						}
						else
						{
							?>
							<div class="imgContainerSingle">
								<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
								<?php
								if($image_content[1] == 1)
								{
									if($pic_detail[$flag]->video == 1)
									{
										?>
										<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
										<?php
									}
									else
									{
										?>
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
										<?php
									}
									?>
								</a>
							</div>
							<?php
								}
						}
					}
					else 
					{
					
						if($pic_detail[$flag]->check_url == 1)
						{
							if($image_content[1] == 1)
							{
								?>
								<div class="imgContainerSingle">
								<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
									
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
									
									
								</a>
								</div>
							<?php
							}
						}
						else
						{
						?>
						<div class="imgContainerSingle">
						<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
						<?php
						if($image_content[1] == 1)
						{
							if($pic_detail[$flag]->video == 1)
							{
								?>
								<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
								<?php
							}
							else
							{
								?>
								<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
								<?php
							}
								?>
							</a>	
							</div>
							<?php
						}
						
					}
					}
				}
				else 
				{
					if(($flag % $images_in_row[1] == 0) && $flag != 0)
					{
						
						if($pic_detail[$flag]->check_url == 1)
						{
							if($image_content[1] == 1)
							{
								?>
								<div class="imgContainerSingle">
								<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
									<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
								</a>
								</div>
							<?php
							}
							
						}
						else 
						{
							if($pic_detail[$flag]->description == "")
							{
								?>
								<div class="imgContainerSingle">
									<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
								<?php
							}
							else 
							{
								?>
								<div class="imgContainerSingle">
									<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?> (<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->description)); ?>)">
								<?php
							}
						
						if($image_content[1] == 1)
						{
							
							
										?>
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
									
						</a>
								</div>
							<?php
							
						}
						
						}
					}
					else
					{
						
						
						if($pic_detail[$flag]->check_url == 1)
						{
							if($image_content[1] == 1)
							{
								?>
								<div class="imgContainerSingle">
								<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
									
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
										
								</a>
								</div>
							<?php
							}
							
						}
						else 
						{
							if($pic_detail[$flag]->description == "")
							{
								?>
								<div class="imgContainerSingle">
									<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
								<?php
							}
							else 
							{
								?>
								<div class="imgContainerSingle">
									<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?> (<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->description)); ?>)">
								<?php
							}
						
						if($image_content[1] == 1)
						{
							?>
										<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin-left:5px;width:155px;<?php echo $css_image_thumbnail; ?>" />
									
						</a>
								</div>
							<?php
						}
						
						}
					}
				}
				
			}
			
			?>
			</div>
		
			<script type="text/javascript">
		
				jQuery(document).ready(function(){
					jQuery("div.da-thumbs").hoverdir();
					jQuery(".titan-lb_<?php echo $unique_id;?>").lightbox({
						beforeShow: function(){
							jQuery(".lightbox-skin").css("background","<?php echo $lightbox_inline_bg_color[1]; ?>");
							jQuery(".lightbox-overlay").css("background","<?php echo $litebox_bg_color_with_opacity; ?>");
							jQuery(".lightbox-wrap").css("border-radius","<?php echo $overlay_border_radius[1]; ?>");
							jQuery(".lightbox-wrap").css("-moz-border-radius","<?php echo $overlay_border_radius[1]; ?>");
							jQuery(".lightbox-wrap").css("-webkit-border-radius","<?php echo $overlay_border_radius[1]; ?>");
							jQuery(".lightbox-wrap").css("-khtml-border-radius","<?php echo $overlay_border_radius[1]; ?>");
							jQuery(".lightbox-wrap").css("-o-border-radius","<?php echo $overlay_border_radius[1]; ?>");
							jQuery(".lightbox-wrap").css("border","<?php echo $lightbox_bg_color_value; ?>");
						},
						afterShow : function()
						{
							jQuery(".lightbox-title").css("color","<?php echo $lightbox_text_color[1]; ?>");
						}
					});
					
				});
				
				var $container_<?php echo $unique_id;?> = jQuery('#view_bank_album_<?php echo $unique_id;?>');
				$container_<?php echo $unique_id;?>.imagesLoaded( function(){
					$container_<?php echo $unique_id;?>.masonry({
					
					itemSelector : '.imgContainerSingle',
					isAnimated: true,
					animationOptions: {
						duration: 750,
						easing: 'linear',
						queue: false
						}
					});
					});
					$container_<?php echo $unique_id;?>.masonry('reload');
				</script>
			<?php
			die();
		}
		elseif($_REQUEST["param"] == "get_album_name")
		{
			$album_id = intval($_REQUEST['album_id']);
			$album_name = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT album_name from ".gallery_bank_albums()." where album_id = %d",
					$album_id
				)
			);
			echo stripslashes(htmlspecialchars_decode($album_name));
			die();
		}
	}
?>