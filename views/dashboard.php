<?php
global $wpdb;
global $current_user;
$current_user = wp_get_current_user();
if (!current_user_can("edit_posts") && ! current_user_can("edit_pages"))
{
	return;
}
else
{
?>	
<div class="block well" style="min-height:400px;">
	<div class="navbar">
		<div class="navbar-inner">
			<h5><?php _e( "Dashboard", gallery_bank ); ?></h5>
		</div>
	</div>
	<ul class="midnav midnav-font">
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
		<li>
			<a href="admin.php?page=add_album" class="hovertip" data-original-title="<?php _e( "Add New Album", gallery_bank ); ?>">
				<img src="<?php echo GALLERY_BK_PLUGIN_URL . '/assets/images/icons/color/photography.png'?>"/>
				<span><?php _e( "Add New Album", gallery_bank ); ?></span>
			</a>
		</li>
		<?php
		}
		?>
		<li>
			<a href="admin.php?page=settings" class="hovertip" data-original-title="<?php _e( "Global Settings", gallery_bank ); ?>">
				<img src="<?php echo GALLERY_BK_PLUGIN_URL . '/assets/images/icons/color/config.png'?>"/>
				<span><?php _e( "Global Settings", gallery_bank ); ?></span>
			</a>
		</li>
		<li>
			<a href="#" class="hovertip" data-original-title="<?php _e( "Delete All Albums", gallery_bank ); ?>" onclick="delete_all_albums();" >
				<img src="<?php echo GALLERY_BK_PLUGIN_URL . '/assets/images/icons/color/busy.png'?>"/>
				<span><?php _e( "Delete All Albums", gallery_bank ); ?></span>
			</a>
		</li>
	</ul>
	<div class="body" style="margin:10px;">
		<div class="separator-doubled"></div>
		<div class="row-fluid">
			<div class="span9">
				<div class="block well">
					<div class="navbar">
						<div class="navbar-inner">
							<h5><?php _e( "Existing Albums Overview", gallery_bank ); ?></h5>
						</div>
					</div>
					<div class="body">
						<div class="table-overflow">
							<table class="table table-striped" id="data-table-album">
								<thead>
									<tr>
										<th><?php _e( "Album Title", gallery_bank ); ?></th>
										<th><?php _e( "Images in Album", gallery_bank ); ?></th>
										<th><?php _e( "Published By", gallery_bank ); ?></th>
										<th><?php _e( "Date of Creation", gallery_bank ); ?></th>
										<th></th>
									</tr>
								</thead>
								<tbody>
								<?php
								$album = $wpdb->get_results
								(
									$wpdb->prepare
									(
										"SELECT * FROM ".gallery_bank_albums(),""
									)
								);
								for($flag=0; $flag < count($album); $flag++)
								{
									$count_pic = $wpdb->get_var
									(
										$wpdb->prepare
										(
											"SELECT count(".gallery_bank_albums().".album_id) FROM ".gallery_bank_albums()." join ".gallery_bank_pics()." on ".gallery_bank_albums().".album_id =  ".gallery_bank_pics().".album_id where ".gallery_bank_albums().".album_id = %d ",
											$album[$flag]->album_id
										)
									);
								?>
									<tr>
										<td><?php echo stripcslashes(htmlspecialchars_decode($album[$flag] -> album_name));?></td>
										<td><?php echo $count_pic;?></td>
										<td><?php echo $album[$flag] -> author;?></td>
										<td><?php echo date("d-M-Y", strtotime($album[$flag] -> album_date));?></td>
										<td>
											<ul class="table-controls">
												<li>
													<a href="admin.php?page=edit_album&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Edit Album", gallery_bank ); ?>">
														<i class="icon-pencil"></i>
													</a>
												</li>
												<li>
													<a href="admin.php?page=images_sorting&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Re-order Images", gallery_bank ); ?>">
														<i class="icon-th"></i>
													</a>
												</li>
												<li>
													<a href="admin.php?page=album_preview&album_id=<?php echo $album[$flag]->album_id;?>" class="btn hovertip" data-original-title="<?php _e( "Preview Album", gallery_bank ); ?>">
														<i class="icon-eye-open"></i>
													</a>
												</li>
												<li>
													<a class="btn hovertip" style="cursor: pointer;" data-original-title="<?php _e( "Delete Album", gallery_bank ); ?>" onclick="delete_album(<?php echo $album[$flag]->album_id;?>)">
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
			<div class="span3">
				<div class="block well">
					<div class="navbar">
						<div class="navbar-inner">
							<h5><?php _e( "Allow Tracking", gallery_bank ); ?></h5>
						</div>
					</div>
					<div class="body">
						<ul class="settings" style="margin-left: 13px;">
							<?php
							$allow_tracking  = get_option('allow_tracking_gb');
							if($allow_tracking == "yes")
							{
							?>
								<input type="checkbox" name="allow-tracking" id="allow-tracking" checked="checked" onchange="allowTracking();" />
							<?php
							}
							else
							{	
							?>
								<input type="checkbox" name="allow-tracking" id="allow-tracking" onchange="allowTracking();" />
							<?php
							}
							?>
							<label style="margin-left: 2px;">Allow tracking of this WordPress installs anonymous data.</label>
							<p>
								To maintain a plugin as big as Gallery Bank, we need to know what we're dealing: what kinds of other plugins our users are using, 
								what themes, etc. Please allow us to track that data from your install. It will not track any user details, so your security and privacy are safe with us.
							</p>
						</ul>
					</div>
				</div>
			</div>	
			<div class="span3" style="float: right;">
				<div class="block well">
					<div class="navbar">
						<div class="navbar-inner">
							<h5><?php _e( "Server Settings", gallery_bank ); ?></h5>
						</div>
					</div>
					<div class="body">
						<ul class="settings" style="margin-left: 13px;">
							<?php
								global $wpdb, $gb;
								// Get MYSQL Version
								$sql_version = $wpdb->get_var("SELECT VERSION() AS version");
								// GET SQL Mode
								$my_sql_info = $wpdb->get_results("SHOW VARIABLES LIKE 'sql_mode'");
								if (is_array($my_sql_info)) $sqlmode = $my_sql_info[0]->Value;
								if (empty($sqlmode)) $sqlmode = __('Not set', 'gallery_bank');
								// Get PHP Safe Mode
								if(ini_get('safemode')) $safemode = __('On', 'gallery_bank');
								else $safemode = __('Off', 'gallery_bank');
								// Get PHP allow_url_fopen
								if(ini_get('allow-url-fopen')) $allowurlfopen = __('On', 'gallery_bank');
								else $allowurlfopen = __('Off', 'gallery_bank');
								// Get PHP Max Upload Size
								if(ini_get('upload_max_filesize')) $upload_maximum = ini_get('upload_max_filesize');
								else $upload_maximum  = __('N/A', 'gallery_bank');
								// Get PHP Output buffer Size
								if(ini_get('pcre.backtrack_limit')) $backtrack_lmt = ini_get('pcre.backtrack_limit');
								else $backtrack_lmt = __('N/A', 'gallery_bank');
								// Get PHP Max Post Size
								if(ini_get('post_max_size')) $post_maximum = ini_get('post_max_size');
								else $post_maximum = __('N/A', 'gallery_bank');
								// Get PHP Max execution time
								if(ini_get('max_execution_time')) $maximum_execute = ini_get('max_execution_time');
								else $maximum_execute = __('N/A', 'gallery_bank');
								// Get PHP Memory Limit
								if(ini_get('memory_limit')) $memory_limit = ini_get('memory_limit');
								else $memory_limit = __('N/A', 'gallery_bank');
								// Get actual memory_get_usage
								if (function_exists('memory_get_usage')) $memory_usage = round(memory_get_usage() / 1024 / 1024, 2) . __(' MByte', 'gallery_bank');
								else $memory_usage = __('N/A', 'gallery_bank');
								// required for EXIF read
								if (is_callable('exif_read_data')) $exif = __('Yes', 'gallery_bank'). " ( V" . substr(phpversion('exif'),0,4) . ")" ;
								else $exif = __('No', 'gallery_bank');
								// required for meta data
								if (is_callable('iptcparse')) $iptc = __('Yes', 'gallery_bank');
								else $iptc = __('No', 'gallery_bank');
								// required for meta data
								if (is_callable('xml_parser_create')) $xml = __('Yes', 'gallery_bank');
								else $xml = __('No', 'gallery_bank');
								
								// Get Read Write permissions
								error_reporting('0');

								$fileName = GALLERY_BK_PLUGIN_DIR."/gallery-uploads/temp.txt";
								$myFile0 = fopen($fileName, 'w');//w indicates you can write text to the file
								fclose($myFile);
								if(file_exists($fileName))
								{
									$WRpermission = __('On', 'gallery_bank');
								}
								else
								{
									$WRpermission = __('Off', 'gallery_bank');
								}

								
							?>
								<li><strong><?php _e('Operating System', 'gallery_bank'); ?> : </strong> <span><?php echo PHP_OS; ?>&nbsp;(<?php echo (PHP_INT_SIZE * 8) ?>&nbsp;Bit)</span></li>
								<li><strong><?php _e('Server', 'gallery_bank'); ?> : </strong> <span><?php echo $_SERVER["SERVER_SOFTWARE"]; ?></span></li>
								<li><strong><?php _e('Read Permissions', 'gallery_bank'); ?> : </strong> <span><?php echo $WRpermission; ?></span></li>
								<li><strong><?php _e('Write Permissions', 'gallery_bank'); ?> : </strong> <span><?php echo $WRpermission; ?></span></li>											
								<li><strong><?php _e('Memory usage', 'gallery_bank'); ?> :</strong> <span><?php echo $memory_usage; ?></span></li>
								<li><strong><?php _e('MYSQL Version', 'gallery_bank'); ?> : </strong> <span><?php echo $sql_version; ?></span></li>
								<li><strong><?php _e('SQL Mode', 'gallery_bank'); ?> : </strong> <span><?php echo $sqlmode; ?></span></li>
								<li><strong><?php _e('PHP Version', 'gallery_bank'); ?> : </strong> <span><?php echo PHP_VERSION; ?></span></li>
								<li><strong><?php _e('PHP Safe Mode', 'gallery_bank'); ?> : </strong> <span><?php echo $safemode; ?></span></li>
								<li><strong><?php _e('PHP Allow URL fopen', 'gallery_bank'); ?> : </strong> <span><?php echo $allowurlfopen; ?></span></li>
								<li><strong><?php _e('PHP Memory Limit', 'gallery_bank'); ?> : </strong> <span><?php echo $memory_limit; ?></span></li>
								<li><strong><?php _e('PHP Max Upload Size', 'gallery_bank'); ?> : </strong> <span><?php echo $upload_maximum; ?></span></li>
								<li><strong><?php _e('PHP Max Post Size', 'gallery_bank'); ?> : </strong> <span><?php echo $post_maximum; ?></span></li>
								<li><strong><?php _e('PCRE Backtracking Limit', 'gallery_bank'); ?> : </strong> <span><?php echo $backtrack_lmt; ?></span></li>
								<li><strong><?php _e('PHP Max Script Execute Time', 'gallery_bank'); ?> : </strong> <span><?php echo $maximum_execute; ?>s</span></li>
								<li><strong><?php _e('PHP Exif support', 'gallery_bank'); ?> : </strong> <span><?php echo $exif; ?></span></li>
								<li><strong><?php _e('PHP IPTC support', 'gallery_bank'); ?> : </strong> <span><?php echo $iptc; ?></span></li>
								<li><strong><?php _e('PHP XML support', 'gallery_bank'); ?> : </strong> <span><?php echo $xml; ?></span></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	oTable = jQuery('#data-table-album').dataTable
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
	function delete_album(album_id) 
	{
			bootbox.confirm("<?php _e( "Are you sure you want to delete this Album?", gallery_bank ); ?>", function(confirmed)
			{
				console.log("Confirmed: "+confirmed);
				if(confirmed == true)
				{
					jQuery.post(ajaxurl, "album_id="+album_id+"&param=Delete_album&action=album_gallery_library", function(data)
					{
						
							var check_page = "<?php echo intval($_REQUEST['page']); ?>";
							window.location.href = "admin.php?page="+check_page;
						
					});
				}
			});
	}
	function delete_all_albums()
	{
		bootbox.confirm("<?php _e("Are you sure you want to delete all Albums?", gallery_bank ); ?>", function(confirmed) 
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				jQuery.post(ajaxurl, "&param=delete_all_albums&action=album_gallery_library", function(data)
				{
					var check_page = "<?php echo intval($_REQUEST['page']); ?>";
					window.location.href = "admin.php?page="+check_page;
				});
				
			}
		});
	}
	function allowTracking()
	{
		var checkProp = jQuery("#allow-tracking").prop("checked");
		
		if(checkProp == true)
		{
			<?php
				update_option( 'allow_tracking_gb', 'yes' );
			?>
		}
		else
		{
			<?php
				update_option( 'allow_tracking_gb', 'no' );
			?>
		}
	}
	
</script>
<?php
}
?>