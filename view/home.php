<?php

try {
  // Get the Facebook\GraphNodes\GraphUser object for the current user.
  // If you provided a 'default_access_token', the '{access-token}' is optional.
  $response = $fb->get('/me?fields=id,name,email,first_name,last_name,gender,picture.width(30).height(30),albums.fields(id,name,cover_photo.fields(name,picture,source),photos.fields(name,picture,source)) ', $_SESSION['facebook_access_token']);
//  print_r($response);
} catch(Facebook\Exceptions\FacebookResponseException $e) {
  // When Graph returns an error
  echo 'ERROR: Graph ' . $e->getMessage();
  exit;
} catch(Facebook\Exceptions\FacebookSDKException $e) {
  // When validation fails or other local issues
  echo 'ERROR: validation fails ' . $e->getMessage();
  exit;
}
$me = $response->getGraphUser();
?>
<pre>
<?php
print_r($me);
?>
</pre>
<?php
$fullname = $me->getProperty('name');
// echo "First Name: ".$me->getProperty('first_name')."<br>";
// echo "Last Name: ".$me->getProperty('last_name')."<br>";
// echo "Email: ".$me->getProperty('email')."<br>";
// echo "gender: ".$me->getProperty('gender')."<br>";
$profileurl =  $me['picture']['url'];


?> <!-- <img src="<?php // print $profileurl; ?>" /> -->
<?php
 // session_destroy();
//header('location:index.php');
?>


<?php
//print_r($me['albums']);
 $albums = $me['albums'];

?>
<pre>
<?php
//print_r($albums);
?>
</pre>
<?php
	
foreach ($albums as $album) {
			
        		 $id =  $album['id'].'<br>';
        		 $name =  $album['name'].'<br>';
        		 $cover_photo =  $album['cover_photo']['source'];
        	//	echo '<img src="'.$cover_photo.'">';
        		
        		foreach ($album['photos'] as $photo) {
        			 $inpic =  $photo['source'];
        			//echo '<img src="'.$inpic.'">';
        		}
}
?>

<header>
		<div class="header-container container">
			<div class="header-row row">
				<div class="user-name pull-left">
						<?php echo $fullname; ?>			
				</div>
				<div class="right-info pull-right">		
						<img src="<?php echo $profileurl; ?>" />
						<a href="logout.php">Logout</a>	
				</div>
			</div>
		</div>
</header>

<div class="page-wrapper">
	<div class="header-container container">
		<div class="row">
			<div class="col-lg-12">
				<h1 class="page-header">Panels and Wells</h1>
			</div>
		</div>

		<div class="row">
			
			<?php	
			foreach ($albums as $album) {
						
			        $id =  $album['id'];
			         $name =  $album['name'];
			        $cover_photo =  $album['cover_photo']['source'];
			       //	echo '<img src="'.$cover_photo.'">';
			        ?>
			        <div class="col-lg-3 col-sm-3 col-md-3">	

			        	<div  class="thumbnail" style="height:300px;">

			        		<a id="<?php echo $id; ?>" href="javascript:;"> 
		      				<img src="<?php echo $cover_photo; ?>" alt="<?php echo $name; ?>" class="cover-photo" >	
		      				</a>

		      				<div class="caption">
							<input type="checkbox" class="select-album pull-left"  title="select album" value="<?php echo $album['id'].','.$album['name'];?>" />
							<?php $names = (strlen($name) > 25) ? substr($name,0,25).'...' : $name; ?>
							<div class="album-name" ><?php echo $names; ?></div>
							<div class="photo-count">
							<?php echo count($album['photos']);?> Photos</div>
						
						</div>
			        	</div>
		    			 
		      		</div>

			      		<script type="text/javascript">
							$(document).ready(function() {
								
								$("#<?php echo $id; ?>").click(function() {
									$.fancybox.open([
										<?php
										foreach ($album['photos'] as $photo) {
			        					 $inpic =  $photo['source'];
			        					?>
			        					 	{
											href : '<?php echo $inpic; ?>'
											},

										<?php
			        					}
			        					?>

									], {
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
			?>
		</div>
	</div>
</div>

<br/><br/><br/><br/>

