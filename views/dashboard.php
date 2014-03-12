<?php
    global $wpdb;
	
	$album = $wpdb->get_results
	(
		$wpdb->prepare
		(
			"SELECT * FROM ".gallery_bank_albums()." order by album_order asc ",""
		)
	);
	$album_css = $wpdb->get_results
	(
		$wpdb->prepare
		(
			"SELECT * FROM ".gallery_bank_settings(),""
		)
	);
	if(count($album_css) != 0)
	{
		$setting_keys= array();
		for($flag=0;$flag<count($album_css);$flag++)
		{
			array_push($setting_keys,$album_css[$flag]->setting_key);
		}
		$index = array_search("cover_thumbnail_width", $setting_keys);
		$cover_thumbnail_width = $album_css[$index]->setting_value;
		
		$index = array_search("cover_thumbnail_height", $setting_keys);
		$cover_thumbnail_height = $album_css[$index]->setting_value;
		
		$index = array_search("cover_thumbnail_opacity", $setting_keys);
		$cover_thumbnail_opacity = $album_css[$index]->setting_value;
		
		$index = array_search("cover_thumbnail_border_size", $setting_keys);
		$cover_thumbnail_border_size = $album_css[$index]->setting_value;
		
		$index = array_search("cover_thumbnail_border_radius", $setting_keys);
		$cover_thumbnail_border_radius = $album_css[$index]->setting_value;
		
		$index = array_search("cover_thumbnail_border_color", $setting_keys);
		$cover_thumbnail_border_color = $album_css[$index]->setting_value;
	}
?>
<!--suppress ALL -->
        <style type="text/css">
	.dynamic_cover_css{
		border:<?php echo $cover_thumbnail_border_size;?>px solid <?php echo $cover_thumbnail_border_color;?> ;
		-moz-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
		-webkit-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
		-khtml-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
		-o-border-radius:<?php echo $cover_thumbnail_border_radius; ?>px;
		border-radius:<?php echo $cover_thumbnail_border_radius;?>px;
		opacity:<?php echo $cover_thumbnail_opacity;?>;
		-moz-opacity:<?php echo $cover_thumbnail_opacity;?>;
		-khtml-opacity:<?php echo $cover_thumbnail_opacity;?>;
	}
	.imgLiquidFill
		{
			width:<?php echo $cover_thumbnail_width;?>px;
			height:<?php echo $cover_thumbnail_height;?>px;
		}
</style>
<div class="fluid-layout">
	<div class="layout-span12">
		<div class="widget-layout">
			<div class="widget-layout-title">
				<h4><?php _e( "Dashboard - Gallery Bank", gallery_bank ); ?></h4>
			</div>
			<div class="widget-layout-body">
				<?php
				$album_count = $wpdb->get_var
				(
					$wpdb->prepare
					(
						"SELECT count(album_id) FROM ".gallery_bank_albums(),""
					)
				);
				if($album_count < 2)
				{
					?>
					<a class="btn btn-info" href="admin.php?page=add_album"><?php _e("Add New Album", gallery_bank);?></a>
					<?php
				}
				?>
				<a class="btn btn-danger" href="#" onclick="delete_all_albums();"><?php _e("Delete All Albums", gallery_bank);?></a>
				<a class="btn btn-danger" href="#" onclick="purge_all_images();"><?php _e("Purge Images & Albums", gallery_bank);?></a>
				<a class="btn btn-danger" href="#" onclick="restore_factory_settings();"><?php _e("Restore Factory Settings", gallery_bank);?></a>
				<div class="separator-doubled"></div>
				<div class="fluid-layout">
					<div class="layout-span12">
						<div class="widget-layout">
							<div class="widget-layout-title">
								<h4><?php _e( "Existing Albums Overview", gallery_bank ); ?></h4>
							</div>
							<div class="widget-layout-body">
								<table class="table table-striped " id="data-table-album">
									<thead>
										<tr>
											<th style="width:24%"><?php _e( "Thumbnail", gallery_bank ); ?></th>
											<th style="width:16%"><?php _e( "Album Title", gallery_bank ); ?></th>
											<th style="width:15%"><?php _e( "Total Images", gallery_bank ); ?></th>
											<th style="width:18%"><?php _e( "Date of Creation", gallery_bank ); ?></th>
											<th style="width:27%"></th>
										</tr>
									</thead>
									<tbody>
										<?php
										for($flag=0; $flag <count($album); $flag++)
										{
											$count_pic = $wpdb->get_var
											(
												$wpdb->prepare
												(
													"SELECT count(".gallery_bank_albums().".album_id) FROM ".gallery_bank_albums()." join ".gallery_bank_pics()." on ".gallery_bank_albums().".album_id =  ".gallery_bank_pics().".album_id where ".gallery_bank_albums().".album_id = %d ",
													$album[$flag]->album_id
												)
											);
											$albumCover = $wpdb->get_row
											(
												$wpdb->prepare
												(
													"SELECT album_cover,thumbnail_url,video FROM ".gallery_bank_pics()." WHERE album_cover=1 and album_id = %d",
													$album[$flag]->album_id
												)
											);
											?>
												<tr>
													<td>
														<a href="admin.php?page=edit_album&album_id=<?php echo $album[$flag]->album_id;?>" title="<?php echo stripcslashes(htmlspecialchars_decode($album[$flag] -> album_name));?>" >
															<div class="imgLiquidFill dynamic_cover_css">
																<?php
																if(count($albumCover) != 0)
																{
																	if($albumCover->album_cover == 0)
																	{
																		?>
																		<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . "/assets/images/album-cover.png"); ?>"  />
																		<?php
																	}
																	else
																	{
																		?> 
																		<img src="<?php echo stripcslashes(GALLERY_BK_ALBUM_THUMB_URL.$albumCover->thumbnail_url); ?>"   />
																		<?php
																	}
																}
																else 
																{
																	?> 
																	<img src="<?php echo stripcslashes(GALLERY_BK_PLUGIN_URL . "/assets/images/album-cover.png"); ?>"   />	
																	<?php
																}
																?>
															</div>
														</a>
													</td>
													<td><?php echo stripcslashes(htmlspecialchars_decode($album[$flag] -> album_name));?></td>
													<td><?php echo $count_pic;?></td>
													<td><?php echo date("d-M-Y", strtotime($album[$flag] -> album_date));?></td>
													<td>
														<ul class="layout-table-controls">
															<li>
																<a href="admin.php?page=edit_album&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Edit Album", gallery_bank ); ?>">
																	<i class="icon-pencil" ></i>
																</a>
															</li>	
															<li>
																<a href="admin.php?page=images_sorting&album_id=<?php echo $album[$flag]->album_id;?>&row=3" class="btn hovertip" data-original-title="<?php _e( "Re-Order Images", gallery_bank ); ?>">
																	<i class="icon-th"></i>
																</a>
															</li>
															<li>
																<a href="admin.php?page=album_preview&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Preview Album", gallery_bank ); ?>">
																	<i class="icon-eye-open"></i>
																</a>
															</li>
															<li>
																<a class="btn hovertip "  style="cursor: pointer;" data-original-title="<?php _e( "Delete Album", gallery_bank)?>" onclick="delete_album(<?php echo $album[$flag]->album_id;?>);" >
																	<i class="icon-trash"></i>
																</a>
															</li>
														</ul>
													</td>
												</tr>
											<?php
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
jQuery(".hovertip").tooltip();
jQuery(document).ready(function() 
{
	jQuery(".imgLiquidFill").imgLiquid({fill:true});
	var oTable = jQuery("#data-table-album").dataTable
	({
		"bJQueryUI": false,
		"bAutoWidth": true,
		"sPaginationType": "full_numbers",
		"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage": 
		{
			"sLengthMenu": "<span>Show entries:</span> _MENU_"
		},
		"aaSorting": [[ 0, "asc" ]],
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [4] }]
	});
});
	
	function delete_album(album_id) 
	{
		var r = confirm("<?php _e( "Are you sure you want to delete this Album?", gallery_bank ); ?>");
		if(r == true)
		{
			//noinspection JSUnresolvedVariable
            jQuery.post(ajaxurl, "album_id="+album_id+"&param=Delete_album&action=add_new_album_library", function()
			{
				var check_page = "<?php echo $_REQUEST["page"]; ?>";
				window.location.href = "admin.php?page="+check_page;
			});
		}
	}
	function delete_all_albums()
	{
		alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
	}
	function restore_factory_settings()
	{
		alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
	}
	function purge_all_images()
	{
		alert("<?php _e( "This feature is only available in Paid Premium Version!", gallery_bank ); ?>");
	}
</script>
