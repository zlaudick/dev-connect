<?php

require_once dirname(__DIR__, 2) . "/classes/autoload.php";
/**this don't work still**/
require_once dirname(__DIR__, 2) . "/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

use Edu\Cnm\DevConnect\Image;

/**
 * API for the Image class
 *
 * @author Devon Beets <dbeetzz@gmail.com> based on code by Derek Mauldin
 **/

//verify the session, start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {
	//grab the MySQL connection
	$pdo = connectToEncryptedMySQL("etc/apache2/capstone-mysql/dev-connect.ini");

	//determine which HTTP method was used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
	$imagePath = filter_input(INPUT_GET, "imagePath", FILTER_SANITIZE_STRING);
	$imageType = filter_input(INPUT_GET, "imageType", FILTER_SANITIZE_STRING);


	//make sure the id is valid for methods that require it
	if(($method === "DELETE") && (empty($id) === true || $id < 0)) {
		throw (new InvalidArgumentException("id cannot be empty or negative", 405));
	}

	//handle GET request, if id is present, return that image
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific image and update reply
		if(empty($id) === false) {
			$image = Image::getImageByImageId($pdo, $id);
			if($image !== null) {
				$reply->data = $image;
			}
		} elseif(empty($imagePath) === false) {
			$images = Image::getImageByImagePath($pdo, $imagePath);
			if($images !== null) {
				$reply->data = $images;
			}
		}
	} elseif($method === "POST") {

		verifyXsrf();
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//make sure image content is available
		if(empty($requestObject->imagePath) === true) {
			throw(new \InvalidArgumentException("No content for the image", 405));
		}
		//make a check to make sure only a user can only upload to their own account
		//some code here to to dat ^

		//create arrays for valid image extensions and valid image MIME types
		$validExtensions = [".jpg,", ".jpeg", ".png"];
		$validTypes = ["image/jpg", "image/jpeg", "image/png"];

		//assign variables to the user image name, MIME type, and image extension
		$tempImagePath = $_FILES["imagePath"]["tmp_name"];
		$imageType = $_FILES["imageType"]["type"];
		$imageFileExtension = strtolower(strchr($_FILES["profileImage"]["name"], "."));

		//check to ensure the file has correct extension and MIME type
		if(!in_array($imageFileExtension, $validExtensions) || (!in_array($imageType, $validTypes))) {
			throw (new \InvalidArgumentException("This is not a valid image"));
		}

		//image creation if file is .jpg .jpeg or .png
		if($imageFileExtension === ".jpg" || $imageFileExtension === ".jpeg") {
			$sanitizedUserImage = imagecreatefromjpeg($tempImagePath);
		} elseif($imageFileExtension === ".png") {
			$sanitizedUserImage = imagecreatefrompng($tempImagePath);
		} else {
			throw(new InvalidArgumentException("This image is not valid."));
		}


		//sanitize the profile image
		if($sanitizedImagePath === false) {
			throw (new \InvalidArgumentException("This image is not valid."));
		}

		//image scale to 500px width, leave height auto
		$sanitizedImagePath = imagescale($sanitizedImagePath, 500);


		//rename the file to make it unique using hash
		$newImagePath = "/var/www/html/public_html/dev-connect" . hash("ripemd160", microtime(true) + random_int(0, 4294967296));

		//finalize image creation after sanitization, resizing and unique renaming
		if($imageFileExtension === ".jpg" || $imageFileExtension === ".jpeg") {
			$createdProperly = imagejpeg($sanitizedUserImage, $newImagePath);
		} elseif($imageFileExtension === ".png") {
			$createdProperly = imagepng($sanitizedUserImage, $newImagePath);
		} else {
			throw (new \InvalidArgumentException("This image is not valid"));
		}

		//put the new image into the database
		if($createdProperly === true) {
			$image = new Image(null, $newImagePath, $imageType);
			$image->insert($pdo);
		}

		//update reply
		$reply->message = "Image created OK";

	} elseif($method === "DELETE") {
		verifyXsrf();

		//retrieve the image to be deleted
		$image = Image::getImageByImageId($pdo, $id);
		if($image === null) {
			throw(new \RuntimeException("Image does not exist", 404));
		}

		unlink($image->getImagePath());

		//delete the image
		$image->delete($pdo);

		//update reply
		$reply->message = "Image deleted OK";
	} else {
		throw (new \InvalidArgumentException("Invalid HTTP method request"));
	}

	//update reply with exception information
} catch(Exception $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
	$reply->trace = $exception->getTraceAsString();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $exception->getMessage();
}

header("Content type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

//encode and return the reply to the front end caller
echo json_encode($reply);
