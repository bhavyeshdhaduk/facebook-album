<?php
require_once('config.php');

use Facebook\FacebookRequest;
use Facebook\GraphSessionInfo;
use Facebook\FacebookSession;

$albums = $_GET["selected_albums"];

	if ( isset( $_GET['selected_albums'] ) and count( $_GET['selected_albums'] ) > 0) {

		$selected_albums = explode(",", $_GET['selected_albums']);
		$uniq = uniqid();
		$album_download_directory = 'library/assets/images/fb-albums/'.$uniq.'/';
		mkdir($album_download_directory, 0777);
		

		foreach ( $selected_albums as $selected_album ) 
		{

				try {
				
				  $response = $fb->get('/'.$selected_album.'?fields=photos.fields(source),name', $_SESSION['facebook_access_token']);
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

				 	$graph = $response->getGraphObject();
				 	$albumname = $graph['name'].time();

					$album_directory = $album_download_directory.$albumname;
					if ( !file_exists( $album_directory ) ) {
						mkdir($album_directory, 0777);
					}


						$i = 1;
						foreach ( $graph['photos'] as $album_photo ) {
							file_put_contents( $album_directory.'/'.$i.".jpg", fopen( $album_photo['source'], 'r') );
							$i++;
						}		
		}

				$rootPath = $album_download_directory;
				$target = 'library/assets/images/fb-albums/'.$uniq.'.zip';
				$directory = realpath($rootPath);
				//make zip 
				$result = create_zip($directory,$target);


				if($result == 'success'){
					echo $uniq;
				}
				else{
					echo 'unsuccess';
				}
	}

	 function create_zip($directory,$target) {

		$zip = new ZipArchive();
		$zip->open($target, ZipArchive::CREATE | ZipArchive::OVERWRITE);

		// Create recursive directory iterator
		/** @var SplFileInfo[] $files */
		$files = new RecursiveIteratorIterator(
		    new RecursiveDirectoryIterator($directory),
		    RecursiveIteratorIterator::LEAVES_ONLY
		);

		foreach ($files as $name => $file)
		{
		    // Skip directories (they would be added automatically)
		    if (!$file->isDir())
		    {
		        // Get real and relative path for current file
		        $filePath = $file->getRealPath();
		        $relativePath = substr($filePath, strlen($directory) + 1);

		        // Add current file to archive
		        $zip->addFile($filePath, $relativePath);
		    }
		}

				// Zip archive will be created only after closing object
				$zip->close();
					chmod($target, 0777);
				return file_exists($target);

				if (file_exists($target)) {
					return 'success';
				}
				else 
				{
					return 'unsuccess';
				}
		 }
?>
