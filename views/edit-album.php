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
					<li>
						<a href="admin.php?page=gallery_bank">
							<?php _e( "Albums", gallery_bank ); ?>
						</a>
					</li>
					<li class="last">
						<a href="#">
							<?php _e( "Edit Album", gallery_bank ); ?>
						</a>
					</li>
				</ul>
			</div>
				<a href="admin.php?page=gallery_bank"class="events-container-button blue" style="margin-top:10px; margin-left:135px; style="text-decoration: none;">
					<span><?php _e('Back to Album', gallery_bank); ?></span>
				</a>
			<div class="message green" id="success_update_album_message" style="display: none;">
				<span>
					<strong><?php _e( "Success! Album has been Updated.", gallery_bank); ?></strong>
				</span>
			</div>
			<div class="message red" id="error_update_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Error! Slide Interval can't be zero.", gallery_bank); ?></strong>
				</span>
			</div>
			<div class="message red" id="error_edit_border_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Error! Border width can't be zero.", gallery_bank); ?></strong>
				</span>
			</div>
			<div id="edit-album"  style="display: block;margin-left: -20px;padding: 10px;">
				<form id="edit_album" class="form-horizontal" method="post" action="">
					<div class="body">
						<div class="box">
							<div class="content">
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
								<div class="row">
									<label>
										<?php _e( "Album Name :", gallery_bank ); ?>
									</label>
									<div class="right">
										<input type="text" class="required span12" name="ux_edit_album_name" id="ux_edit_album_name" value="<?php echo $album->album_name ;?>"/> 
									</div>
								</div>
								<div class="row">
									<label><?php _e( "Description :", gallery_bank ); ?></label>
									<div class="right">
										<?php 
										$content = stripslashes($album->description);
										wp_editor($content, $id = 'ux_edit_description', $prev_id = 'title', $media_buttons = true, $tab_index = 1);
										?>
									</div>
								</div>
								<div class="row">
									<label>
										<?php _e( "Upload Media :", gallery_bank ); ?>
									</label>
									<div class="right">
										<button type="button" id="upload_image_button" class="blue">
											<span>
												<?php _e( "Upload Images", gallery_bank ); ?>
											</span>
										</button>
									</div>
								</div>
								<?php
									$pic_detail = $wpdb->get_results
									(
										$wpdb->prepare
										(
										"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d",
										$album_id
										)
									);
								?>
								<div class="box" style=" margin-left: 20px; margin-top: 10px; padding: 10px">
									<?php
									for ($flag = 0; $flag < count($pic_detail); $flag++)
									{
										?>
										<div id="<?php echo $pic_detail[$flag]->pic_id; ?>"  style="margin-bottom: 30px; border-bottom: solid 1px #e5e5e5; !important">
											<img class="imgHolder" src="<?php echo $pic_detail[$flag]->pic_path; ?>" style="border:3px solid #e5e5e5"; height ="200px" width="200px"/>
											<a class="imgHolder orange" style="margin-left:-190px; margin-top:210px; margin-bottom: 10px; cursor: pointer;"  id="del_img" onclick="edit_delete_pic(<?php echo $pic_detail[$flag]->pic_id; ?>);"><img src="<?php echo GALLERY_BK_PLUGIN_URL.'/images/button-cross.png'?>" alt="">&nbsp; <span><?php _e( "Remove Image", gallery_bank);?></span></a>
											<div class="row" style="margin-left:230px !important;">
												<label>
													<?php _e( "Title :", gallery_bank ); ?>
												</label>
												<div class="right" style="margin-left:80px !important; margin-bottom: 20px;">
													<input type="text" id="ux_edit_title_<?php echo $pic_detail[$flag]->pic_id ;?>" value= " <?php echo $pic_detail[$flag]->title ;?>" />
												</div>
											</div>
											<div class="row" style="margin-left:230px!important;">
												<label>
													<?php _e( "Description :", gallery_bank ); ?>
												</label>
												<div class="right">
													<textarea style="margin-left:-43px !important; width: 106%;" id="ux_edit_desc_<?php echo $pic_detail[$flag]->pic_id ;?>" rows="6"><?php echo $pic_detail[$flag]->description ;?></textarea>
												</div>
												
											</div>
										</br>
											<label><input type="hidden" id="hidden_pic_id_<?php  echo $pic_detail[$flag]->pic_id; ?>" value="<?php echo $pic_detail[$flag]->pic_id ;?>" /></label>
										</div>
										<?php
									}
									?>
									<div id="edit_media" style="margin-bottom: 30px;">
									</div>
								</div>
								<?php
								if($album->number_of_pics == 0)
								{
									?>
									<div class="row" style="border-bottom:!important; margin-top: -61px;">
										<label></label>
										<div class="right">
											<button type="submit" class="blue">
												<span>
													<?php _e( "Submit & Save Changes", gallery_bank ); ?>
												</span>
											</button>
										</div>
									</div>
									<?php
								}
								else
								{
									?>
									<div class="row" style="border-bottom:!important; margin-top: -37px;">
									<label><input type="hidden" id="hidden_album_id" value="<?php echo $album_id;?>" /></label>
									<div class="right" style=" margin-left: 238px;">
										<button type="submit" class="blue">
											<span>
												<?php _e( "Submit & Save Changes", gallery_bank ); ?>
											</span>
										</button>
									</div>
								</div>
								<?php
								}
								?>
							</div>
						</div>	
					</div>
				</form>
			</div>
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
<script type="text/javascript">
	var arr =[];
	var array = [];
	var ar = [];
	jQuery(document).ready(function() 
	{
		jQuery("#ux_edit_border_color").ColorPicker
		({		
			onSubmit: function(hsb, hex, rgb, el) 
			{
				jQuery(el).val( '#' + hex);
				jQuery(el).ColorPickerHide();
			},
			onBeforeShow: function() 
			{
				jQuery(this).ColorPickerSetColor(this.value);
			}
		}).bind('onblur', function()
		{
			jQuery(this).ColorPickerSetColor(this.value);
		});
	});
	jQuery("#edit_album").validate
	({
		rules: 
		{
			ux_album_name: "required",
			ux_description: 
			{
				required: true
			},
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
			ux_edit_border_width:
			{
				required : true,
				digits: true
			},
			ux_edit_border_color:
			{
				required : true
			},
			ux_edit_image_no:
			{
				digits: true
			},
			ux_edit_slide_interval:
			{
				required : true,
				digits: true
			}
		},
		submitHandler: function(form)
		{
			var albumId = jQuery('#hidden_album_id').val();
			var ux_edit_slide = jQuery('#ux_edit_slide').prop("checked");
			var ux_edit_slide_interval = jQuery('#ux_edit_slide_interval').val();
			var ux_edit_image_border = jQuery('#ux_edit_image_border').prop("checked");
			var ux_edit_boder_width = jQuery('#ux_edit_border_width').val();
			if (jQuery("#wp-ux_edit_description-wrap").hasClass("tmce-active"))
			{
				var uxeditdescription = encodeURIComponent(tinyMCE.get('ux_edit_description').getContent());
			}
			else
			{
				var uxeditdescription = encodeURIComponent(jQuery('#ux_edit_description').val());
			}
			if((ux_edit_image_border == true) && (ux_edit_boder_width == 0))
			{
				jQuery('#error_update_album_message').css('display','none');
				jQuery('#success_update_album_message').css('display','none');
				jQuery('#error_edit_border_album_message').css('display','block');
			}
			else if((ux_edit_slide == true) && (ux_edit_slide_interval < 1))
			{
				jQuery('#error_edit_border_album_message').css('display','none');
				jQuery('#success_update_album_message').css('display','none');
				jQuery('#error_update_album_message').css('display','block');
			}
			else
			{
				jQuery.post(ajaxurl, jQuery(form).serialize() + "&param=update_general_settings&action=album_gallery_library", function(data)
				{
					
				});
			}
			var image;
			for(image = 0; image < arr.length; image++ )
			{
				if((ux_edit_image_border == true) && (ux_edit_boder_width == 0))
				{
					jQuery('#error_update_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','none');
					jQuery('#error_edit_border_album_message').css('display','block');
				}
				else if((ux_edit_slide == true) && (ux_edit_slide_interval < 1))
				{
					jQuery('#error_edit_border_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','none');
					jQuery('#error_update_album_message').css('display','block');
				}
				else
				{
					jQuery.post(ajaxurl, "id="+arr[image]+"&param=delete_pic&action=album_gallery_library", function(data)
					{
					});
				}
			}
			if((ux_edit_image_border == true) && (ux_edit_boder_width == 0))
			{
				jQuery('#error_update_album_message').css('display','none');
				jQuery('#success_update_album_message').css('display','none');
				jQuery('#error_edit_border_album_message').css('display','block');
			}
			else if((ux_edit_slide == true) && (ux_edit_slide_interval < 1))
			{
				jQuery('#error_edit_border_album_message').css('display','none');
				jQuery('#success_update_album_message').css('display','none');
				jQuery('#error_update_album_message').css('display','block');
			}
			else
			{
				jQuery.post(ajaxurl, jQuery(form).serialize() +"&albumId="+albumId+"&ux_edit_description="+uxeditdescription+"&param=update_album&action=album_gallery_library", function(data)
				{
					var pics;
					for(pics = 0; pics < array.length; pics++ )
					{
						var pic_path = array[pics];
						var pic_title = jQuery("#pic_title_" + ar[pics]).val();
						var pic_detail= jQuery("#pic_des_" + ar[pics]).val();
						if((ux_edit_image_border == true) && (ux_edit_boder_width == 0))
						{
							jQuery('#error_update_album_message').css('display','none');
							jQuery('#success_update_album_message').css('display','none');
							jQuery('#error_edit_border_album_message').css('display','block');
						}
						else if((ux_edit_slide == true) && (ux_edit_slide_interval < 1))
						{
							jQuery('#error_edit_border_album_message').css('display','none');
							jQuery('#success_update_album_message').css('display','none');
							jQuery('#error_update_album_message').css('display','block');
						}
						else
						{
							jQuery.post(ajaxurl, "albumId="+albumId+"&pic_title="+pic_title+"&pic_detail="+pic_detail+"&pic_path="+pic_path+"&param=add_new_pics&action=album_gallery_library", function(data)
							{
							});
						}
					}
					jQuery('#error_edit_border_album_message').css('display','none');
					jQuery('#error_update_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','block');
					setTimeout(function() 
					{
						jQuery('#success_update_album_message').css('display','none');
						window.location.href = "admin.php?page=gallery_bank";
					}, 2000);
				});
			}
			<?php
			for ($flag = 0; $flag < count($pic_detail); $flag++)
			{
			?>
				var picId = <?php  echo $pic_detail[$flag]->pic_id; ?>;
				var edit_title = jQuery("#ux_edit_title_" + picId).val();
				var edit_detail = jQuery("#ux_edit_desc_" + picId).val();
				if((ux_edit_image_border == true) && (ux_edit_boder_width == 0))
				{
					jQuery('#error_update_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','none');
					jQuery('#error_edit_border_album_message').css('display','block');
				}
				else if((ux_edit_slide == true) && (ux_edit_slide_interval < 1))
				{
					jQuery('#error_edit_border_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','none');
					jQuery('#error_update_album_message').css('display','block');
				}
				else
				{
					jQuery.post(ajaxurl,"albumId="+albumId+"&picId="+picId+"&edit_title="+edit_title+"&edit_detail="+edit_detail+"&param=update_pic&action=album_gallery_library", function(data)
					{
						jQuery('#error_edit_border_album_message').css('display','none');
						jQuery('#error_update_album_message').css('display','none');
						jQuery('#success_update_album_message').css('display','block');
						setTimeout(function() 
						{
							jQuery('#success_update_album_message').css('display','none');
							window.location.href = "admin.php?page=gallery_bank";
						}, 2000);
					});
				}
				
			<?php
			}
			?>
		}
	});
	function delete_pic(dynamicId)
	{
		bootbox.confirm("<?php _e( "Are you sure you want to delete this Picture?", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				jQuery("#" + dynamicId).remove();
				if(ar.indexOf(dynamicId) > -1) {
					var index = ar.indexOf(dynamicId);
					array.splice(index, 1);
					ar.splice(index, 1);
				}
			}
		});
	}
	
	function edit_div_control()
	{
		var border = jQuery("#ux_edit_image_border").prop("checked");
		if(border == true)
		{
			jQuery("#ux_edit_border").css('display','block');
		}
		else
		{
			jQuery("#ux_edit_border").css('display','none');
		}
	}
	function edit_slide_control()
	{
		var ux_slide = jQuery("#ux_edit_slide").prop("checked");
		if(ux_slide == true)
		{
			jQuery("#div_edit_slide_interval").css('display','block');
		}
		else
		{
			jQuery("#div_edit_slide_interval").css('display','none');
		}
	}
			
	function edit_delete_pic(pic_id)
	{
		bootbox.confirm("<?php _e( "Are you sure you want to delete this Picture?", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				arr.push(pic_id);
				jQuery('#' + pic_id ).remove();
			}
		});
	}
	var file_frame;
	jQuery('#upload_image_button').live('click', function( event ){
		
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
				array.push(attachment.url);
				var dynamicId = Math.floor((Math.random() * 1000)+1);
				var div = jQuery("<div class=\"box\" style=\"border-bottom: solid 1px #e5e5e5;\" id=\""+dynamicId+"\">");
				var img = jQuery("<br><img class=\"imgHolder\" id=\"edit_images\" style= \"border:3px solid #e5e5e5;\" />");
				img.attr('src', attachment.url)
				img.attr('width', '200px');
				img.attr('height', '200px');
				img.css('margin-left', '10px !important');
				div.append(img);
				var del = jQuery('<a class="imgHolder orange" style="margin-left:-190px; margin-top:210px; margin-bottom: 10px; cursor: pointer;"  id="img_del" onclick="delete_pic('+dynamicId+')"><img src="<?php echo GALLERY_BK_PLUGIN_URL.'/images/button-cross.png'?>" alt="">&nbsp; <span><?php _e("Remove Image", gallery_bank);?></span></a>');
				div.append(del);
				var box = jQuery("<div class=\"row\" style=\"margin-left:230px !important;\"><label> <?php _e("Title :", gallery_bank);?></label><div class=\"right\" style=\"margin-left:80px !important;\"><input type=\"text\" id=\"pic_title_"+dynamicId+"\"/></div></div>");
				box.css('margin-bottom', '20px');
				div.append(box);
				var text = jQuery("<div class=\"row\" style=\"margin-left:230px!important;\"><label> <?php _e("Description :", gallery_bank);?> </label><div class=\"right\" style=\"margin-left:80px !important;\"><textarea id=\"pic_des_"+dynamicId+"\" rows=\"6\"/></div></div></br>");
				text.css('margin-bottom', '10px');
				div.append(text);
				ar.push(dynamicId);
				div.append('</div>');
				jQuery("#edit_media").append(div);
			});
			wp.media.model.settings.post.id = wp_media_post_id;
		});
		file_frame.open();
	});
	jQuery('a.add_media').on('click', function() {
		wp.media.model.settings.post.id = wp_media_post_id;
	});
</script>
