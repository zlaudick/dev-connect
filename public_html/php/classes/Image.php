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
	 * @param int|null $newImageId id of this Image if it exists or null if a new Image
	 * @param string $newImagePath string containing path of the Image
	 * @param string $newImageType string containing file type of the Image
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (i.e., strings are too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other error occurs
	 **/
	public function __construct(int $newImageId = null, string $newImagePath, string $newImageType) {
		try {
			$this->setImageId($newImageId);
			$this->setImagePath($newImagePath);
			$this->setImageType($newImageType);
		} catch(\InvalidArgumentException $invalidArgument){
				//rethrow the exception to the caller
				throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
				//rethrow the exception to the caller
				throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
				//rethrow the exception to the caller
				throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
				//rethrow the exception to the caller
				throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}
	/**
	 * accessor method for image id
	 *
	 * @return int|null value of image id
	 **/
	public function getImageId() {
		return($this->imageId);
	}

	/**
	 * mutator method for image id
	 *
	 * @param int|null $newImageId new value of image id
	 * @throws \RangeException if $newImageId id is not positive
	 * @throws \TypeError if $newImageId is not an integer
	 **/
	public function setImageId(int $newImageId = null) {
		//base case: if the image id is null, this is a new image without a MySQL assigned id yet
		if($newImageId === null) {
			$this->imageId = null;
			return;
		}

		//verify the image id is positive
		if($newImageId <= 0) {
			throw(new \RangeException("image id is not positive"));
		}

		//convert and store image id
		$this->imageId = $newImageId;
	}




}