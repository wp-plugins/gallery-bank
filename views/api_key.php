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
					<?php _e( "API KEY", gallery_bank); ?>
				</a>
			</li>
		</ul>
	</div>
	<div class="section">
		<div class="box">
			<div class="title"><span><?php _e("API KEY", gallery_bank);?></span>
			</div>
			<div id="content">
				<form id="uxFrmApikey" class="events-container-form" method="post" action="">
					<div class="message green" id="successMessageAPI" style="margin-left:10px;">
						<span>
							<strong>
								<?php _e( "<p>We Respect your Privacy. Your information is highly confidential to us and we don't sell it to third parties.</p><p> We need you to register your API Key with your Email Address & Name in order to make this plugin work smoothly.</p><p>Once you register your API, the plugin would work as expected.</p>", gallery_bank ); ?>
							</strong>
						</span>
					</div>
					<div class="body" id="api_key">
						<div class="row">
							<label >
								<?php _e("Your Email :" ,gallery_bank)?>
							</label>
							<div class="right">
								<input type="text" name="uxApiEmail" id="uxApiEmail">
							</div>
						</div>
						<div class="row">
							<label>
								<?php _e("Your Name :" ,gallery_bank)?>
							</label>
							<div class="right">
								<input type="text" name="uxApiName" id="uxApiName">
							</div>
						</div>
						
						<div class="row">
							<label>
								<?php _e("Api Key :" ,gallery_bank)?>
							</label>
							<div class="right">
								<input type="text" readonly="readonly" "uxApiKey" id="uxApiKey" value="<?php
								function NewGuid() 
								{ 
									$s = strtoupper(md5(uniqid(rand(),true))); 
									$guidText = 
									substr($s,0,8) . '-' . 
									substr($s,8,4) . '-' . 
									substr($s,12,4). '-' . 
									substr($s,16,4). '-' . 
									substr($s,20); 
									return $guidText;
								}
								echo NewGuid();
								$key = NewGuid();
							?>">
							</div>
						</div>
						<div class="row">
							<label>
								<?php _e("" ,gallery_bank)?>
							</label>
							<div class="right">
								<input type="checkbox" name="uxApiCheck" id="uxApiCheck" checked="checked">
								<span><?php _e("Yes, I may Purchase Pro Version Later." ,gallery_bank); ?></span>
							</div>
						</div>
						<div class="row" style="border-bottom:none !important">
							<label>
							</label>
							<div class="right">
								<button type="submit" class="events-container-button blue">
									<span>
										<?php _e( "Register your API Key to start using Gallery Bank", gallery_bank ); ?>
									</span>
								</button>
							</div>
						</div>
					</div>
				</form>
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
<script type="text/javascript">
	
	jQuery("#APIkey").addClass("current");
	var pass_string = "http://gallery-bank.com/wp-content/plugins/wp-gallery-bank-users/user_data.php";
	jQuery("#uxFrmApikey").validate
	({
		rules:
		{
			uxApiEmail:
			{
				required: true,
				email: true
			},
			uxApiName:
			{
				required: true,
			},
			uxApiKey: 
			{
				required: true,
			}
		},
		submitHandler: function(form)
		{
			
			var uxApiEmail = encodeURIComponent(jQuery("#uxApiEmail").val());
			var uxApiName = encodeURIComponent(jQuery("#uxApiName").val());
			var uxApiKey = encodeURIComponent(jQuery("#uxApiKey").val());
			var uxApiCheck = jQuery("#uxApiCheck").prop("checked");
			var site_url = "<?php echo site_url();?>";
			var culture = "<?php echo get_locale();?>"; 
			var st_url = encodeURIComponent(site_url);
			var cult = encodeURIComponent(culture);
			if(uxApiCheck == true)
			{
				var ux_chk_value = "1";
			}
			else
			{
				var ux_chk_value = "0";
			}
			
			if(uxApiEmail != "" || uxApiEmail != null )
			{
				jQuery.post(pass_string,  +"?"+"&uxApiName="+uxApiName+"&uxApiKey="+uxApiKey+"&ux_chk_value="+ux_chk_value+"&st_url="+st_url+"&cult="+cult+"&uxEmail="+uxApiEmail, function(data)
				{
					jQuery.post(ajaxurl,  "uxApiKey="+uxApiKey+"&param=updateApi&action=apikeyLibrary", function(data)
					{
						window.location.href = "admin.php?page=gallery_bank";
					});
					
					
				});
			}
		}
	});
</script>