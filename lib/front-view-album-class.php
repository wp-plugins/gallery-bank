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
			<h3><?php echo $album->album_name;?></h3>
			
			<?php
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				if($pic_detail[$flag]->description == "")
				{
					if(($flag % $album->images_in_row ==0) && $flag != 0)
					{
					?>
						<br/>
						<div id="bank_pics_<?php echo $flag; ?>" style="padding:10px; display: inline-block;">
							<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
							<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>;"  width="150px" /></a>
						</div>
					<?php
					}
					else
					{
					?>
						<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
							<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
							<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>;"   width="150px" /></a>
						</div>
					<?php
					}
				}
				else
				{
					if(($flag % $album->images_in_row ==0) && $flag != 0)
					{
					?>
						<br/>
						<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
							<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
							<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>;"  width="150px" /></a>
						</div>
					<?php
					}
					else
					{
					?>
						<div id="bank_pics_<?php echo $flag; ?>" style="padding: 10px; display: inline-block;">
							<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
							<img src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border: <?php echo $album->border_width;?>px solid <?php echo $album->border_color;?>;"  width="150px" /></a>
						</div>
					<?php
					}
				}
			}
			$interval = $album->slideshow_interval;
			$slide = $album->slideshow;
			?>
				<script type="text/javascript">
					var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
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
				<?php
			die();
		}
	}

?>