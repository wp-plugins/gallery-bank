<?php
	global $wpdb;
	$url = plugins_url('', __FILE__);
	$album_id = $_REQUEST["album_id"];
	$pic_detail = $wpdb->get_results
	( 
		$wpdb->prepare
		(
			"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d order by sorting_order",
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
<div id="right">
	
	<div id="breadcrumbs">
				<ul>
					<li class="first"></li>
					<li>
						<a href="admin.php?page=gallery_bank">
							<?php _e( "Gallery Bank", gallery_bank ); ?>
						</a>
					</li>
					<li>
						<a href="admin.php?page=gallery_bank">
							<?php _e( "Albums", gallery_bank ); ?>
						</a>
					</li>
					<li class="last">
						<a href="#">
							<?php _e( "Sorting", gallery_bank ); ?>
						</a>
					</li>
				</ul>
			</div>
			<a href="admin.php?page=gallery_bank"class="events-container-button blue" style="margin-top:10px;text-decoration: none;">
					<span><?php _e('Back to Album', gallery_bank); ?></span>
			</a>
	<h3 style="font-size: 20px;margin: 10px;"><?php echo stripcslashes($album->album_name);?></h3>
	<form id="sort_album" class="form-horizontal" method="post" action="">
		<div id="view_bank_album">
		<?php
		$row = $album ->images_in_row;;
			for ($flag = 0; $flag <count($pic_detail); $flag++)
			{
				if($pic_detail[$flag]->description == "")
				{
					if(($flag % $row ==0) && $flag != 0)
					{
					?>
						<br/>
						
							<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/>
					<?php
					}
					else
					{
						?>
						
							<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/>
						<?php
					}		
				}	
				else
				{
					if(($flag % $row ==0) && $flag != 0)
					{
						?>
						<br/>
						
							<img id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" style="margin:5px;" src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL).'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/>
						<?php
					}
					else 
					{
						?>
						
							<img  id="recordsArray_<?php echo $pic_detail[$flag]->pic_id; ?>" style="margin:5px;" src="<?php echo GALLERY_BK_PLUGIN_URL.'/lib/timthumb.php?src='.stripcslashes($pic_detail[$flag]->pic_path).'&h=150&w=150&zc=1&q=100';?>"/>
						<?php
					}
				}
			}
			?>
	</div>	
	</form>
</div>
	<div id="footer">
			<div class="split">
				<?php _e( "&copy; 2013 Gallery-Bank", gallery_bank ); ?>
			</div>
			<div class="split right">
				<?php _e( "Powered by ", gallery_bank ); ?>
				<a href="#" >
					<?php _e( "Gallery Bank!", gallery_bank ); ?>
				</a>
			</div>
		</div>
	</div>
</div>
<style>
	img:hover
	{
		cursor:move;
	}
	
</style>
<script type="text/javascript">
	
	jQuery(document).ready(function()
	{
		jQuery("#view_bank_album").sortable
		({
			opacity: 0.6,
			cursor: 'move',
			update: function()
			{
				jQuery.post(ajaxurl, jQuery(this).sortable("serialize")+"&param=reorderControls&action=album_gallery_library", function(data)
				{
				
 					
				});
			}
		});
	});
	
</script>