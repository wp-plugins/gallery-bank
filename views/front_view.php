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
	$unique_id = rand(100,10000);
?>
		<h3><?php echo $album->album_name;?></h3>
		<div id="view_bank_album_<?php echo $unique_id;?>">
			<?php
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				if($pic_detail[$flag]->description == "")
				{
					?><div class="imgContainerSingle">
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?>">
						<img  src="<?php echo stripcslashes($pic_detail[$flag]->thumbnail_url); ?>" width="150px" /></a>
						</div>
					<?php
					
				}
				else
				{
					?>
					<div class="imgContainerSingle">
						<a class="vlightbox1<?php echo $unique_id;?>" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo $pic_detail[$flag]->title; ?> (<?php echo $pic_detail[$flag]->description; ?>)">
						<img  src="<?php echo stripcslashes($pic_detail[$flag]->thumbnail_url); ?>" width="150px" /></a>
					</div>
					<?php
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
				
			</script>