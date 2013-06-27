<?php
	global $wpdb;
	$url = plugins_url('', __FILE__);
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
?>
		<h3><?php echo $album->album_name;?></h3>
		<div id="view_bank_album_<?php echo $unique_id;?>">
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
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?>">
							<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
					<?php
					}
					else
					{
						?>
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?>">
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
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?> (<?php echo stripcslashes($pic_detail[$flag]->description); ?>)">
							<img style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
						<?php
					}
					else 
					{
						?>
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?> (<?php echo stripcslashes($pic_detail[$flag]->description); ?>)">
							<img style="margin:5px;" src="<?php echo GALLERY_BK_PLUGIN_URL.'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/></a>
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
					classNames:'vlightbox1<?php echo $unique_id;?>',
					descSliding:true,
					enableRightClick:false,
					prefix:'vlb1',
					resizeSpeed:7,
					descSliding:true,
					enableSlideshow:false,
					startZoom:true
				});
			</script>