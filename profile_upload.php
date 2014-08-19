<?php 
define("SITE_ROOT",dirname(__FILE__));
$data = array();
$good_ext = array('jpg','jpeg','gif','png');

	$error = false;
	$files = array();
	$error_message = '';
	$uploaddir = SITE_ROOT.DIRECTORY_SEPARATOR.'profile_images';
	$profiledir = "/profile_images";
	
	foreach($_FILES as $file) 
	{
		$ext = strtolower(pathinfo(basename($file['name']), PATHINFO_EXTENSION));
		
		// Check if there was an error in the upload process
		if ($file['error']) { 
			$error = true;
			$error_message = $file['error'];
			continue;
		}

		// Check to see if it is a valid image.
		if (!in_array($ext,$good_ext))
		{
			$error = true;
			$error_message = "Sorry, $file[name] is not an image file. Please upload a JPEG, GIF or PNG.";
			continue;
		}

		$new_name = (isset($_POST['user_id']) ? $_POST['user_id'] : $file['name']).'.jpg';

		$destination =  $uploaddir.DIRECTORY_SEPARATOR.basename($new_name);

		if (!in_array($ext,array('jpg','jpeg')) && !extension_loaded('imagick'))
		{
			$error = true;
			$error_message = "Could not convert this image to JPEG. Perhpas try uploading a JPEG image.";
		} 
		if (extension_loaded('imagick'))
		{
			try {
				$img = new IMagick($file['tmp_name']);
				if (!in_array($ext,'jpg','jpeg'))
				{
					// set the background to white
					$img->setImageBackgroundColor('White');
					// flattens multiple layers
					$img = $img->flattenImages();
				}
				// the output format
				$img->setImageFormat('jpg');
				// resize the image
				$img->scaleImage(100,100,false);
				// save to disk
				$img->writeImage($destination);
				// cleanup
				$img->clear();
				$img->destroy();
				$files[] = $profiledir.DIRECTORY_SEPARATOR.basename($new_name);
			}
			catch(ImagickException $e)
			{
				$error = true;
				$error_message = "Something went wrong uploading the file. ".$e->getMessage();
			}
		}
		else if (in_array($ext,array('jpg','jpeg')))
		{
			if (move_uploaded_file($file['tmp_name'], $destination)) {
				$files[] = $profiledir.DIRECTORY_SEPARATOR.basename($new_name);
			} else {
				$error = true;
				$error_message = "Error moving file to: ".$destination;
			}
		}

	}

	$data = ($error) ? array('error' => $error_message) : array('files' => $files);
	
	//$data = array('success' => 'Form was submitted', 'formData' => $_POST);
 
echo json_encode($data);
?>