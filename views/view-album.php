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
							<?php _e( "Album Details", gallery_bank ); ?>
						</a>
					</li>
				</ul>
			</div>
			<a href="admin.php?page=gallery_bank" class="events-container-button blue" style="text-decoration: none; margin-top: 10px; margin-left: 10px;">
				<span><?php _e('Back to Albums', gallery_bank); ?></span>
			</a>
			<form id="view_bank_album" class="form-horizontal" method="post" action="" style="padding:10px 10px 10px 0px">
					<div class="body">
						<div class="box">
							<div class="content">
								<?php
								if(isset($_REQUEST["album_id"]))
								{
									$album_id = $_REQUEST["album_id"];
									$album = $wpdb->get_row
								(
									$wpdb->prepare
									(
										"SELECT * FROM ".gallery_bank_albums()." WHERE album_id = %d",
										$album_id
									)
								);
								}
								
								?>
								<div class="row" style="border-top: none !important">
									<label style="top:10px;">
										<?php _e( "Album Name :", gallery_bank ); ?>
									</label>
									<div class="right">
										<span>
											<?php echo stripcslashes($album->album_name);?>
										</span>
									</div>
								</div>
								<div class="row">
									<label style="top:10px;">
										<?php _e( "Description :", gallery_bank ); ?>
									</label>
									<div class="right" >
										<span>
											<?php echo stripslashes($album->description);?>
											&nbsp;
										</span>
									</div>
								</div>
								<div class="row">
									<label style="top:10px;">
										<?php _e( "Author Name :", gallery_bank ); ?>
									</label>
									<div class="right">
										<span>
											<?php echo $album->author;?>
											&nbsp;
										</span>
									</div>
								</div>
								<div class="row">
									<label style="top:10px;">
										<?php _e( "No. of Pics:", gallery_bank ); ?>
									</label>
									<div class="right">
										<span>
											<?php 
											$count_pic = $wpdb->get_var
											(
												$wpdb->prepare
												(
													"SELECT count(".gallery_bank_albums().".album_id) FROM ".gallery_bank_albums()." join ".gallery_bank_pics()." on ".gallery_bank_albums().".album_id =  ".gallery_bank_pics().".album_id where ".gallery_bank_albums().".album_id = %d ",
													$album->album_id
												)
											);
											?>
											<?php echo $count_pic;?>
											&nbsp;
										</span>
									</div>
								</div>
								<div class="row">
									<label style="top:10px;">
										<?php _e( "Album Date:", gallery_bank ); ?>
									</label>
									<div class="right">
										<span>
											<?php echo $album->album_date;?>
											&nbsp;
										</span>
									</div>
								</div>
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
						if( count($pic_detail) == 0)
						{
							?>
							<div class="box" style=" margin-top: 10px; margin-left: 20px; border: solid 1px #e5e5e5; padding: 10px; display: none;">
							</div>
							<?php	
						}
						else 
						{
						?>
							<div class="box" style=" margin-top: 10px; margin-left: 20px; border: solid 1px #e5e5e5; padding: 10px">
								<?php
								for ($flag = 0; $flag < count($pic_detail); $flag++)
								{
									?>
									<div id="bank_pics" style="padding: 10px; display: inline-block; width:860px;border-bottom:solid 1px #E5E5E5;">
											<?php
											if($pic_detail[$flag]->description == "")
											{
												?>
												<a class="vlightbox1" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?>">
												<img class="imgHolder" src="<?php echo $pic_detail[$flag]->pic_path; ?>" style="border:3px solid #e5e5e5"; width="150px;"/></a>
											<?php
											}
											else 
											{
												?>
												<a class="vlightbox1" id="<?php echo $pic_detail[$flag]->pic_id; ?>" href="<?php echo $pic_detail[$flag]->pic_path; ?>" title="<?php echo stripcslashes($pic_detail[$flag]->title); ?> (<?php echo stripcslashes($pic_detail[$flag]->description); ?>)">
												<img class="imgHolder" src="<?php echo $pic_detail[$flag]->pic_path; ?>" style="border:3px solid #e5e5e5"; width="150px;"/></a>
												<?php
											}
											?>
											<div class="row" style="margin-left:180px !important;">
												<label style="top:10px;">
													<strong><?php _e( "Title :", gallery_bank ); ?></strong>
												</label>
												<div class="right">
													<span><?php echo stripcslashes($pic_detail[$flag]->title); ?></span>
													&nbsp;
												</div>
											</div>
											<div class="row" style="margin-left:180px !important;">
												<label style="top:10px;">
													<strong><?php _e( "Description :", gallery_bank ); ?></strong>
												</label>
												<div class="right" >
													<span><?php echo stripcslashes($pic_detail[$flag]->description);?></span>
													&nbsp;
												</div>
											</div>
										</div>
									<?php
								}
								?>
							</div>
						<?php
						}
							?>
					</div>
				</form>
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
jQuery(document).ready(function(){
	window.Lightbox = new jQuery().visualLightbox({
		autoPlay:false,
		borderSize:39,
		classNames:'vlightbox1',
		descSliding:true,
		enableRightClick:false,
		enableSlideshow:false,
		prefix:'vlb1',
		resizeSpeed:7,
		slideTime:4,
		startZoom:true}) 
		});
</script>