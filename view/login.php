
<div class="login-wrapper">
<?php

$helper = $fb->getRedirectLoginHelper();


	if (isset($_SESSION['facebook_access_token']))
	{
		$accessToken = $_SESSION['facebook_access_token'];
	}
	else
	{
  		$accessToken = $helper->getAccessToken();
	}

	if (isset($accessToken)) {

		if (isset($_SESSION['facebook_access_token'])) {
			$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		} 
		else
		{
		// getting short-lived access token
		$_SESSION['facebook_access_token'] = (string) $accessToken;

	  	// OAuth 2.0 client handler
		$oAuth2Client = $fb->getOAuth2Client();

		// Exchanges a short-lived access token for a long-lived one
		$longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);

		$_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

		// setting default access token to be used in script
		$fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
		}
		
		header("location:home.php");
  	} 
  	else
  	{
  		
  		$permissions = array('scope' => 'email,public_profile,user_photos');
		$loginUrl = $helper->getLoginUrl('http://localhost/facebook-album/', $permissions);
		echo '<a class="btn btn-sm btn-primary btn-fb" href="' . $loginUrl . '">Log in with Facebook!</a>';
	}
	?>
	</div>