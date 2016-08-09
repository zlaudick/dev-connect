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

	/**
	 * accessor method for image path
	 *
	 * @return string value of image path
	 **/
	public function getImagePath() {
		return($this->imagePath);
	}

	/**
	 * mutator method for image path
	 *
	 * @param string $newImagePath new value of image path
	 * @throws \InvalidArgumentException if $newImagePath is not a string or insecure
	 * @throws \RangeException if $newImagePath is > 255 characters
	 * @throws \TypeError if $newImagePath is not a string
	 **/
	public function setImagePath(string $newImagePath) {
		//verify image path is secure
		$newImagePath = trim($newImagePath);
		$newImagePath = filter_var($newImagePath, FILTER_SANITIZE_STRING);
		if(empty($newImagePath) === true) {
			throw(new \InvalidArgumentException("image path is empty or insecure"));
		}

		//verify the image path will fit in the database
		if(strlen($newImagePath) > 255) {
			throw(new \RangeException("image path is too many characters"));
		}

		//store the image path
		$this->imagePath = $newImagePath;
	}

	/**
	 * accessor method for image type
	 *
	 * @return string value of image type
	 **/
	public function getImageType() {
		return($this->imageType);
	}

	/**
	 * mutator method for image type
	 *
	 * @param string $newImageType new value of image type
	 * @throws \InvalidArgumentException if $newImageType is not a string or insecure
	 * @throws \RangeException if $newImageType is > 32 characters
	 * @throws \TypeError if $newImageType is not a string
	 **/
	public function setImageType(string $newImageType) {
		//verify image type is secure
		$newImageType = trim($newImageType);
		$newImageType = filter_var($newImageType, FILTER_SANITIZE_STRING);
		if(empty($newImageType) === true) {
			throw(new \InvalidArgumentException("image type is empty or insecure"));
		}

		//verify the image type will fit in the database
		if(strlen($newImageType) > 32) {
			throw(new \RangeException("image type is too many characters"));
		}

		//store the image type
		$this->imageType = $newImageType;
	}

	/**
	 * inserts this image into MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function insert(\PDO $pdo) {
		//enforce the imageId is null (don't insert an image that already exists)
		if($this->imageId !== null) {
			throw(new \PDOException("not a new image"));
		}

		//create query template
		$query = "INSERT INTO image(imagePath, imageType) VALUES(:imagePath, :imageType)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$parameters = ["imagePath" => $this->imagePath, "imageType" => $this->imageType];
		$statement->execute($parameters);

		//update the null image id with what MySQL just gave us
		$this->imageId = intval($pdo->lastInsertId());
	}

	/**
	 * deletes this image from MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function delete(\PDO $pdo) {
		//enforce the imageId is not null (don't delete an image that hasn't been inserted)
		if($this->imageId === null) {
			throw(new \PDOException("unable to delete an image that does not exist"));
		}

		//create query template
		$query = "DELETE FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$parameters = ["imageId" => $this->imageId];
		$statement->execute($parameters);
	}

	/**
	 * updates this image in MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/
	public function update(\PDO $pdo) {
		//enforce that the image Id is not null (don't update an image that hasn't been inserted)
		if($this->imageId === null) {
			throw(new \PDOException("unable to update an image that does not exist"));
		}

		//create query template
		$query = "UPDATE image SET imagePath = :imagePath, imageType = :imageType WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the placeholders in the template
		$parameters = ["imagePath" => $this->imagePath, "imageType" => $this->imageType, "imageId" => $this->imageId];
		$statement->execute($parameters);
	}

	/**
	 * gets the image by image path
	 *
	 * @param \PDO $pdo connection object
	 * @param string $imagePath image path to search for
	 * @return \SplFixedArray SplFixedArray of images found
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getImageByImagePath(\PDO $pdo, string $imagePath) {
		//sanitize the description before searching
		$imagePath = trim($imagePath);
		$imagePath = filter_var($imagePath, FILTER_SANITIZE_STRING);
		if(empty($imagePath) === true) {
			throw(new \PDOException("image path is invalid"));
		}

		//create query template
		$query = "SELECT imageId, imagePath, imageType FROM image WHERE imagePath LIKE :imagePath";
		$statement = $pdo->prepare($query);

		//bind the image path to the placeholder in the template
		$imagePath = "%$imagePath%";
		$parameters = ["imagePath" => $imagePath];
		$statement->execute($parameters);

		//build an array of images
		$images = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$image = new Image($row["imageId"], $row["imagePath"], $row["imageType"]);
				$images[$images->key()] = $image;
				$images->next();
			} catch(\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($images);
	}

	/**
	 * gets the image by image id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param int $imageId image id to search for
	 * @return Image|null Image found or null if not found
	 * @throws \PDOException when MySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getImageByImageId (\PDO $pdo, int $imageId) {
		//sanitize the imageId before searching
		if($imageId <= 0) {
			throw(new \PDOException("imageId is not positive"));
		}

		//create query template
		$query = "SELECT imageId, imagePath, imageType FROM image WHERE imageId = :imageId";
		$statement = $pdo->prepare($query);

		//bind the image id to the place holder in the template
		$parameters = ["imageId" => $imageId];
		$statement->execute($parameters);

		//grab the image from MySQL
		try {
			$image = null;
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			$row = $statement->fetch();
			if($row !== false) {
				$image = new Image($row["imageId"], $row["imagePath"], $row["imageType"]);
			}
		} catch(\Exception $exception) {
			//if the row couldn't be converted, rethrow it
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return($image);
	}

	/**
	 * gets all images
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return \SplFixedArray SplFixedArray of images found or null if not found
	 * @throws \PDOException when MySQL relates errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getAllImages (\PDO $pdo) {
		//create query template
		$query = "SELECT imageId, imagePath, imageType FROM image";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//build an array of images
		$images = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row = $statement->fetch()) !== false) {
			try {
				$image = new Image($row["imageId"], $row["imagePath"], $row["imageType"]);
				$image[$images->key()] = $image;
				$images->next();
			} catch(\Exception $exception) {
				//if the row could not be converted, rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($images);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting in state variables to serialize
	 **/
	public function jsonSerialize() {
		//TODO: Implement jsonSerialize() method.
	}
}