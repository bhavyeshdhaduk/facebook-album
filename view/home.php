<?php

if(!$_SESSION['facebook_access_token'])
{
header("location:index.php");
}

try {
  $response = $fb->get('/me?fields=id,name,email,first_name,last_name,gender,picture.width(30).height(30),albums.fields(id,name,cover_photo.fields(name,picture,source),photos.fields(name,picture,source)) ', $_SESSION['facebook_access_token']);
}
catch(Facebook\Exceptions\FacebookResponseException $e) {
  echo 'ERROR: Graph ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'ERROR: validation fails ' . $e->getMessage();
  exit;
}

$me = $response->getGraphUser();
$fullname = $me->getProperty('name');
$profileurl =  $me['picture']['url'];
?>

	<header>
		<div class="header-container container">
			<div class="header-row row">
				<div class="user-name pull-left">
					<?php echo $fullname; ?>			
				</div>
            	<ul class="nav navbar-top-links pull-right">
	            <!-- /.dropdown -->
	                <li class="dropdown">
	                 	<a class="dropdown-toggle" data-toggle="dropdown" href="#">
	                   		<img class="profile-pic" alt="profile" src="<?php echo $profileurl; ?>" />
	                 	</a>
	                    <ul class="dropdown-menu dropdown-user">
	                        <li><a href="https://facebook.com/" target="_blank" title="user profile">User Profile</a>
	                        </li>
	                        <li><a href="https://facebook.com/settings" target="_blank" title="user Setting">Settings</a>
	                        </li>
	                        <li class="divider"></li>
	                        <li><a href="logout.php" title="logout">Logout</a>
	                        </li>
	                    </ul>
	                </li>
                <!-- /.dropdown -->
            	</ul>
			</div>
		</div>
	</header>

	<div class="page-wrapper">
		<?php
		if (empty($me['albums'])) 
		{
		?>
			<div class="errormsg">sorry, you have not access permission to access this functionality, please send me request so i will add you as a tester, [ <a href="https://web.facebook.com/bhavyesh17" target="_blanck" title="Bhavyesh Dhaduk">Bhavyesh Dhaduk</a> ] </div>
		<?php
		exit;
		}
		?>
		<div class="wrap-container container">
			<div class="row head-btn">
				<div class="col-lg-3 col-sm-3 col-md-3"><button type="button" id="download-selected-albums" class="btn btn-custom">Download Selected </button></div>
				<div class="col-lg-3 col-sm-3 col-md-3"><button type="button" id="download-all" class="btn btn-custom-light">Download All </button></div>
				<div class="col-lg-3 col-sm-3 col-md-3"><button type="button" id="download-all" class="btn btn-custom">Move Selected </button></div>
				<div class="col-lg-3 col-sm-3 col-md-3"><button type="button" id="download-all" class="btn btn-custom-light">Move All </button></div>
				<div class="progressbar-head col-lg-12 col-sm-12 col-md-12">
					<img  src="<?php echo IMAGES;?>/ajax-loader.gif" alt="progressbar">
					<iframe id="downloadframe" src="" style="display:none; visibility:hidden;"></iframe>
				</div>
			</div>
			<div class="row">
			<?php
				if(!empty($me['albums']))
				{ 
					$albums = $me['albums'];
					foreach ($albums as $album) {	
			        	$id =  $album['id'];
			         	$name =  $album['name'];
			        	$cover_photo =  $album['cover_photo']['source'];
				?>
			        	<div class="col-lg-3 col-sm-4 col-md-3">	
			        		<div  class="thumbnail box">
			        			<a class="<?php echo $id; ?>" href="javascript:;"> 
		      						<img src="<?php echo $cover_photo; ?>" alt="<?php echo $name; ?>" class="cover-photo img-thumbnail" >	
		      					</a>
		      					<div class="caption">
									<input type="checkbox" class="select-album pull-left"  title="select album" id="<?php echo $album['id']; ?>" value="<?php echo $album['id'];?>" />
									<?php $names = (strlen($name) > 17) ? substr($name,0,17).'...' : $name; ?>
									<label class="album-name" for="<?php echo $album['id']; ?>"><?php echo $names; ?></label>
									<div class="photo-count">
										<?php echo count($album['photos']);?> Photos
									</div>
									<button rel="<?php echo $id;?>" type="button" class="btn btn-custom btn-block btn-sm download-album" >Download </button>
									<div data-albumid="<?php echo $id;?>" class="progressbar">
										<img  src="<?php echo IMAGES;?>/ajax-loader.gif" alt="progressbar">
										<button type="button"  data-albumid="<?php echo $id;?>" class="btn btn-block btn-sm download-zipalbum" >Download Zip</button>
										<iframe id="downloadframe" src="" style="display:none; visibility:hidden;"></iframe>
									</div>
								</div>
			        		</div>
		      			</div>

			      		<script type="text/javascript">
							$(document).ready(function() {
								$(".<?php echo $id; ?>").click(function() {
									$.fancybox.open([
										<?php
										foreach ($album['photos'] as $photo) {
			        					 	$inpic =  $photo['source'];
			        						?>
			        					 	{
												href : '<?php echo $inpic; ?>'
											},
								  <?php } ?>
										], 
										{
											helpers : {
												thumbs : {
													width: 75,
													height: 50
												}
											}
										});
								});
							});
						</script>
			        <?php
				}
			}
			?>
		</div>
	</div>

	<script type="text/javascript">
		
		$(".download-album").on("click", function() 
		{
			var id = $(this).attr("rel");
			$('.progressbar[data-albumid='+id+']').show();
			$('.progressbar[data-albumid='+id+'] > img').show();
			$('.download-zipalbum[data-albumid='+id+']').hide();	
			$(".box").css("height", "390px");
			$.ajax({
				  url: 'download_album.php',
				  type: 'GET',
				  data: {id : id},
				  success: function(data) {

					$('.progressbar[data-albumid='+id+'] > img').hide();
					if (data == 'unsuccess') {
						alert('something goes wrong please try again');
					}
					else
					{
						$('.download-zipalbum[data-albumid='+id+']').show();		
						$('.download-zipalbum[data-albumid='+id+']').attr('data-albumnm', data);
					}

				  },
				  error: function(e) {
					//console.log(e.message);
				  }
			});
		});

//download zip
	$(".download-zipalbum").on("click", function() {
		var name = $(this).attr("data-albumnm");
		var id = $(this).attr("data-albumid");		  
		$('.progressbar[data-albumid='+id+'] > iframe').attr('src','download_zip.php?name='+name+'');
	});

//download selected
	$("#download-selected-albums").on("click", function() {
				
			var selected_albums = get_selected_albums();
			if (selected_albums == null) {
				alert('Knindlly Select any album');
				return false;
			}
		
			$('.progressbar-head').show();
			$.ajax({
				  url: 'download_sel_album.php',
				  type: 'GET',
				  data: {selected_albums : selected_albums},
				  success: function(data) {

					if (data == 'unsuccess') {
						alert('something goes wrong please try again');
					}
					else
					{
						$('.progressbar-head').hide();
						$('.progressbar-head > iframe').attr('src','download_zip.php?name='+data+'');

					}

				  },
				  error: function(e) {
					//console.log(e.message);
				  }
			});
	});

	function get_selected_albums() {
		var selected_albums;
		var i = 0;
		$(".select-album").each(function () {
			if ($(this).is(":checked")) {
					if (!selected_albums) {
						selected_albums = $(this).val();
					} 
					else
					{
						selected_albums = selected_albums + "," + $(this).val();
					}
			}
		});
	return selected_albums;
	}

	//download All
	$("#download-all").on("click", function() {
				
			$('.progressbar-head').show();
			$.ajax({
				  url: 'download_all_album.php',
				  type: 'GET',
				  success: function(data) {
					//called when successful
					
					if (data == 'unsuccess') {
						alert('something goes wrong please try again');
					}
					else{
						$('.progressbar-head').hide();
						$('.progressbar-head > iframe').attr('src','download_zip.php?name='+data+'');
					}

				  },
				  error: function(e) {
					//console.log(e.message);
				  }
				});

	});
</script>