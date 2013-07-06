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
							<?php _e( "Create New Album", gallery_bank ); ?>
						</a>
					</li>
				</ul>
			</div>
			<a href="admin.php?page=gallery_bank" class="events-container-button blue" style="margin-top:10px;text-decoration: none;">
				<span><?php _e('Back to Albums Page', gallery_bank); ?></span>
			</a>
			<div class="message green" id="success_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Success! We are in the middle of updating the data. Kindly wait for the redirect.", gallery_bank); ?></strong>
				</span>
			</div>
			<div class="message red" id="error_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Error! Slide Interval can't be zero.", gallery_bank); ?></strong>
				</span>
			</div>
			<div class="message red" id="error_border_album_message" style="margin-left :20px; display: none;">
				<span>
					<strong><?php _e( "Error! Border width can't be zero.", gallery_bank); ?></strong>
				</span>
			</div>
			<div id="add-album"  style="display: block;padding:10px 10px 10px 0px;">
				<form id="add_new_album" class="form-horizontal" method="post" action="">
					<div class="body">
						<div class="box">
							<div class="content">
								<div class="row">
									<label>
										<?php _e( "Album Name :", gallery_bank ); ?>
									</label>
									<div class="right">
										<input type="text" class="required span12" name="ux_album_name" id="ux_album_name"/> 
									</div>
								</div>
								<div class="row">
									<label style="top: 10px;"><?php _e( "Description :", gallery_bank ); ?></label>
									<div class="right">
										<?php 
										wp_editor("", $id = 'ux_description', $prev_id = 'title', $media_buttons = true, $tab_index = 1);
										?>
									</div>
								</div>
								<div class="row" >
									<img src="<?php echo GALLERY_BK_PLUGIN_URL."/images/screenshot.png";?>"/>
								</div>
								<div class="row" >
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
							</div>
						</div>
						<div class="box">
							<div id="add_media" class="content" >
							</div>
						</div>
						<div class="box">
							<div class="content">
								<div class="row" style="border-bottom:none!important;">
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
<script type="text/javascript">
jQuery("#gallery_bank").addClass("current");
var arr =[];
var ar = [];
var thumb_array = [];
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
					arr.splice(index, 1);
					ar.splice(index, 1);
					thumb_array.splice(index, 1);
				}
			}
		});
	}
	jQuery("#add_new_album").validate
	({
		rules: 
		{
			ux_album_name: "required"
		},
		submitHandler: function(form)
		{
			if (jQuery("#wp-ux_description-wrap").hasClass("tmce-active"))
			{
				var uxDescription  = encodeURIComponent(tinyMCE.get('ux_description').getContent());
			}
			else
			{
				var uxDescription  = encodeURIComponent(jQuery('#ux_description').val());
			}
			var ux_slide_interval = jQuery('#ux_slide_interval').val();
			var album_name = encodeURIComponent(jQuery('#ux_album_name').val());
			
			jQuery.post(ajaxurl, jQuery(form).serialize() +"&album_name="+album_name+"&ux_description="+uxDescription+"&param=add_new_album&action=album_gallery_library", function(data)
			{
				var album_id= jQuery.trim(data);
				var pic;
				var count = 0;
				if(arr.length > 0)
				{
					for(pic = 0; pic < arr.length; pic++ )
					{
						var path = arr[pic];
						var thumb = thumb_array[pic];
						var title = encodeURIComponent(jQuery("#title_img_" + ar[pic]).val());
						var detail= encodeURIComponent(jQuery("#des_img_" + ar[pic]).val());
						var alb_cover = jQuery('input:radio[id=cover_'+ar[pic]+']:checked').val();
						if(alb_cover == "on")
						{
							alb_cover1 = 1;
						}
						else
						{
							alb_cover1 = 0;
						}
						jQuery.post(ajaxurl, "album_id="+album_id+"&title="+title+"&detail="+detail+"&alb_cover="+alb_cover1+"&path="+path+"&thumb="+thumb+"&param=add_pic&action=album_gallery_library", function(data)
						{
							jQuery('#error_album_message').css('display','none');
							jQuery('#error_border_album_message').css('display','none');
							jQuery('#success_album_message').css('display','block');
							jQuery('body,html').animate({
							scrollTop: jQuery('body,html').position().top}, 'slow');
							count++;
							if(count == arr.length)
							{
								setTimeout(function()
								{
									jQuery('#success_album_message').css('display','none');
									window.location.href = "admin.php?page=gallery_bank";
								}, 2000);
							}
						});
					}
				}
				else
				{
					jQuery('#error_album_message').css('display','none');
					jQuery('#error_border_album_message').css('display','none');
					jQuery('#success_album_message').css('display','block');
					jQuery('body,html').animate({
					scrollTop: jQuery('body,html').position().top}, 'slow');
					setTimeout(function()
					{
						jQuery('#success_album_message').css('display','none');
						window.location.href = "admin.php?page=gallery_bank";
					}, 2000);
				}
				
					
			});
		}	
	});
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
				var dynamicId = Math.floor((Math.random() * 1000)+1);
				var div = jQuery("<div class=\"box\" style=\"border-bottom: solid 1px #e5e5e5;\"id=\""+dynamicId+"\">");
				var innerDiv = jQuery("<div style=\"float:left;width:170px;margin-left:10px;\">");
				var img = jQuery("<img class=\"imgHolder\" style=\"border:3px solid #e5e5e5;margin-top:10px;\" id=\"up_img\"/>");
				img.attr('src', attachment.url);
				thumb_array.push(attachment.url);
				arr.push(attachment.url);
				img.attr('width', '150px');
				innerDiv.append(img);
				var cover = jQuery("<input type=\"radio\" checked=\"checked\" style=\"cursor: pointer;\" id=\"cover_"+dynamicId+"\" name = \"album_cover\" /><label><?php _e(" Set Image as Album Cover",gallery_bank);?></label>");
				cover.css('margin-bottom', '10px');
				innerDiv.append(cover);
				var del = jQuery("<a class=\"imgHolder orange\" style=\"margin-left: 20px;cursor: pointer;\" id=\"del_img\" onclick=\"delete_pic("+dynamicId+")\"><img style=\"cursor: pointer;vertical-align:middle;\" src=\"<?php echo GALLERY_BK_PLUGIN_URL.'/images/button-cross.png'?>\">&nbsp; <span  style=\"cursor: pointer;vertical-align:middle;\"><?php _e("Remove Image",gallery_bank);?></span></a>");
				innerDiv.append(del);
				div.append(innerDiv);
				var box = jQuery("<div class=\"row\" style=\"margin-left:180px;\"><label><?php _e("Title :",gallery_bank);?></label><div class=\"right\" style=\"margin-left:80px !important;\"><input type=\"text\" id=\"title_img_"+dynamicId+"\"/></div></div>");
				box.css('margin-bottom', '10px');
				div.append(box);
				var text = jQuery("<div class=\"row\" style=\"margin-left:180px;border-bottom:none !important;\"><label><?php _e("Description :",gallery_bank);?></label><div class=\"right\" style=\"margin-left:80px !important;\"><textarea id=\"des_img_"+dynamicId+"\" rows=\"10\"></textarea></div></div></br>");
				text.css('margin-bottom', '10px');
				div.append(text);
				ar.push(dynamicId);
				div.append('</div>');
				jQuery("#add_media").append(div);
			});
		});
		file_frame.open();
	});
	function div_control()
	{
		var border = jQuery("#ux_image_border").prop("checked");
		if(border == true)
		{
			jQuery("#ux_border").css('display','block');
		}
		else
		{
			jQuery("#ux_border").css('display','none');
		}
	}
	function slide_control()
	{
		var ux_slide = jQuery("#ux_slide").prop("checked");
		if(ux_slide == true)
		{
			jQuery("#div_slide_interval").css('display','block');
		}
		else
		{
			jQuery("#div_slide_interval").css('display','none');
		}
	}
</script>