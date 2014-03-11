<?php
	global $wpdb;
	$albums = $wpdb->get_var
	(
		$wpdb->prepare
		(
			"SELECT count(*) FROM ". gallery_bank_albums(), ""
		)
	);
	if($albums > 0)
	{
	$album = $wpdb->get_results
	(
		$wpdb->prepare
		(
			"SELECT * FROM ".gallery_bank_albums(),""
		)
	);
	$unique_id = rand(100,10000);
?>
<button id="back_button<?php echo $unique_id;?>" style="margin-top:10px; display: none;" onclick="view_albums<?php echo $unique_id;?>();">
	<span style="color: #000;">&laquo <?php _e('Back to Albums', gallery_bank); ?></span>
</button>
<table style="width:100%;border:0px;" >
<?php
	for($flag = 0; $flag < count($album); $flag++)
	{
		$get_settings = $wpdb->get_row
		(
			$wpdb->prepare
			(
				"SELECT * FROM ". gallery_bank_settings(). " WHERE album_id = %d ",
				$album[$flag]->album_id
			)
		);
		if($get_settings->album_settings == 1)
		{
			$sett_value = $wpdb->get_row
			(
				$wpdb->prepare
				(
					"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id = %d",
					$get_settings->album_settings,
					0
				)
			);
		}
		else
		{
			$sett_value = $wpdb->get_row
			(
				$wpdb->prepare
				(
					"SELECT * FROM ". gallery_bank_settings(). " WHERE album_settings = %d and album_id =%d",
					$get_settings->album_settings,
					$album[$flag]->album_id
				)
			);
		}
		$content = explode("/", $sett_value->setting_content);
		$cover_settings = explode(";", $content[1]);
		$cover_content = explode(":", $cover_settings[0]);
		$cover_width = explode(":", $cover_settings[1]);
		$cover_height = explode(":", $cover_settings[2]);
		$cover_opacity = explode(":", $cover_settings[3]);
		$cover_border_size = explode(":", $cover_settings[4]);
		$cover_border_radius = explode(":", $cover_settings[5]);
		$cover_border_color = explode(":", $cover_settings[6]);
		$cover_border_value = $cover_border_size[1] ." solid " . $cover_border_color[1];
		$filter_cover_opacity = $cover_opacity[1]* 100;
		?>
		<style>
			.dynamic_cover_css
			{
				border:<?php echo $cover_border_value;?>;
				-moz-border-radius:<?php echo $cover_border_radius[1]; ?>;
				-webkit-border-radius:<?php echo $cover_border_radius[1]; ?>;
				-khtml-border-radius:<?php echo $cover_border_radius[1]; ?>;
				-o-border-radius:<?php echo $cover_border_radius[1]; ?>;
				border-radius:<?php echo $cover_border_radius[1];?>;
				-moz-opacity:<?php echo $cover_opacity[1];?>;
				-khtml-opacity:<?php echo $cover_opacity[1];?>;
				-ms-filter: 'progid:DXImageTransform.Microsoft.Alpha(Opacity=<?php echo $filter_cover_opacity; ?>)';
				filter:alpha(opacity=<?php echo $filter_cover_opacity; ?>);
				opacity:<?php echo $cover_opacity[1]; ?>;
				cursor:pointer;
				margin-left:5px;
				margin-top:3px;
				width:150px;
			}
			.cover_div_css
			{
				text-align: justify;
				display:inline-block;
				vertical-align:top;
				margin-left:20px;
			}
		</style>
		<tr id="tr_<?php echo $album[$flag]->album_id;?>">
			<td style="border:0px;">
				<?php
				if(($get_settings->album_cover == "undefined") || ($get_settings->album_cover == "") )
				{
					$url = GALLERY_BK_PLUGIN_URL."/album-cover.png";
				}
				else
				{
					$url = $get_settings->album_cover;
				}
				?>
				<div id="main_div<?php echo $unique_id;?>" style="display: block;" class="album-cover">
					<a onclick="view_images<?php echo $unique_id;?>(<?php echo $album[$flag]->album_id;?>);" style="cursor: pointer" >
						<img class="imgHolder dynamic_cover_css" src="<?php echo stripcslashes($url); ?>" />
					</a>
					<div class="cover_div_css">
						<h3><?php echo stripcslashes(htmlspecialchars_decode($album[$flag]->album_name)); ?>&nbsp;</h3>
						<span><?php echo stripcslashes(htmlspecialchars_decode($album[$flag]->description));?>&nbsp;</span><br/>
						<a style="cursor: pointer;" onclick="view_images<?php echo $unique_id;?>(<?php echo $album[$flag]->album_id;?>)">
							<?php _e("See Album images", gallery_bank ); ?> &raquo
						</a>
					</div>
				</div>
			</td>
		</tr>
	<?php
	}
?>
</table>
<div id="image_show_div<?php echo $unique_id;?>" style="display: none;" class="images-cover">
	<h3 id="album_title<?php echo $unique_id;?>"></h3>
	<div id="show_images_<?php echo $unique_id;?>" >
	</div>
</div>
<script type="text/javascript">
	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
	function view_images<?php echo $unique_id;?>(album_id)
	{
		jQuery(".album-cover").css('display','none');
		jQuery("#main_div<?php echo $unique_id;?>").css('display','none');
		jQuery("#back_button<?php echo $unique_id;?>").css('display','none');
		jQuery("#image_show_div<?php echo $unique_id;?>").css('display','block');
		<?php
			for($flag = 0; $flag < count($album); $flag++)
			{
				?>
				if("<?php echo $flag; ?>" == 0)
				{
					jQuery("#tr_"+<?php echo $album[$flag]->album_id; ?>).css('display','block');
				}
				else
				{
					jQuery("#tr_"+<?php echo $album[$flag]->album_id; ?>).css('display','none');
				}
				<?php
			}
		?>
		jQuery.post(ajaxurl, "album_id="+album_id+"&param=show_images&action=front_albums_gallery_library", function(data)
		{
			jQuery("#back_button<?php echo $unique_id;?>").css('display','block');
			jQuery('#show_images_<?php echo $unique_id;?>').html(data);
		});
		jQuery.post(ajaxurl, "album_id="+album_id+"&param=get_album_name&action=front_albums_gallery_library", function(data)
		{
			jQuery("#album_title<?php echo $unique_id;?>").html(data);
		});
	}
	function view_albums<?php echo $unique_id;?>()
	{
		jQuery(".album-cover").css('display','block');
		jQuery(".images-cover").css('display','none');
		jQuery("#back_button<?php echo $unique_id;?>").css('display','none');
		<?php
			for($flag = 0; $flag < count($album); $flag++)
			{
				?>
				jQuery("#tr_"+<?php echo $album[$flag]->album_id; ?>).css('display','block');
				<?php
			}
		?>
	}
	
</script>
<?php
}
?>