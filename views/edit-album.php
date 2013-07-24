<div class="block well" style="min-height:400px;">
	<div class="navbar">
		<div class="navbar-inner">
			<h5><?php _e( "Edit Album", gallery_bank ); ?></h5>
		</div>
	</div>
	<div class="body" style="margin:10px;">
		<form class="form-horizontal" id="edit_album">
			<a class="btn btn-inverse" href="admin.php?page=gallery_bank">
				<?php _e( "Back to Album Overview", gallery_bank ); ?>
			</a>
			<a class="btn btn-danger" href="#" onclick="delete_album();">
				<?php _e( "Delete Album", gallery_bank ); ?>
			</a>
			<button type="submit" class="btn btn-primary" style="float:right">
				<?php _e( "Update Album", gallery_bank ); ?>
			</button>
			<div class="separator-doubled"></div>
			<div class="message green" id="edit_message" style="display: none;">
				<span>
					<strong><?php _e("Album updated. Kindly wait for the redirect.", gallery_bank); ?></strong>
				</span>
			</div>
			
			<div class="row-fluid">
				<div class="span8">
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php _e( "Album Details", gallery_bank ); ?></h5>
							</div>
						</div>
						<div class="body">
							<div class="control-group">
								<?php
							$album_id = $_GET["album_id"];
							$album = $wpdb->get_row
							(
								$wpdb->prepare
								(
								"SELECT * FROM ".gallery_bank_albums()." where album_id = %d",
								$album_id
								)
							);
							?>
							<div class="cntrl">
								<input type="text" name="title" class="span12" value="<?php echo $album->album_name ;?>" id="title" placeholder="<?php _e( "Enter your Album title here", gallery_bank);?>" />
							</div>
							</div>
							<input type="hidden" id="hidden_album_id" value="<?php echo $album_id;?>" />
							<div class="control-group">
							<?php 
							$content = stripslashes($album->description);
							wp_editor($content, $id = 'ux_edit_description', $prev_id = 'title', $media_buttons = true, $tab_index = 1);
							?>
							</div>
						</div>
					</div>
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php _e( "Upload Images", gallery_bank ); ?></h5>
							</div>
						</div>
						<div class="body">
							<div class="control-group">
								<a class="btn btn-info" id="upload_img_button" href=""><?php _e( "Upload Images to your Album ", gallery_bank ); ?></a>
							</div>
							<div class="control-group">
								<input type="checkbox" id="delete_selected" name="delete_selected" style=" cursor: pointer;"/>
								<a class="btn btn-danger" onclick="delete_selected_images();" style="margin-left: 20px; cursor: pointer;">
									<?php _e( "Delete ", gallery_bank ); ?>
								</a>
							</div>
							<table class="table table-striped" id="edit-album-data-table">
								<tbody>
								<?php
									$pic_detail = $wpdb->get_results
									(
										$wpdb->prepare
										(
											"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d order by sorting_order asc",
											$album_id
										)
									);
									for ($flag = 0; $flag < count($pic_detail); $flag++)
									{
										?>
										<tr>
											<td>
												<div class="block" id="<?php echo $pic_detail[$flag]->pic_id; ?>">
													<div class="block" style="padding:0px 6px 0px 0px; float: left; width: 2%;">
														<input type="checkbox" id="delete_image" name="delete_image" value="<?php echo $pic_detail[$flag]->pic_id; ?>" style="cursor: pointer;"/>
													</div>
													<div class="block" style="width:30%; float:left" >
														<img class="imgHolder" src="<?php echo stripcslashes($pic_detail[$flag]->thumbnail_url); ?>" style="border:3px solid #e5e5e5; cursor: pointer;" width="150px"/></br>
														<a class="imgHolder orange" style="margin-left:20px;" id="del_img" onclick="edit_delete_pic(<?php echo $pic_detail[$flag]->pic_id; ?>);">
															<img style="vertical-align:middle; cursor:pointer" src="<?php echo GALLERY_BK_PLUGIN_URL.'/assets/images/icons/color-16/cross.png'?>" alt="">&nbsp; 
															<span style="vertical-align:middle; cursor:pointer"><?php _e( "Remove Image", gallery_bank);?></span>
														</a>
													</div>
													<div class="block" style="width:66%; float:left;">
														<div class="control-group">
															<input style="width: 100%" class="span12" type="text" id="ux_edit_title_<?php echo $pic_detail[$flag]->pic_id ;?>" value= "<?php echo stripcslashes($pic_detail[$flag]->title) ;?>" placeholder="<?php _e( "Enter your Image Title", gallery_bank);?>" />
														</div>
														<div class="control-group" style="border-bottom: none !important;">
															<textarea style="width: 100%" class="span12" rows="10" id="ux_edit_desc_<?php echo $pic_detail[$flag]->pic_id ;?>" placeholder="<?php _e( "Enter your Image Description", gallery_bank);?>"><?php echo stripcslashes($pic_detail[$flag]->description) ;?></textarea>
														</div>
														<input type="hidden" id="hidden_pic_id_<?php  echo $pic_detail[$flag]->pic_id; ?>" value="<?php echo $pic_detail[$flag]->pic_id ;?>" />
													</div>
												</div>
											</td>
										</tr>
										<?php
									}
								?>
								<div id="edit_media" class="content">
								</div>
								</tbody>
							</table>
						</div>
					</div>
				</div>
				<?php
				$settings_count = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT count(setting_id) FROM ". gallery_bank_settings(),""
						)
					);
					$settings_count_album = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT album_settings FROM ". gallery_bank_settings(). " WHERE album_id = %d",
							$album_id
						)
					);
					$settings_cover = $wpdb->get_var
					(
						$wpdb->prepare
						(
							"SELECT album_cover FROM ". gallery_bank_settings(). " WHERE album_id = %d ",
							$album_id
						)
					);
					if($settings_count > 1)
					{
						$settings_content = $wpdb->get_row
						(
							$wpdb->prepare
							(
								"SELECT * FROM ". gallery_bank_settings(). " WHERE album_id = %d ",
								$album_id
							)
						);
					}
					else 
					{
						$settings_content = $wpdb->get_row
						(
							$wpdb->prepare
							(
								"SELECT * FROM ". gallery_bank_settings(). " WHERE album_id = %d ",
								0
							)
						);
					}
					?>
				<div class="span4">
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php _e( "Album Cover", gallery_bank ); ?></h5>
								<div class="nav pull-right">
									<a class="just-icon" data-toggle="collapse" data-target="#edit_album_cover">
										<i class="font-resize-vertical"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="collapse in" id="edit_album_cover">
							<div class="body">
								<div class="control-group">
									<?php
										if(($settings_content->album_cover == "undefined") || ($settings_content->album_cover == ""))
										{
											?>
											<img id="edit_cover_image" src="<?php echo GALLERY_BK_PLUGIN_URL .'/album-cover.png';?>"/>
											<input type="hidden" id="hdn_album_cover" value="<?php echo $settings_cover; ?>" />
										<?php
										}
										else
										{
											?>
											<img id="edit_cover_image"  width="250px;" src="<?php echo $settings_cover;?>" />
											<input type="hidden" id="hdn_album_cover" value="<?php echo $settings_cover; ?>" />
										<?php
										}
										?>
								</div>
								<div class="control-group">
									<a class="btn btn-info" id="upload_cover_image_button" href=""><?php _e( "Set Cover Image ", gallery_bank ); ?></a>
								</div>
							</div>
						</div>
					</div>
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php _e( "Short Codes For Page/Post", gallery_bank ); ?></h5>
								<div class="nav pull-right">
									<a class="just-icon" data-toggle="collapse" data-target="#edit_shortcode">
										<i class="font-resize-vertical"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="collapse in " id="edit_shortcode">
							<div class="body">
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "All Albums", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank_all_albums]</span>
								</div>
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "Single Album", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank album_id=<?php echo $album_id; ?>]</span>
								</div>
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "Album with Cover", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank_album_cover album_id=<?php echo $album_id; ?>]</span>
								</div>
							</div>
						</div>
					</div>
					<a href="http://gallery-bank.com/" target="_blank"><img style="cursor: pointer;width:95%" src="<?php echo GALLERY_BK_PLUGIN_URL.'/sidebar.png' ?>"/></a>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
	var arr = [];
	var ar = [];
	var array = [];
	var thumb_array = [];
	var cover_array;
	var del_ar = [];
	var array_existing = [];
	var array_new = [];
	var exist_array = [];
	jQuery(document).ready(function()
	{
		
		jQuery('.hovertip').tooltip();
		oTable = jQuery('#edit-album-data-table').dataTable
		({
			"bJQueryUI": false,
			"bAutoWidth": true,
			"sPaginationType": "full_numbers",
			"bStateSave": true,
			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
			"oLanguage": 
			{
				"sLengthMenu": "_MENU_"
			},
			"aaSorting": [[ 0, "desc" ]],
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [0] },{ "bSortable": false, "aTargets": [0] }],
			"bSort": false
		});
		
		
		jQuery(".dataTables_filter").css("margin-top", "24px");
		<?php
		for ($flag = 0; $flag < count($pic_detail); $flag++)
		{	
			?>
			array_existing[<?php echo $flag; ?>] = "<?php  echo $pic_detail[$flag]->pic_id; ?>";
			<?php
		}
		?>
	});
	jQuery(function()
	{  
		jQuery('#delete_selected').click(function()
		{
			var oTable = jQuery("#edit-album-data-table").dataTable();
			var  checkProp = jQuery("#delete_selected").prop("checked");
			jQuery("input:checkbox", oTable.fnGetNodes()).each(function(){
				if(checkProp)
				{
					jQuery(this).attr('checked', 'checked');	
				}
				else
				{
					jQuery(this).removeAttr('checked');
				}
			});
		});
	});
	
	jQuery("#edit_album").validate
	({
		rules: 
		{
			title: "required",
			ux_edit_image_width: 
			{
				required: true,
				digits: true
			},
			ux_edit_image_height: 
			{
				required: true,
				digits: true
			},
			ux_edit_cover_width: 
			{
				required: true,
				digits: true
			},
			ux_edit_cover_height: 
			{
				required: true,
				digits: true
			}
		},
		submitHandler: function(form)
		{
			jQuery('#edit_message').css('display','block');
			var albumId = jQuery('#hidden_album_id').val();
			var title = encodeURIComponent(jQuery('#title').val());
			if (jQuery("#wp-ux_edit_description-wrap").hasClass("tmce-active"))
			{
				var uxeditdescription = encodeURIComponent(tinyMCE.get('ux_edit_description').getContent());
			}
			else
			{
				var uxeditdescription = encodeURIComponent(jQuery('#ux_edit_description').val());
			}
			var count_pic = arr.length;
			jQuery.post(ajaxurl, "id="+arr+"&count_pic="+count_pic+"&param=delete_pic&action=album_gallery_library", function(data)
			{	
			});
			<?php
			for ($flag = 0; $flag < count($pic_detail); $flag++)
			{
			?>
				var picId = <?php  echo $pic_detail[$flag]->pic_id; ?>;
				var edit_title = "";
				if(encodeURIComponent(jQuery("#ux_edit_title_" + picId).val()) != "undefined")
				{
					edit_title = encodeURIComponent(jQuery("#ux_edit_title_" + picId).val());
				}
				var edit_detail = "";
				if(encodeURIComponent(jQuery("#ux_edit_desc_" + picId).val()) != "undefined")
				{
					edit_detail = encodeURIComponent(jQuery("#ux_edit_desc_" + picId).val());
				}
				var id = jQuery("#<?php  echo $pic_detail[$flag]->pic_id; ?>").val();
					
				if(typeof id != 'undefined')
				{
					jQuery.post(ajaxurl,"albumId="+albumId+"&picId="+picId+"&edit_title="+edit_title+"&edit_detail="+edit_detail+"&param=update_pic&action=album_gallery_library", function(data)
					{
					});
				}
			<?php
			}
			?>
			var cover_image = "";
			if(cover_array == "")
			{
				cover_image = jQuery("#hdn_album_cover").val();
			}
			else
			{
				cover_image = cover_array;
			}
			jQuery.post(ajaxurl, jQuery(form).serialize() +"&edit_album_name="+title+"&albumId="+albumId+"&ux_edit_description="+uxeditdescription+"&cover_array="+cover_image+"&param=update_album&action=album_gallery_library", function(data)
			{
				var pics;
				var count = 0;
				if(array.length > 0)
				{
					for(pics = 0; pics < array.length; pics++ )
					{
						var pic_path = array[pics];
						var thumb = thumb_array[pics];
						var pic_title = "";
						if(encodeURIComponent(jQuery("#pic_title_" + ar[pics]).val()) != "undefined")
						{
							
							pic_title = encodeURIComponent(jQuery("#pic_title_" + ar[pics]).val());
						}
						var pic_detail = "";
						if(encodeURIComponent(jQuery("#pic_des_" + ar[pics]).val()) != "undefined")
						{
							pic_detail= encodeURIComponent(jQuery("#pic_des_" + ar[pics]).val());
						}
						jQuery.post(ajaxurl, "album_id="+albumId+"&title="+pic_title+"&detail="+pic_detail+"&path="+pic_path+"&thumb="+thumb+"&param=add_pic&action=album_gallery_library", function(data)
						{
							
							count++;
							if(count == array.length)
							{
								setTimeout(function() 
								{
									jQuery('#edit_message').css('display','none');	
									window.location.href = "admin.php?page=gallery_bank";
								}, 2000);
							}
							
						});
					}
				}
				else
				{
					jQuery('#edit_message').css('display','block');
					setTimeout(function() 
					{
						jQuery('#edit_message').css('display','none');	
						window.location.href = "admin.php?page=gallery_bank";
					}, 3000);
				}
					
			});
		}
	});
	var cover_file_frame;  
	jQuery('#upload_cover_image_button').live('click', function( event ){
		event.preventDefault();
		cover_file_frame = wp.media.frames.cover_file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false
		});
		cover_file_frame.on( 'select', function() {
			var selection = cover_file_frame.state().get('selection');
			selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				jQuery("#edit_cover_image").attr('src', attachment.url);
				jQuery("#edit_cover_image").attr('width','250px');
			
				cover_array = attachment.url;
			});
		});
		cover_file_frame.open();
	});
	
	
	
	var file_frame;
	jQuery('#upload_img_button').live('click', function( event ){
		event.preventDefault();
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
				text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: true
		});
		file_frame.on( 'select', function() {
				var selection = file_frame.state().get('selection');
				selection.map( function( attachment ) {
				attachment = attachment.toJSON();
				var dynamicId = Math.floor((Math.random() * 1000)+1);
				thumb_array.push(attachment.url);
				array.push(attachment.url);
				ar.push(dynamicId);
				var tr = jQuery("<tr></tr>");
				var td = jQuery("<td></td>");
				var main_div = jQuery("<div class=\"block\" id=\""+dynamicId+"\" >");
				var chk_block = jQuery("<div class=\"block\" style=\"padding:6px 6px 0px 0px; width:2%;float:left\">");
				var check = jQuery("<input type=\"checkbox\" value=\""+dynamicId+"\" />");
				chk_block.append(check);
				chk_block.append("</div>");
				td.append(chk_block);
				var block = jQuery("<div class=\"block\" style=\"width:30%;float:left\">");
				var img = jQuery("<img class=\"imgHolder\" style=\"border:2px solid #e5e5e5;margin-top:10px;cursor: pointer;\" id=\"up_img\"/>");
				img.attr('src', attachment.url);
				img.attr('width', '150px');
				block.append(img);
				var del = jQuery("<br/><a class=\"imgHolder orange\" style=\"margin-left: 20px;cursor: pointer;\" id=\"del_img\" onclick=\"delete_pic("+dynamicId+")\"><img style=\"cursor: pointer;vertical-align:middle;\" src=\"<?php echo GALLERY_BK_PLUGIN_URL.'/assets/images/icons/color-16/cross.png'?>\">&nbsp; <span  style=\"cursor: pointer;vertical-align:middle;\"><?php _e("Remove Image",gallery_bank);?></span></a>");
				block.append(del);
				block.append("</div>");
				td.append(block);
				var block1 = jQuery("<div class=\"block\" style=\"width:66%;float:left\">");
				var box = jQuery("<div class=\"control-group\"><input type=\"text\" class=\"span12\" id=\"pic_title_"+dynamicId+"\" placeholder=\"<?php _e( "Enter your Image Title", gallery_bank);?>\" /></div>");
				block1.append(box);
				var text = jQuery("<div class=\"control-group\"><textarea id=\"pic_des_"+dynamicId+"\" rows=\"10\"  placeholder=\"<?php _e( "Enter your Image Description", gallery_bank);?>\"  class=\"span12\"></textarea></div>"); 
				block1.append(text);
				block1.append("</div>");
				main_div.append(chk_block);
				main_div.append(block);
				main_div.append(block1);
				main_div.append("</div>");
				td.append(main_div);
				tr.append(td);
				oTable = jQuery('#edit-album-data-table').dataTable();
				oTable.fnAddData([tr.html()]);
				
				array_new.push(dynamicId);
			});
		});
		file_frame.open();
	});
	function delete_pic(dynamicId)
	{
		
		bootbox.confirm("<?php _e( "Are you sure you want to delete this Image?", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				if(jQuery.inArray(dynamicId, array_new) > -1) {
					var index = parseInt(jQuery.inArray(dynamicId, array_new)) + parseInt(array_existing.length);
					oTable = jQuery('#edit-album-data-table').dataTable();
					oTable.fnDeleteRow(index);
					array.splice(jQuery.inArray(dynamicId, array_new), 1);
					ar.splice(jQuery.inArray(dynamicId, array_new), 1);
					thumb_array.splice(jQuery.inArray(dynamicId, array_new), 1);
					array_new.splice(jQuery.inArray(dynamicId, array_new), 1);
				}
			}
		});
	}
	function edit_delete_pic(pic_id)
	{
		bootbox.confirm("<?php _e("Are you sure you want to delete this Image?", gallery_bank); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				if(jQuery.inArray(pic_id.toString(), array_existing) > -1)
				{
					var index = jQuery.inArray(pic_id.toString(), array_existing);
					oTable = jQuery('#edit-album-data-table').dataTable();
					oTable.fnDeleteRow(index);
					array_existing.splice(index, 1);
					arr.push(pic_id);
				}
			}
		});
	}
	function delete_album()
	{
		bootbox.confirm("<?php _e( "Are you sure you want to delete this Album?", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				var album_id = jQuery('#hidden_album_id').val();
				jQuery.post(ajaxurl, "album_id="+album_id+"&param=delete_album&action=album_gallery_library", function(data)
				{
					window.location.href = "admin.php?page=gallery_bank";
				});
			}
		});
	}
	
	function delete_selected_images()
	{
		bootbox.confirm("<?php _e( "Are you sure you want to delete these Images", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				var oTable = jQuery("#edit-album-data-table").dataTable();
				jQuery("input:checkbox:checked", oTable.fnGetNodes()).each(function()
				{
					var dynamicId = parseInt(this.checked? jQuery(this).val():"");
					jQuery("#" + dynamicId).remove();
					if(jQuery.inArray(dynamicId, array_new) > -1) {
						var index = parseInt(jQuery.inArray(dynamicId, array_new)) + parseInt(array_existing.length);
						oTable.fnDeleteRow(index);
						array.splice(jQuery.inArray(dynamicId, array_new), 1);
						ar.splice(jQuery.inArray(dynamicId, array_new), 1);
						thumb_array.splice(jQuery.inArray(dynamicId, array_new), 1);
						array_new.splice(jQuery.inArray(dynamicId, array_new), 1);
					}
					if(jQuery.inArray(dynamicId.toString(), array_existing) > -1)
					{
						var index = jQuery.inArray(dynamicId.toString(), array_existing);
						oTable.fnDeleteRow(index);
						array_existing.splice(index, 1);
						arr.push(dynamicId);
					}
					jQuery('#delete_selected').removeAttr('checked');
				});
			}
		});
	}
</script>