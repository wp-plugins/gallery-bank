<?php
	global $wpdb;
	$popup = get_option("gallery-bank-info-popup");
	if($popup == "")
	{
		?>
		<ol id="gallery_bank_popup" title="Important First Steps" style="display:none;">
			<li class="add_new_album" id="add_new_album">
				<h4>Add New Album</h4>
				<p>
					Gallery Bank provides you a feature to add elegant and beautiful gallery albums with images and videos.
				</p>
				<a href="http://tech-banker.com/gallery-bank/documentation/" target="_blank" class="button gb_buttons">Read More</a>
			</li>
			<li class="shortcode" id="shortcode">
				<h4>Implement Shortcode</h4>
				<p>
					Gallery Bank have 117 ways to display your galleries.</br> Just try out different
					shortcode on your Wordpress Page or Post.
				</p>
				<a href="http://tech-banker.com/gallery-bank/documentation/frequently-asked-questions-shortcodes-gallery-bank/" target="_blank" class="button gb_buttons">Read More</a>
			</li>
			<li class="Upgrade" id="Upgrade">
				<h4>Upgrade to Pro Version</h4>
				<p>
					Gallery Bank is an one time Investment. To enjoy full features of Gallery Bank,
					 Upgrade to Premium Version Now! Starting at 10£/- only.
				</p>
				<a href="http://tech-banker.com/gallery-bank" target="_blank" class="button gb_buttons">Upgrade Now</a>
			</li>
			<li class="help" id="help">
				<h4>Help to Improve</h4>
				<p>
					Gallery Bank would like to collect anonymous data about features you use to help improve this plugin.
				</p>
				<a href="http://tech-banker.com/forum/gallery-bank-support/" target="_blank" class="button gb_buttons">Read More</a>
			</li>
				<a href="javascript:void(0);" onclick="close_popup()" class="gb_close_popup">Dismiss</a>
		</ol>
		<?php
	}
	$album = $wpdb->get_results
	(
		"SELECT * FROM ".gallery_bank_albums()." order by album_order asc "
	);
	$album_css = $wpdb->get_results
	(
		"SELECT * FROM ".gallery_bank_settings()
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
	div.pp_default .pp_top .pp_middle {
    background-color: #ffffff;
    }
	.pp_pic_holder.pp_default {
	    background-color: #ffffff;
    }
    div.pp_default .pp_content_container .pp_left {
        background-color: #ffffff;
        padding-left: 16px;
    }

    div.pp_default .pp_content_container .pp_right {
        background-color: #ffffff;
        padding-right: 13px;
    }

    div.pp_default .pp_bottom .pp_middle {
        background-color: #ffffff;
    }

    div.pp_default .pp_content, div.light_rounded .pp_content {
        background-color: #ffffff;
    }

    .pp_details {
        background-color: #ffffff;
    }

    .ppt {
        display: none !important;
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
					"SELECT count(album_id) FROM ".gallery_bank_albums()
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
				<a rel="prettyPhoto[gallery]" href="<?php echo GALLERY_BK_PLUGIN_URL . "/assets/images/how-to-setup-short-code.png";?>">How to setup Short-Codes for Gallery Bank into your WordPress Page/Post?</a>
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
											<th style="width:13%"><?php _e( "Title", gallery_bank ); ?></th>
											<th style="width:16%"><?php _e( "Total Images", gallery_bank ); ?></th>
											<th style="width:15%"><?php _e( "Date", gallery_bank ); ?></th>
											<th style="width:14%"><?php _e( "Short-Codes", gallery_bank ); ?></th>
											<th style="width:17%"></th>
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
														<a rel="prettyPhoto[gallery]" href="<?php echo GALLERY_BK_PLUGIN_URL . "/assets/images/how-to-setup-short-code.png";?>">Short Codes</a>
													</td>
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
	<?php
	if($popup == "")
	{
	?>
		jQuery("#gallery_bank_popup").dialog(
		{
			dialogClass: "wp-dialog gallery_bank_popup_box",
			modal: true,
			closeOnEscape: true,
			title: gallery_bank_popup.title,
			width: "auto",
			resizable: true,
			draggable: false,
			create: function ( event, ui ) {
				jQuery( this ).css( "maxWidth", "600px" );
			},
			close: function(event)
			{
				jQuery( "#gallery_bank_popup" ).dialog( "close" );
				jQuery.post(ajaxurl, "param=update_option&action=add_new_album_library", function(data)
				{
				});
			}
			
		});
	<?php
	}
	?>
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
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [5] }]
	});
	jQuery("a[rel^=\"prettyPhoto\"]").prettyPhoto
	({
		animation_speed: 1000, 
		slideshow: 4000, 
		autoplay_slideshow: false,
		opacity: 0.80, 
		show_title: false,
		allow_resize: true
	});
});
function close_popup()
{
	jQuery( "#gallery_bank_popup" ).dialog( "close" );
	jQuery.post(ajaxurl, "param=update_option&action=add_new_album_library", function()
	{
	});
	
}
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
