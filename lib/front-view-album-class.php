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
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				?>
				<div class="imgContainerSingle">
				<?php
				if($pic_detail[$flag]->description == "")
				{
					?>
							
							<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
							<img src="<?php echo $pic_detail[$flag]->pic_path; ?>" width="150px" /></a>
						
					<?php
				}
				else
				{
				?>
					
						<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
						<img src="<?php echo $pic_detail[$flag]->pic_path; ?>" width="150px" /></a>
					
				<?php
				}
				?>
					</a>
					</div>
				<?php
				}
			?>
			<script>
					window.Lightbox = new jQuery().visualLightbox
				({
					classNames:'vlightbox1<?php echo $album_id;?>',
					descSliding:true,
					enableRightClick:false,
					enableSlideshow:false,
					prefix:'vlb1',
					resizeSpeed:7,
					startZoom:true
				});
			</script>
			<?php
			die();
		}
		elseif($_REQUEST["param"] == "get_album_name")
		{
			$album_id = $_REQUEST['album_id'];
			echo $album_name = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT album_name from ".gallery_bank_albums()." where album_id = %d",
					$album_id
				)
			);
			die();
		}
	}

?>