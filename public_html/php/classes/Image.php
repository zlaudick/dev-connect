<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * Image class for profile images
 *
 * This Image can store the image path and file type of a DevConnect profile's pictures
 *
 * @author Devon Beets <dbeetzz@gmail.com>
 * @version 3.0.0
 **/
class Image implements \JsonSerializable {
	/**
	 * id for this Image, this is the primary key
	 * @var int $imageId
	 **/
	private $imageId;
	/**
	 * path for this Image
	 * @var string $imagePath
	 **/
	private $imagePath;
	/**
	 * file type of this Image
	 * @var string $imageType
	 **/
	private $imageType;

	/**
	 * constructor for this Image
	 *
	 * @param int|null
	 **/
}