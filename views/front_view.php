<?php
	global $wpdb;
	$url = plugins_url('', __FILE__);
	$pic_detail = $wpdb->get_results
	( 
		$wpdb->prepare
		(
			"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d",
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
?>
					<div id="view_bank_album_<?php echo $album_id;?>" class="box" style=" margin-top: 10px; padding: 10px;">
						<h3><?php echo $album->album_name;?></h3>
						 
						<?php
						 $row = $album ->images_in_row;
						for ($flag = 0; $flag <count($pic_detail); $flag++)
						{
							if($pic_detail[$flag]->description == "")
							{
								if(($flag % $row ==0) && $flag != 0)
								{
								?>
								<br/>
									<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
										<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
										<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>; height: <?php echo $album->image_height; ?>px; width: <?php echo $album->image_width; ?>px;" /></a>
									</div>
								<?php
								}
								else
								{
									?>
									<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
										<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
										<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>; height: <?php echo $album->image_height; ?>px; width: <?php echo $album->image_width; ?>px;" /></a>
									</div>
									<?php
								}
							}
							else
							{
								if(($flag % $row ==0) && $flag != 0)
								{
								?>
								<br/>
									<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
										<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
										<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>; height: <?php echo $album->image_height; ?>px; width: <?php echo $album->image_width; ?>px;" /></a>
									</div>
								<?php
								}
								else
								{
									?>
									<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
										<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
										<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>; height: <?php echo $album->image_height; ?>px; width: <?php echo $album->image_width; ?>px;" /></a>
									</div>
									<?php
								}
							}
						}
						?>
					</div>
		<?php
		$album = $wpdb->get_row
		(
			$wpdb->prepare
			(
				"SELECT slideshow,slideshow_interval FROM ".gallery_bank_albums()." WHERE album_id = %d",
				$album_id
			)
		);
		$interval = $album->slideshow_interval;
		$slide = $album->slideshow;
		?>
			<script type="text/javascript">
				window.Lightbox = new jQuery().visualLightbox
				({
					<?php
					if($slide == 0)
					{
						?>
						autoPlay:false,
						<?php
					}
					else
					{
						?>
						autoPlay:true,
						<?php
					}
					?>
					classNames:'vlightbox1<?php echo $album_id;?>',
					descSliding:true,
					enableRightClick:false,
					<?php
					if($slide == 0)
					{
						?>
						enableSlideshow:false,
						<?php
					}
					else
					{
						?>
						enableSlideshow:true,
						<?php
					}
					?>
					prefix:'vlb1',
					resizeSpeed:7,
					<?php
					if($slide == 0)
					{
						?>
						slideTime:2,
						<?php
					}
					else
					{
						?>
						slideTime:<?php echo $interval;?>,
						<?php
					}
					?>
					startZoom:true
				});
			</script>