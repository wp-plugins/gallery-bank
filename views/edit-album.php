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
				<a href="admin.php?page=gallery_bank"class="events-container-button blue" style="margin-top:10px;text-decoration: none;">
					<span><?php _e('Back to Album', gallery_bank); ?></span>
				</a>
			<div class="message green" id="success_update_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Success! We are in the middle of updating the data. Kindly wait for the redirect.", gallery_bank); ?></strong>
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
			<div id="edit-album"  style="display: block;padding:10px 10px 10px 0px;">
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
										<input type="text" class="required span12" name="ux_edit_album_name" id="ux_edit_album_name" value="<?php echo stripcslashes($album->album_name) ;?>"/> 
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
								<div class="row" >
									<img src="<?php echo GALLERY_BK_PLUGIN_URL."/images/screenshot.png";?>"/>
								</div>
								<div class="row">
									<label>
										<?php _e( "Upload Images :", gallery_bank ); ?>
									</label>
									<div class="right">
										<button type="button" id="upload_image_button" class="blue">
											<span>
												<?php _e( "Browse Here", gallery_bank ); ?>
											</span>
										</button>
									</div>
								</div>
								<?php
									$pic_detail = $wpdb->get_results
									(
										$wpdb->prepare
										(
										"SELECT * FROM ". gallery_bank_pics(). " WHERE album_id = %d order by sorting_order asc",
										$album_id
										)
									);
									$cover = $wpdb->get_var
									(
										$wpdb->prepare
										(
										"SELECT pic_id FROM ". gallery_bank_pics(). " WHERE album_id = %d and album_cover = %d",
										$album_id,
										1
										)
									);
								?>
							</div>
						</div>
						<?php
							for ($flag = 0; $flag < count($pic_detail); $flag++)
							{
								?>
								<div class="box">
									<div class="content" style="margin-left:20px; border-bottom: solid 1px #e5e5e5;" id="<?php echo $pic_detail[$flag]->pic_id; ?>"  style="border-bottom: solid 1px #e5e5e5; !important">
										<div style="float:left;width:170px;">
											<img class="imgHolder" src="<?php echo $pic_detail[$flag]->thumbnail_url; ?>" style="border:3px solid #e5e5e5"; width="150px"/>
											<input type="radio" style="cursor: pointer; margin-bottom: 10px;" id="edit_cover_<?php echo $pic_detail[$flag]->pic_id ;?>" name="edit_album_cover"/><?php _e(" Set Image as Album Cover",gallery_bank);?>
											<script>
												var id = "<?php echo $cover; ?>";
												jQuery("#edit_cover_" + id).attr('checked',true);
												
											</script>
											<a class="imgHolder orange" style="margin-left:20px;"  id="del_img" onclick="edit_delete_pic(<?php echo $pic_detail[$flag]->pic_id; ?>);">
												<img style="vertical-align:middle;cursor:pointer" src="<?php echo GALLERY_BK_PLUGIN_URL.'/images/button-cross.png'?>" alt="">&nbsp; 
												<span style="vertical-align:middle;cursor:pointer"><?php _e( "Remove Image", gallery_bank);?></span>
											</a>
										</div>
										<div class="row" style="margin-left:180px!important;margin-top:10px; margin-bottom: 10px;">
											<label>
												<?php _e( "Title :", gallery_bank ); ?>
											</label>
											<div class="right" >
												<input type="text" id="ux_edit_title_<?php echo $pic_detail[$flag]->pic_id ;?>" value= "<?php echo stripcslashes($pic_detail[$flag]->title) ;?>" />
											</div>
										</div>
										<div class="row" style="margin-left:180px!important;border-bottom:none !important; margin-bottom: 20px;">
											<label>
												<?php _e( "Description :", gallery_bank ); ?>
											</label>
											<div class="right">
												<textarea rows="10" id="ux_edit_desc_<?php echo $pic_detail[$flag]->pic_id ;?>" ><?php echo stripcslashes($pic_detail[$flag]->description) ;?></textarea>
											</div>
											
										</div>
										<input type="hidden" id="hidden_pic_id_<?php  echo $pic_detail[$flag]->pic_id; ?>" value="<?php echo $pic_detail[$flag]->pic_id ;?>" />
									</div>
								</div>
								<?php
							}
							?>
							<div class="box">
								<div id="edit_media" class="content" style="margin-left:20px;">
								</div>
							</div>
							<div class="box">
								<div class="content">
									<div class="row">
										<input type="hidden" id="hidden_album_id" value="<?php echo $album_id;?>" />
										<button type="submit" class="blue">
											<span>
											<?php _e( "Submit & Save Changes", gallery_bank ); ?>
											</span>
										</button>
									</div>
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
jQuery("#gallery_bank").addClass("current");
	var arr =[];
	var array = [];
	var ar = [];
	var thumb_array = [];
	jQuery("#edit_album").validate
	({
		rules: 
		{
			ux_edit_album_name: "required",
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
			ux_edit_image_no:
			{
				digits: true
			}
		},
		submitHandler: function(form)
		{
			jQuery('#success_update_album_message').css('display','block');
			jQuery('body,html').animate({
			scrollTop: jQuery('body,html').position().top}, 'slow');
			var albumId = jQuery('#hidden_album_id').val();
			if (jQuery("#wp-ux_edit_description-wrap").hasClass("tmce-active"))
			{
				var uxeditdescription = encodeURIComponent(tinyMCE.get('ux_edit_description').getContent());
			}
			else
			{
				var uxeditdescription = encodeURIComponent(jQuery('#ux_edit_description').val());
			}
			var count_pic = arr.length;
			var edit_album_name = encodeURIComponent(jQuery('#ux_edit_album_name').val());
			jQuery.post(ajaxurl, "id="+arr+"&count_pic="+count_pic+"&param=delete_pic&action=album_gallery_library", function(data)
			{	
			});
			<?php
			for ($flag = 0; $flag < count($pic_detail); $flag++)
			{
			?>
				var picId = <?php  echo $pic_detail[$flag]->pic_id; ?>;
				var edit_title = encodeURIComponent(jQuery("#ux_edit_title_" + picId).val());
				var edit_detail = encodeURIComponent(jQuery("#ux_edit_desc_" + picId).val());
				var edit_cover = jQuery('input:radio[id=edit_cover_' + picId+']:checked').val();
				if(edit_cover == "on")
				{
					edit_cover = 1;
				}
				else
				{
					edit_cover = 0;
				}
				jQuery.post(ajaxurl,"albumId="+albumId+"&picId="+picId+"&edit_title="+edit_title+"&edit_detail="+edit_detail+"&edit_cover="+edit_cover+"&param=update_pic&action=album_gallery_library", function(data)
				{
				});
			<?php
			}
			?>
			jQuery.post(ajaxurl, jQuery(form).serialize() +"&albumId="+albumId+"&edit_album_name="+edit_album_name+"&ux_edit_description="+uxeditdescription+"&param=update_album&action=album_gallery_library", function(data)
			{
				
				var pics;
				var count = 0;
				if(array.length > 0)
				{
					for(pics = 0; pics < array.length; pics++ )
					{
						var pic_path = array[pics];
						var thumb = thumb_array[pics];
						var pic_title = encodeURIComponent(jQuery("#pic_title_" + ar[pics]).val());
						var pic_detail= encodeURIComponent(jQuery("#pic_des_" + ar[pics]).val());
						var cover_pic = jQuery('input:radio[id=cover_pic_'+ar[pics]+']:checked').val();
						if(cover_pic == undefined)
						{
							cover_pic = 0;
						}
						else
						{
							cover_pic = 1;
						}
						jQuery.post(ajaxurl, "album_id="+albumId+"&title="+pic_title+"&detail="+pic_detail+"&alb_cover="+cover_pic+"&path="+pic_path+"&thumb="+thumb+"&param=add_pic&action=album_gallery_library", function(data)
						{
							jQuery('#error_edit_border_album_message').css('display','none');
							jQuery('#error_update_album_message').css('display','none');
							count++;
							if(count == array.length)
							{
								setTimeout(function() 
								{
									jQuery('#success_update_album_message').css('display','none');	
									window.location.href = "admin.php?page=gallery_bank";
								}, 3000);
							}
						});
					}	
				}
				else
				{
					jQuery('#error_edit_border_album_message').css('display','none');
					jQuery('#error_update_album_message').css('display','none');
					jQuery('#success_update_album_message').css('display','block');
					jQuery('body,html').animate({
					scrollTop: jQuery('body,html').position().top}, 'slow');
					setTimeout(function() 
					{
						jQuery('#success_update_album_message').css('display','none');	
						window.location.href = "admin.php?page=gallery_bank";
					}, 3000);
				}
			});
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
					thumb_array.splice(index, 1);
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
				var div = jQuery("<div class=\"box\" style=\"border-bottom: solid 1px #e5e5e5;\"id=\""+dynamicId+"\">");
				var innerDiv = jQuery("<div style=\"float:left;width:170px;\">");
				var img = jQuery("<img class=\"imgHolder\" style=\"border:3px solid #e5e5e5;margin-top:10px;\"; id=\"up_img\"/>");
				img.attr('src', attachment.url);
				thumb_array.push(attachment.url);
				img.attr('width', '150px');
				innerDiv.append(img);
				var cover = jQuery("<input type=\"radio\" style=\"cursor: pointer;\" id=\"cover_pic_"+dynamicId+"\" name = \"edit_album_cover\" /><label><?php _e(" Set Image as Album Cover",gallery_bank);?></label>");
				cover.css('margin-bottom', '10px');
				innerDiv.append(cover);
				var del = jQuery("<a class=\"imgHolder orange\" style=\"margin-left: 20px;cursor: pointer;\" id=\"del_img\" onclick=\"delete_pic("+dynamicId+")\"><img style=\"vertical-align:middle;cursor:pointer\" src=\"<?php echo GALLERY_BK_PLUGIN_URL.'/images/button-cross.png'?>\">&nbsp; <span style=\"vertical-align:middle;cursor:pointer;\"><?php _e("Remove Image",gallery_bank);?></span></a>");
				innerDiv.append(del);
				div.append(innerDiv);
				var box = jQuery("<div class=\"row\" style=\"margin-left:180px;margin-top:10px;\"><label><?php _e("Title :",gallery_bank);?></label><div class=\"right\" ><input type=\"text\" id=\"pic_title_"+dynamicId+"\"/></div></div>");
				box.css('margin-bottom', '10px');
				div.append(box);
				var text = jQuery("<div class=\"row\" style=\"margin-left:180px; border-bottom:none !important;\"><label><?php _e("Description :",gallery_bank);?></label><div class=\"right\" ><textarea id=\"pic_des_"+dynamicId+"\" rows=\"10\"></textarea></div></div></br>");
				text.css('margin-bottom', '10px');
				div.append(text);
				ar.push(dynamicId);
				div.append('</div>');
				jQuery("#edit_media").append(div);
			});
		});
		file_frame.open();
	});
</script>
