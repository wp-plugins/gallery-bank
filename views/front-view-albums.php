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
	$album_cover = $wpdb->get_row
	(
		$wpdb->prepare
		(
			"SELECT * FROM ".gallery_bank_pics()." where album_cover = %s and album_id = %d",
			"1",
			$album_id
		)
	);
	
?>
<button id="back_button" style="margin-top:10px; display: none;" onclick="view_albums();">
	<span style="color: #000;">&laquo <?php _e('Back to Albums', gallery_bank); ?></span>
</button>
<div id="main_div<?php echo $album_id;?>" style="display: block;" class="album-cover">
	<div class="box" style=" margin-top: 10px;">
		<div id="view_bank_album_<?php echo $album_id;?>" style=" margin-top: 10px; padding: 10px;" > 
			<img class="imgHolder" src="<?php echo $album_cover->thumbnail_url; ?>" onclick="view_images(<?php echo $album_id;?>);" style="display:inline-block;border:5px solid #000; cursor:pointer;" width="150px" />
			<div style="text-align: justify;display:inline-block;vertical-align:middle;margin-left:20px;">
				<div>
					<h3><?php echo $album->album_name; ?>&nbsp;</h3>
				</div>
				<div>
					<span><?php echo $album->description;?>&nbsp;</span>
				</div>
				<div>
					<a style="cursor: pointer;" onclick="view_images(<?php echo $album_id;?>)">
						<?php _e("See Album images", gallery_bank ); ?> &raquo
					</a>
				</div>
			</div>	
		</div>
	</div>
</div>
<div id="image_show_div<?php echo $album_id;?>" style="display: none;" class="images-cover">
	<div id="show_images_<?php echo $album_id;?>" class="box" style=" margin-top: 10px;padding:10px;">
	</div>
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
			function view_images(album_id)
			{
				jQuery(".album-cover").css('display','none');
				jQuery("#main_div"+album_id).css('display','none');
				jQuery("#back_button").css('display','none');
				jQuery("#image_show_div"+album_id).css('display','block');
				jQuery.post(ajaxurl, "album_id="+album_id+"&param=show_images&action=front_albums_gallery_library", function(data)
				{
					jQuery("#back_button").css('display','block');
					jQuery("#main_div"+album_id).css('display','none');
					jQuery("#show_images_" + album_id).html(data);	
				});
			}
			function view_albums()
			{
				jQuery(".album-cover").css('display','block');
				jQuery(".images-cover").css('display','none');
				jQuery("#back_button").css('display','none');
			}
		</script>