<?php 
session_start();
ob_start();

	//admin site variables
	define('URL','http://localhost/facebook-album/');
	define('LIBRARY','http://localhost/facebook-album/library/');
	define('ASSETS','http://localhost/facebook-album/library/assets/');
	define('OTHER','http://localhost/facebook-album/other');
	
	define('IMAGES',ASSETS.'images/');
	define('MODEL',URL.'model/');
	define('VIEW',URL.'view/');
	define('CSS',ASSETS.'css/');
	define('JS',ASSETS.'js/');
	define('FANCYBOX',ASSETS.'fancybox/');
	define('SITE_NAME','facebook-album');
	
	
	//admin directory variblebby
	define('ROOT_FOLD',$_SERVER['DOCUMENT_ROOT'].'/facebook-album/');
	
	define('ASSETS_FOLD',ROOT_FOLD.'library/assets/');
	define('MODEL_FOLD',ROOT_FOLD.'model/');
	define('VIEW_FOLD',ROOT_FOLD.'view/');
	define('STYLE_FOLD',ROOT_FOLD.'library/');
	define('FUNC_FOLD',ROOT_FOLD.'functions/');
	define('IMAGE_FOLD',STYLE_FOLD.'assets/images/');
	define('CSS_FOLD',STYLE_FOLD.'css/');
	define('JS_FOLD',STYLE_FOLD.'js/');
	

require_once ASSETS_FOLD.'facebook-php-sdk/src/Facebook/autoload.php';
require_once ASSETS_FOLD.'facebook-php-sdk/src/Facebook/facebook.php';
require_once ASSETS_FOLD.'facebook-php-sdk/src/Facebook/Entities/AccessToken.php';

$fb = new Facebook\Facebook([
  'app_id' => '282144482162248',
  'app_secret' => '51e40078eed12220ffcac765882bbc85',
  'default_graph_version' => 'v2.7',
]);

?>
