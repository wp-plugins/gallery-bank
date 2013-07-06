<?php
global $wpdb;
$url = plugins_url('', __FILE__);
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
					<li class="last">
						<a href="#">
							<?php _e( "Albums", gallery_bank ); ?>
						</a>
					</li>
				</ul>
			</div>
			<?php
			$url1 = $_REQUEST['page'];
			$album_count = $wpdb->get_var
			(
				$wpdb->prepare
				(
					"SELECT count(album_id) FROM ".gallery_bank_albums(),""
				)
			);
			if($album_count < 2 )
			{
				?>
					<a href="admin.php?page=add_album" class="events-container-button blue" style="margin-top:20px; margin-left:20px; text-decoration: none;">
						<span><?php _e('Create New Album', gallery_bank); ?></span>
					</a>
				<?php
			}
			?>	
			<div class="section">
				<div class="box">
					<div class="title">
						<?php _e("Albums", gallery_bank); ?>
					</div>
					<div class="content">
						<table class="table table-striped" id="data-table-album">
		 					<thead>
								<tr>
									<th style="width:10%"><?php _e( "Album Name", gallery_bank ); ?></th>
									<th style="width:15%"><?php _e( "Shortcode - Only Pics", gallery_bank ); ?></th>
									<th style="width:25%"><?php _e( "Shortcode - Album Cover with Pics", gallery_bank ); ?></th>
									<th style="width:8%"><?php _e( "Total Pics", gallery_bank ); ?></th>
									<th style="width:7%"><?php _e( "Author", gallery_bank ); ?></th>
									<th style="width:8%"><?php _e( "Date", gallery_bank ); ?></th>
									<th style="width:6%"><?php _e( "Action", gallery_bank ); ?></th>
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
										<td><a href="admin.php?page=view_album&album_id=<?php echo $album[$flag]->album_id;?>"  title="<?php _e( "View Album", gallery_bank);?>" style="text-decoration: none;"><?php echo $album[$flag] -> album_name;?></a></td>
										<td id="view_bank_album">[gallery_bank album_id=<?php echo $album[$flag]->album_id;?>]</td>
										<td id="view_bank_album">[gallery_bank_album_cover album_id=<?php echo $album[$flag]->album_id;?>]</td>
										<td><?php echo $count_pic;?></td>
										<td><?php echo $album[$flag] -> author;?></td>
										<td><?php echo $album[$flag] -> album_date;?></td>
										<td>
											<a data-original-title="<?php _e("Edit Album?", gallery_bank ); ?>" data-placement="top"class="icon-edit hovertip"  href="admin.php?page=edit_album&album_id=<?php echo $album[$flag]->album_id;?>" ></a>
											<a data-original-title="<?php _e("Sort Images?", gallery_bank ); ?>" data-placement="top" class="icon-move hovertip" style="cursor: pointer;"href="admin.php?page=images_sorting&album_id=<?php echo $album[$flag]->album_id;?>" ></a>
											<a data-original-title="<?php _e("Delete Album?", gallery_bank ); ?>" data-placement="top" class="icon-trash hovertip" style="cursor: pointer;" onclick="delete_album(<?php echo $album[$flag]->album_id;?>)"></a>
											
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
		<div id="footer">
			<div class="split">
				<?php echo "&copy; 2013 Gallery-Bank" ; ?>
			</div>
			<div class="split right">
				<?php echo "Powered by " ; ?>
				<a href="#" >
					<?php echo "Gallery Bank!"; ?>
				</a>
			</div>
		</div>
	</div>
</div>
<script>
	jQuery("#gallery_bank").addClass("current");
	oTable = jQuery('#data-table-album').dataTable
	({
		"bJQueryUI": false,
		"bAutoWidth": true,
		"sPaginationType": "full_numbers",
		"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
		"oLanguage":
		{
			"sLengthMenu": "_MENU_"
		},
		"aaSorting": [[ 6, "asc" ]],
		"aoColumnDefs": [{ "bSortable": false, "aTargets": [6] }]
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
					var pic_exist = jQuery.trim(data);
					if(pic_exist == "Pictures Exist")
					{
						bootbox.alert("<?php _e("Error! you are unable to delete this Album until Album has Pictures.", gallery_bank ); ?>");
					}
					else
					{
						var check_page = "<?php echo $_REQUEST['page']; ?>";
						window.location.href = "admin.php?page="+check_page;
					}
				});
			}
		});
	}
</script>