
<div class="block well" style="min-height:400px;">
	<div class="navbar">
		<div class="navbar-inner">
			<h5><?php _e( "Add New Album", gallery_bank ); ?></h5>
		</div>
	</div>
	<div class="body" style="margin:10px;">
		<form class="form-horizontal" id="add_new_album">
			<a class="btn btn-inverse" href="admin.php?page=gallery_bank"><?php _e( "Back to Album Overview", gallery_bank ); ?></a>
			<button type="submit" class="btn btn-primary" style="float:right">
				<?php _e( "Publish Album", gallery_bank ); ?>
			</button>
			<div class="separator-doubled"></div>
			<div class="message green" id="message" style="display: none;">
				<span>
					<strong><?php _e("Album published.Kindly wait for the redirect.", gallery_bank); ?></strong>
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
								<div class="cntrl">
									<input type="text" name="title" class="span12" value="" id="title" placeholder="<?php _e( "Enter your Album title here", gallery_bank);?>" />
								</div>
							</div>
							<div class="control-group">
							<?php 
								wp_editor("", $id = 'ux_description', $prev_id = 'title', $media_buttons = true, $tab_index = 1);
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
								<a class="btn btn-info" id="upload_image_button" href=""><?php _e( "Upload Images to your Album ", gallery_bank ); ?></a>
							</div>
							<div class="control-group">
								<input type="checkbox" id="delete_selected" name="delete_selected" style="cursor: pointer;"/>
								<a class="btn btn-danger" onclick="delete_selected_images();" style="margin-left: 20px; cursor: pointer;">
									<?php _e( "Delete ", gallery_bank ); ?>
								</a>
							</div>
							<table class="table table-striped" id="add-album-data-table">
							</table>
						</div>
					</div>
				</div>
				<div class="span4">
					<div class="block well">
						<div class="navbar">
							<div class="navbar-inner">
								<h5><?php _e( "Album Cover", gallery_bank ); ?></h5>
								<div class="nav pull-right">
									<a class="just-icon" data-toggle="collapse" data-target="#album_cover">
										<i class="font-resize-vertical"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="collapse in" id="album_cover">
							<div class="body">
								<div class="control-group">
									<img id="albumCoverImage" src="<?php echo GALLERY_BK_PLUGIN_URL .'/album-cover.png';?>"/>
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
									<a class="just-icon" data-toggle="collapse" data-target="#short_code">
										<i class="font-resize-vertical"></i>
									</a>
								</div>
							</div>
						</div>
						<div class="collapse in " id="short_code">
							<div class="body">
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "All Albums", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank_all_albums]</span>
								</div>
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "Single Album", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank_album album_id=1]</span>
								</div>
								<div class="control-group">
									<label class="control-label" style="padding-top:3px !important"><?php _e( "Album with Cover", gallery_bank ); ?> : </label>
									<span class="label label-info">[gallery_bank_album_cover  album_id=1]</span>
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
	var arr =[];
	var ar = [];
	var thumb_array = [];
	var cover_array;
	jQuery(document).ready(function() 
	{
		oTable = jQuery('#add-album-data-table').dataTable
		({
			"bJQueryUI": false,
			"bAutoWidth": true,
			"sPaginationType": "full_numbers",
			"sDom": '<"datatable-header"fl>t<"datatable-footer"ip>',
			"oLanguage": 
			{
				"sLengthMenu": "_MENU_"
			},
			"aaSorting": [[ 0, "asc" ]],
			"aoColumnDefs": [{ "bSortable": false, "aTargets": [0] },{ "bSortable": false, "aTargets": [0] }]
		});
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
				jQuery("#albumCoverImage").attr('src', attachment.url);
				jQuery("#albumCoverImage").attr('width','250px');
				
				cover_array = attachment.url;
			});
		});
		cover_file_frame.open();
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
				thumb_array.push(attachment.url);
				arr.push(attachment.url);
				ar.push(dynamicId);
				var tr = jQuery("<tr></tr>");
				var td = jQuery("<td></td>");
				var main_div = jQuery("<div class=\"block\" id=\""+dynamicId+"\" >");
				var div = jQuery("<div class=\"block\" style=\"padding:6px 6px 0 0; width:2%; float:left\">");
				var checkbox = jQuery("<input type=\"checkbox\" value=\""+dynamicId+"\" style=\"cursor: pointer;\"/>");
				div.append(checkbox);
				div.append("</div>");
				td.append(div);
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
				var box = jQuery("<div class=\"control-group\"><input type=\"text\" style=\"width=150%\" class=\"span12\" id=\"title_img_"+dynamicId+"\" placeholder=\"<?php _e( "Enter your Image Title", gallery_bank);?>\" /></div>");
				block1.append(box);
				var text = jQuery("<div class=\"control-group\"><textarea id=\"des_img_"+dynamicId+"\" rows=\"10\" placeholder=\"<?php _e( "Enter your Image Description", gallery_bank);?>\" style=\"width=150%\" class=\"span12\"></textarea></div>"); 
				block1.append(text);
				block1.append("</div>");
				main_div.append(div);
				main_div.append(block);
				main_div.append(block1);
				main_div.append("</div>");
				td.append(main_div);
				tr.append(td);
				oTable = jQuery('#add-album-data-table').dataTable();
				oTable.fnAddData([tr.html()]);
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
				jQuery("#" + dynamicId).remove();
				if(ar.indexOf(dynamicId) > -1) {
					var index = ar.indexOf(dynamicId);
					arr.splice(index, 1);
					ar.splice(index, 1);
					thumb_array.splice(index, 1);
					oTable = jQuery('#add-album-data-table').dataTable();
					oTable.fnDeleteRow(index);
				}
			}
		});
	}
	jQuery("#add_new_album").validate
	({
		rules: 
		{
			title: "required",
			ux_image_width: 
			{
				required: true,
				digits: true
			},
			ux_image_height: 
			{
				required: true,
				digits: true
			},
			ux_cover_width:
			{
				required : true,
				digits: true
			},
			ux_cover_height:
			{
				required : true,
				digits: true
			},
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
			var album_name = encodeURIComponent(jQuery('#title').val());
			jQuery.post(ajaxurl, jQuery(form).serialize() +"&album_name="+album_name+"&ux_description="+uxDescription+"&cover_array="+cover_array+"&param=add_new_album&action=album_gallery_library", function(data)
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
						var title = "";
						if(encodeURIComponent(jQuery("#title_img_" + ar[pic]).val()) != "undefined")
						{
							title = encodeURIComponent(jQuery("#title_img_" + ar[pic]).val());
						}
						var detail="";
						if(encodeURIComponent(jQuery("#des_img_" + ar[pic]).val()) != "undefined")
						{
							detail = encodeURIComponent(jQuery("#des_img_" + ar[pic]).val());
						}
						jQuery.post(ajaxurl, "album_id="+album_id+"&title="+title+"&detail="+detail+"&path="+path+"&thumb="+thumb+"&param=add_pic&action=album_gallery_library", function(data)
						{
							jQuery('#message').css('display','block');
							count++;
							if(count == arr.length)
							{
								setTimeout(function()
								{
									jQuery('#message').css('display','none');
									window.location.href = "admin.php?page=gallery_bank";
								}, 2000);
							}
							
						});
					}
				}
				else
				{
					jQuery('#message').css('display','block');
					setTimeout(function()
					{
						jQuery('#message').css('display','none');
						window.location.href = "admin.php?page=gallery_bank";
					}, 2000);
				}
			});
		}
	});
	jQuery(function()
	{
		jQuery('#delete_selected').click(function(){
			var oTable = jQuery("#add-album-data-table").dataTable();
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
	function delete_selected_images()
	{
		bootbox.confirm("<?php _e( "Are you sure you want to delete these Images?", gallery_bank ); ?>", function(confirmed)
		{
			console.log("Confirmed: "+confirmed);
			if(confirmed == true)
			{
				var oTable = jQuery("#add-album-data-table").dataTable();
				jQuery("input:checkbox:checked", oTable.fnGetNodes()).each(function(){
				var dynamicId = parseInt(this.checked? jQuery(this).val():"");
				jQuery("#" + dynamicId).remove();
					if(ar.indexOf(dynamicId) > -1){
						var index = ar.indexOf(dynamicId);
						arr.splice(index, 1);
						ar.splice(index, 1);
						thumb_array.splice(index, 1);
						oTable.fnDeleteRow(index);
						jQuery('#delete_selected').removeAttr('checked');
					}
				});
			}
		});
	}
	
	
</script>