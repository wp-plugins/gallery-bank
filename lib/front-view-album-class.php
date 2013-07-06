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
			$row = $album ->images_in_row;
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				?>
				<?php
				if($pic_detail[$flag]->description == "")
				{
					if(($flag % $row ==0) && $flag != 0)
					{
					?>
						<br/>
						<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?>">
						<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
					<?php
					}
					else
					{
					?>
						<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?>">
						<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
					<?php
					}
				}
				else
				{
					if(($flag % $row ==0) && $flag != 0)
					{
					?>
						<br/>
						<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?> (<?php echo stripcslashes($pic_detail[$flag]->description); ?>)">
						<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
					<?php
					}
					else 
					{
					?>
						<a class="vlightbox1<?php echo $album_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?> (<?php echo stripcslashes($pic_detail[$flag]->description); ?>)">
						<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
					<?php
					}
				}
				?>
					</a>
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