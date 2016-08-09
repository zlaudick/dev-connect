<?php
namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\{Image};

//grab the project test parameters
require_once("DevConnectTest.php");

//grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");

/**
 * Full PHPUnit test for the Image class
 *
 * This is a complete PHPUnit test of the Image class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Image
 * @author Devon Beets <dbeets@cnm.edu>
 **/

class ImageTest extends DevConnectTest {
	/**
	 * path of the Image
	 * @var string $VALID_IMAGEPATH
	 **/
	protected $VALID_IMAGEPATH = "myPictureIsWorking";
	/**
	 * path of the updated Image
	 * @var string $VALID_IMAGEPATH2
	 **/
	protected $VALID_IMAGEPATH2 = "myOtherPictureIsWorkingToo";
	/**
	 * type of the Image
	 * @var string $VALID_IMAGETYPE
	 **/
	protected $VALID_IMAGETYPE = "jpeg";
	/**
	 * type of the updated Image
	 * @var string $VALID_IMAGETYPE2
	 **/
	protected $VALID_IMAGETYPE2 = "png";
	/**
	 * Image Id that associates with the Image
	 **/
	protected $image = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		//run the default setUp() method first
		parent::setUp();
	}

	/**
	 * test inserting a valid Image and verify that actual MySQL data matches
	 **/
	public function testInsertValidImage() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");

		//create a new Image and insert into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//grab the data from MySQL and enforce that the fields match our expectations
		$pdoImage = Image::getImageByImageId($this->getPDO(), $image->getImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));
		$this->assertEquals($pdoImage->getImageId(), $image->getImageId());
		$this->assertEquals($pdoImage->getImagePath(), $this->VALID_IMAGEPATH);
		$this->assertEquals($pdoImage->getImageType(), $this->VALID_IMAGETYPE);
	}

	/**
	 * test inserting an Image that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidImage() {
		//create an Image with a non null ImageId and watch it fail
		$image = new Image(DevConnectTest::INVALID_KEY, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());
	}

	/**
	 * test inserting an Image, editing it, and then updating it
	 **/
	public function testUpdateValidImage() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");

		//create a new Image and insert into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//edit the Image and update it in MySQL
		$image->setImagePath($this->VALID_IMAGEPATH2);
		$image->setImageType($this->VALID_IMAGETYPE2);
		$image->update($this->getPDO());

		//grab the data from MySQL and enforce that the fields match our expectations
		$pdoImage = Image::getImageByImageId($this->getPDO(), $image->getImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));
		$this->assertEquals($pdoImage->getImageId(), $image->getImageId());
		$this->assertEquals($pdoImage->getImagePath(), $this->VALID_IMAGEPATH2);
		$this->assertEquals($pdoImage->getImageType(), $this->VALID_IMAGETYPE2);
	}

	/**
	 * test inserting an Image then deleting it
	 **/
	public function testDeleteValidImage() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");

		//create a new Image and insert into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//delete the Image from MySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));
		$image->delete($this->getPDO());

		//grab the data from MySQL and enforce that the fields match our expectations
		$pdoImage = Image::getImageByImageId($this->getPDO(), $image->getImageId());
		$this->assertNull($pdoImage);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("image"));
	}

	/**
	 * test deleting an Image that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidImage() {
		//create an Image and try to delete it without actually inserting it
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->delete($this->getPDO());
	}

	/**
	 * test grabbing an Image by image path
	 **/
	public function testGetValidImageByImagePath () {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");

		//create a new Image and insert it into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//grab the data from MySQL and enforce the fields match our expectations
		$results = Image::getImageByImagePath($this->getPDO(), $image->getImagePath());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DevConnect\\Image", $results);

		//grab the results from the array and validate it
		$pdoImage = $results[0];
		$this->assertEquals($pdoImage->getImageId(), $image->getImageId());
		$this->assertEquals($pdoImage->getImagePath(), $this->VALID_IMAGEPATH);
		$this->assertEquals($pdoImage->getImageType(), $this->VALID_IMAGETYPE);
	}

	/**
	 * test grabbing an Image by an image path that does not exist
	 **/
	public function testGetInvalidImageByImagePath() {
		//grab an image by searching for content that does not exist
		$image = Image::getImageByImagePath($this->getPDO(), "this image is not found");
		$this->assertCount(0, $image);
	}

	/**
	 * tests the JSON serialization
	 **/
	public function testJsonSerialize() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("image");

		//create a new Image and insert into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//grab the data from MySQL and enforce that the JSON data matches our expectations
		$pdoImage = Image::getImageByImageId($this->getPDO(), $image->getImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("image"));

		$imageId = $image->getImageId();
		$expectedJson = <<< EOF
{"imageId": $imageId, "imagePath": "$this->VALID_IMAGEPATH", "imageType": "$this->VALID_IMAGETYPE"}
EOF;
		$this->assertJsonStringEqualsJsonString($expectedJson, json_encode($pdoImage));

	}
}
