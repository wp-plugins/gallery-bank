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
	$album_cover_count = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(album_id) FROM ".gallery_bank_pics()." where album_cover = %s and album_id = %d",
			"1",
			$album_id
		)
	);
	$unique_id = rand(100,10000);
	
?>
<button id="back_button" style="margin-top:10px; display: none;" onclick="view_albums();">
	<span style="color: #000;">&laquo <?php _e('Back to Albums', gallery_bank); ?></span>
</button>
<?php
if($album_cover_count == 0)
{
	$url = GALLERY_BK_PLUGIN_URL."/images/timthumb.jpg";
	?>
		<div id="main_div<?php echo $unique_id;?>" style="display: block;" class="album-cover">
		<img class="imgHolder" src="<?php echo stripcslashes($url); ?>" onclick="view_images(<?php echo $album_id;?>);" style="display:inline-block;border:5px solid #000; cursor:pointer;" width="150px" />
			<div style="text-align: justify;display:inline-block;vertical-align:top;margin-left:20px;">
				<h3><?php echo $album->album_name; ?>&nbsp;</h3>
				<span><?php echo $album->description;?>&nbsp;</span><br/>
				<a style="cursor: pointer;" onclick="view_images(<?php echo $album_id;?>)">
					<?php _e("See Album images", gallery_bank ); ?> &raquo
				</a>
			</div>
		</div>	
	<?php
}
else 
{
	?>
	<div id="main_div<?php echo $unique_id;?>" style="display: block;" class="album-cover">
		<img class="imgHolder" src="<?php echo stripcslashes($album_cover->thumbnail_url); ?>" onclick="view_images(<?php echo $album_id;?>);" style="display:inline-block;border:5px solid #000; cursor:pointer;" width="150px" />
			<div style="text-align: justify;display:inline-block;vertical-align:top;margin-left:20px;">
				<h3><?php echo $album->album_name; ?>&nbsp;</h3>
				<span><?php echo $album->description;?>&nbsp;</span><br/>
				<a style="cursor: pointer;" onclick="view_images(<?php echo $album_id;?>)">
					<?php _e("See Album images", gallery_bank ); ?> &raquo
				</a>
			</div>
		</div>	
	<?php
}
?>

<div id="image_show_div<?php echo $unique_id;?>" style="display: none;" class="images-cover">
	<h3><?php echo $album->album_name;?></h3>
	<div id="show_images_<?php echo $unique_id;?>" >
	</div>
</div>
			<script type="text/javascript">
				var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
				function view_images(album_id)
				{
					
					jQuery(".album-cover").css('display','none');
					jQuery("#main_div<?php echo $unique_id;?>").css('display','none');
					jQuery("#back_button").css('display','none');
					jQuery("#image_show_div<?php echo $unique_id;?>").css('display','block');
					jQuery.post(ajaxurl, "album_id="+album_id+"&param=show_images&action=front_albums_gallery_library", function(data)
					{
						jQuery("#back_button").css('display','block');
						
						jQuery('#show_images_<?php echo $unique_id;?>').html(data);
						var $container_<?php echo $unique_id;?> = jQuery('#show_images_<?php echo $unique_id;?>');
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
					});
				}
				function view_albums()
				{
					jQuery(".album-cover").css('display','block');
					jQuery(".images-cover").css('display','none');
					jQuery("#back_button").css('display','none');
				}
		
			
				
			</script>