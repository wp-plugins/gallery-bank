<?php
if(isset($_REQUEST['param']))
{
	if($_REQUEST['param'] == "album_sorting")
	{
		$album_id = $_REQUEST["id"];
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
				"SELECT album_settings FROM ". gallery_bank_settings(). " WHERE album_id = %d",
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
		$pagination = explode(":", $content[4]);
		$pagination_value = str_replace(";","",$pagination[1]);
		$count = 1;
		$row_id_images = "";
		
		if($album_id != 0)
		{
		?>
			<div class="row-fluid" >
				<div class="span12">
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php echo stripcslashes(htmlspecialchars_decode($album->album_name));?></h5>
							</div>
						</div>
						<form id="sort_album" class="form-horizontal" method="post" action="">
						<input type="hidden" id="pagination_val" name="pagination_val" value="<?php echo $pagination_value; ?>" />
						<div id="view_bank_album">
						<?php
						
							for ($flag = 0; $flag <count($pic_detail); $flag++)
							{
								$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1].";filter:alpha(opacity=".$image_opacity[1] * 100 . ");-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=".$image_opacity[1] * 100 . ")';-moz-opacity:" . $image_opacity[1] . ";-khtml-opacity:".$image_opacity[1]. ";";
								
								if($pic_detail[$flag]->description == "")
								{
									if(($flag % $images_in_row[1] == 0) && $flag != 0)
									{
										
											if($image_content[1] == 1)
											{
												
												
													if($pic_detail[$flag]->video == 1)
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													}
													else 
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													
													}
													
												
												
											}
											
											$row_id_images .= "/" . $pic_detail[$flag]->pic_id;
											
									}
									else 
									{
										
											if($image_content[1] == 1)
											{
												
													if($pic_detail[$flag]->video == 1)
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													}
													else 
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													
													}
													
												
												
											}
											
											$row_id_images .=  "-". $pic_detail[$flag]->pic_id;
											
									}
								}
								else 
								{
									if(($flag % $images_in_row[1] == 0) && $flag != 0)
									{
										
											if($image_content[1] == 1)
											{
												
												
													if($pic_detail[$flag]->video == 1)
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													}
													else 
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													
													}
													
												
												
											}
											
											$row_id_images .= "/" . $pic_detail[$flag]->pic_id;
											
									}
									else
									{
											if($image_content[1] == 1)
											{
												
												
													if($pic_detail[$flag]->video == 1)
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													}
													else 
													{
														?>
													<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" src="<?php echo stripcslashes($pic_detail[$flag]->pic_path);?>" style="margin:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
													<?php
													
													}
													
												
												
											}
											
											$row_id_images .=  "-". $pic_detail[$flag]->pic_id;
											
									}
								}
								
								
							}
							
							?>
						</div>
						<input type="hidden" id="uxHdn_ids" name="uxHdn_ids" value="" />
					</form>
				</div>
			</div>
		</div>
		<?php
		}
		?>
		<script>
		var start_pic_ids = [];
		
			<?php
				$row_id_images = substr($row_id_images, 1);
			?>
		
			jQuery("#uxHdn_ids").val("<?php echo $row_id_images; ?>");
			if(jQuery("#pagination_val").val() == "1")
			{
				jQuery("#images_view_data_table tbody").sortable
				({
					opacity: 0.6,
					cursor: 'move',
					update: function()
					{
						var row_ids = jQuery("#uxHdn_ids").val();
						jQuery.post(ajaxurl, jQuery(this).sortable("serialize") +"&row_id_images=" +row_ids+"&images_in_row=<?php echo $images_in_row[1]; ?>&param=reorderRows&action=album_gallery_library", function(data)
						{
							jQuery("#ux_album_name").val(0);
						});
					}
				});
				
				jQuery(".sort_table").sortable
				({
					opacity: 0.6,
					cursor: 'move',
					update: function()
					{
						var row_ids = jQuery("#uxHdn_ids").val();
						jQuery.post(ajaxurl, jQuery(this).sortable("serialize")+"&row_id_images=" +row_ids+"&param=reorder_td_controls&action=album_gallery_library", function(data)
						{
							var array_data = jQuery.trim(data).split("_");
							var arr = array_data[0].split(",");
							var new_tr_string = array_data[1];
							jQuery("#uxHdn_ids").val(new_tr_string);
							var new_first_id = arr[0];
							var  tr_id = "";
							for(flag = 0; flag < arr.length - 1; flag++)
							{
								var id = "row_" + arr[flag];
								tr_id = jQuery("#"+id).length;
								if(tr_id == 1)
								{
									jQuery("#"+id).attr("id","row_"+new_first_id);
								}
							}
						});
					}
				});
			}
			else
			{
				jQuery("#view_bank_album").sortable
				({
					opacity: 0.6,
					cursor: 'move',
					update: function()
					{
						jQuery.post(ajaxurl, jQuery(this).sortable("serialize")+"&param=reorderControls&action=album_gallery_library", function(data)
						{
							jQuery("#ux_album_name").val(0);
						});
					}
				});
			}
			
		
		</script>
		<?php
		die();
	}
}
?>
