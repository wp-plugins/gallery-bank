<?php
	global $wpdb;
	$album_id = $_REQUEST["album_id"];
	$albums = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(*) FROM ". gallery_bank_albums() . " WHERE album_id = %d",
			$album_id
		)
	);

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
	$pagination = explode(":", $content[4]);
	if($auto_play[1] == "1")
	{
		$autoplay = true;
		
	}
	else {
		$autoplay = false;
	}
	
	$slide_interval = explode(":", $slideshow_settings[1]);
	
	$pagination_settings = explode(";", $content[4]);
	$pagination = explode(":", $pagination_settings[0]);
	
	$count = 1;
	$pagename = 'temp';
	$fileName = GALLERY_BK_PLUGIN_DIR.'/lib/cache/'.$pagename.".txt";
		
?>
	
	<div class="block well" style="min-height:400px;">
		<div class="navbar">
			<div class="navbar-inner">
				<h5><?php _e( "Preview Album ", gallery_bank ); ?></h5>
			</div>
		</div>
		<div class="body" style="margin:10px;">
			<a class="btn btn-inverse" href="admin.php?page=gallery_bank"><?php _e("Back to Album Overview", gallery_bank); ?></a>
			<div class="separator-doubled"></div>
			<div class="row-fluid">
			<div class="span12">
			<div class="block well">
				<div class="navbar">
					<div class="navbar-inner">
						<h5><?php echo stripcslashes(htmlspecialchars_decode($album->album_name));?></h5>
					</div>
				</div>
				<div id="view_bank_album_<?php echo $unique_id;?>">
			<div class="imgContainerSingle" style="margin:10px;">
			
			<?php
			if($pagination[1] == 1)
			{
			?>
			<table class='table table-striped' id='images_view_data_table_<?php echo $unique_id;?>'>
			<?php
			}
			else {
				?>
				<table style="width:100%;"><tr><td>
				<?php
			}
			
				$default_height = 151 + ($image_border_size_value[1] * 2) . "px;" . "border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1] . ";";
				$default_width = 155 + ($image_border_size_value[1] * 2) . "px;" ."border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1] . ";";
				$custom_height = $image_height[1] + 1 + ($image_border_size_value[1] * 2) . "px;" . "border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1] . ";";
				$custom_width = $image_width[1] + 5 + ($image_border_size_value[1] * 2) . "px;" . "border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1] . ";";
				$radius_for_shutter = "border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1];
			
				for ($flag = 0; $flag <count($pic_detail); $flag++)
				{
					if($pagination[1] == 1)
					{	
						if($count == 1)
						{
						
						?>
							<tr><td>
						<?php
						}
					}
					$css_image_thumbnail = "border:" . $image_border_size_value[1]. " solid " . $border_color[1] . ";border-radius:" . $image_radius_value[1]. ";-moz-border-radius:" . $image_radius_value[1]. ";-webkit-border-radius:" . $image_radius_value[1]. ";-khtml-border-radius:" . $image_radius_value[1]. ";-o-border-radius:" . $image_radius_value[1].";opacity:".$image_opacity[1].";filter:alpha(opacity=".$image_opacity[1] * 100 . ");-ms-filter:'progid:DXImageTransform.Microsoft.Alpha(Opacity=".$image_opacity[1] * 100 . ")';-moz-opacity:" . $image_opacity[1] . ";-khtml-opacity:".$image_opacity[1]. ";";
					if($pic_detail[$flag]->description == "")
					{
						if(($flag % $images_in_row[1] == 0) && $flag != 0)
						{
							?>
							</br>
							<?php
							if($image_content[1] == 1)
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
									<?php
								}
								else {
									?>
									<div class="view da-thumbs" style="float:left;height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
								<?php
								}
							}
							else 
							{
								if($images_in_row[1] == 1)
								{
									?>
										<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									
								}
								else 
								{
									?>
										<div class="view da-thumbs" style="float:left;height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
								}
							}
							
							if($pic_detail[$flag]->check_url == 1)
							{
								if($image_content[1] == 1)
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path );?>" style="margin-left:5px;width: 150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom"></span>
											</div>
										</article>
										</a>
										</div>
									<?php
									
								}
								else 
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path );?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">	
												</span>
											</div>
										</article>
										</a>
										</div>
									<?php
								}
							}
							else
							{
								?>
							<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
							<?php
								if($image_content[1] == 1)
								{
										if(file_exists($fileName)!=false)
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg')).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else 
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										else 
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg');?>" style="margin-left:5px;width: 150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo stripcslashes($pic_detail[$flag]->pic_path );?>" style="margin-left:5px;width: 150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom"></span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
								else 
								{
										if(file_exists($fileName)!=false)
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg')).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										else 
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg'));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else 
											{
												?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">	
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
								}
							}
						}
						else 
						{
							
							if($image_content[1] == 1)
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
									<?php
									
								}
								else {
									
								
									if((($flag + 1) % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
											<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
										<?php
									}
									else 
									{
										?>
											<div class="view da-thumbs" style="float:left;height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
										<?php
									}
								
								}
							}
							else 
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									
								}
								else 
								{
									if((($flag + 1) % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
										<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									}
									else 
									{
										?>
										<div class="view da-thumbs" style="float:left;height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
										
									}
								}
							}
							
							if($pic_detail[$flag]->check_url == 1)
							{
								if($image_content[1] == 1)
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>	
										</div>
									<?php
								}
								else 
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
													
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
							}
							else
							{
								?>
									<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
								<?php
								if($image_content[1] == 1)
								{
										if(file_exists($fileName)!=false)
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg')).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										else 
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg'));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>	
										</div>
									<?php
								}
								else 
								{
									
										if(file_exists($fileName)!=false)
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg')).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										else 
										{
											if($pic_detail[$flag]->video == 1)
											{
												?>
											<img src="<?php echo trim(stripcslashes(GALLERY_BK_PLUGIN_URL . '/assets/images/video.jpg' ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											else
											{
												?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
											}
											
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
													
												</span>
											</div>
										</article>
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
							?>
							</br>
							<?php
							if($image_content[1] == 1)
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
									<?php
									
								}
								else {
									?>
									<div class="view da-thumbs" style="float:left;height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
								<?php
								}
							}
							else 
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									
								}
								else {
									?>
									<div class="view da-thumbs" style="float:left;height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
								<?php
								}
							}
							
							if($pic_detail[$flag]->check_url == 1)
							{
								if($image_content[1] == 1)
								{
									
									?>
									<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
								else 
								{
									
									?>
									<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
										<?php
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
											</article>
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
										<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
									<?php
								}
								else 
								{
									?>
										<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?> (<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->description)); ?>)">
									<?php
								}
								
								if($image_content[1] == 1)
								{
									
									
										if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
								else 
								{
									
									if(file_exists($fileName)!=false)
										{
											?>
											<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										else 
										{
											?>
											<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
											<?php
										}
										?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
											</article>
								</a>
											</div>
									<?php
									
								}
							}
						}
						else
						{
							if($image_content[1] == 1)
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
									<?php
									
								}
								else {
									
								
									if((($flag + 1) % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
											<div class="view da-thumbs" style="height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
										<?php
									}
									else 
									{
										?>
											<div class="view da-thumbs" style="float:left;height:<?php echo $default_height; ?>;width:<?php echo $default_width; ?>;">
										<?php
									}
								
								}
							}
							else 
							{
								if($images_in_row[1] == 1)
								{
									?>
									<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									
								}
								else 
								{
									if((($flag + 1) % $images_in_row[1] == 0) && $flag != 0)
									{
										?>
										<div class="view da-thumbs" style="height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
									}
									else 
									{
										?>
										<div class="view da-thumbs" style="float:left;height:<?php echo $custom_height; ?>;width:<?php echo $custom_width; ?>;">
									<?php
										
									}
								}
							}
							
							if($pic_detail[$flag]->check_url == 1)
							{
								if($image_content[1] == 1)
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
											<?php
											if(file_exists($fileName)!=false)
											{
												?>
												<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											else 
											{
												?>
												<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
								else 
								{
									
									?>
										<a href="<?php echo $pic_detail[$flag]->url;?>" target="_blank">
											<?php
											if(file_exists($fileName)!=false)
											{
												?>
												<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											else 
											{
												?>
												<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
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
										<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>">
									<?php
								}
								else 
								{
									?>
										<a class="titan-lb_<?php echo $unique_id;?>" data-titan-lightbox="on" data-titan-group="gallery" href="<?php echo stripcslashes($pic_detail[$flag]->pic_path); ?>" title="<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?> (<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->description)); ?>)">
									<?php
								}
								if($image_content[1] == 1)
								{
									
									
											if(file_exists($fileName)!=false)
											{
												?>
												<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h=150&w=150&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											else 
											{
												?>
												<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:150px;height:155px;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
								else 
								{
									
									if(file_exists($fileName)!=false)
											{
												?>
												<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.trim(stripcslashes($pic_detail[$flag]->pic_path)).'&h='.$image_height[1].'&w='.$image_width[1].'&zc=1&q=100';?>" style="margin-left:5px;;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											else 
											{
												?>
												<img src="<?php echo trim(stripcslashes($pic_detail[$flag]->pic_path ));?>" style="margin-left:5px;width:<?php echo $image_width[1];?>;height:<?php echo $image_height[1];?>;<?php echo $css_image_thumbnail; ?>" />
												<?php
											}
											?>
										
										<article class="da-animate da-slideFromRight" style="<?php echo $radius_for_shutter; ?>">
											<p <?php if ( $pic_detail[$flag]->title == '' ) { echo 'style="display:none !important;"'; } ?> class="emgfittext">
												<?php echo stripcslashes(htmlspecialchars($pic_detail[$flag]->title)); ?>
											</p>
											<div class="forspan">
												<span class="zoom">
												</span>
											</div>
										</article>
								</a>
										</div>
									<?php
									
								}
							}
						}
					}
					if($pagination[1] == 1)
					{
						if($count == $images_in_row[1])
						{
							?>
							</td></tr>
							<?php
							$count = 1;
						}
						else 
						{
							$count++;	
						}
					}
				}
			if($pagination[1] == 1)
			{
			
			?>
			</table>
			<?php
			}
			else {
				?>
				</td></tr></table>
			<?php
			}
			?>
			</div>
			</div>
			</div>
			</div>
			</div>
		</div>
	</div>	 
		<script type="text/javascript">
		<?php
		if($slide_interval[1] == 0)
		{
			$interval = 10000 * 1000 ;
		}
		else 
		{
			$interval = $slide_interval[1] * 1000;	
		}
		?>
		
			jQuery(document).ready(function() {
				jQuery('.titan-lb_<?php echo $unique_id;?>').lightbox({
					//interval : "<php echo $interval; ?>",
					//autoPlay: "<php echo $autoplay; ?>",
					beforeShow: function(){
						jQuery(".lightbox-skin").css("background","<?php echo $lightbox_inline_bg_color[1]; ?>");
						jQuery(".lightbox-overlay").css("background","<?php echo $litebox_bg_color_with_opacity; ?>");
						jQuery(".lightbox-wrap").css("border-radius","<?php echo $overlay_border_radius[1]; ?>");
						jQuery(".lightbox-wrap").css("-moz-border-radius","<?php echo $overlay_border_radius[1]; ?>");
						jQuery(".lightbox-wrap").css("-webkit-border-radius","<?php echo $overlay_border_radius[1]; ?>");
						jQuery(".lightbox-wrap").css("-khtml-border-radius","<?php echo $overlay_border_radius[1]; ?>");
						jQuery(".lightbox-wrap").css("-o-border-radius","<?php echo $overlay_border_radius[1]; ?>");
						jQuery(".lightbox-wrap").css("border","<?php echo $lightbox_bg_color_value;?>");
					},
					afterShow : function()
					{
						jQuery(".lightbox-title").css("color","<?php echo $lightbox_text_color[1]; ?>");
					}
				});
				
			});
			jQuery(document).ready(function(){
				oTable = jQuery('#images_view_data_table_<?php echo $unique_id;?>').dataTable
				({
					"bJQueryUI": false,
					"bAutoWidth": true,
					"sPaginationType": "full_numbers",
					"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
					"oLanguage": 
					{
						"sLengthMenu": "_MENU_"
					},
					"aaSorting": [[ 0, "desc" ]],
					"aoColumnDefs": [{ "bSortable": false, "aTargets": [0] },{ "bSortable": false, "aTargets": [0] }],
					"bSort": false
				});
			});
			// var $container_<?php echo $unique_id;?> = jQuery('#view_bank_album_<?php echo $unique_id;?>');
				// $container_<?php echo $unique_id;?>.imagesLoaded( function(){
					// $container_<?php echo $unique_id;?>.masonry({
					// itemSelector : '.imgContainerSingle',
					// isAnimated: true,
					// animationOptions: {
						// duration: 750,
						// easing: 'linear',
						// queue: false
						// }
					// });
// 					
				// });
		</script>
		
		