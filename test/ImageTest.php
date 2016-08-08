<?php
namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\Test\DevConnectTest;

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
		$this->assertEquals($pdoImage->getImageId(), $this->image->getImageId());
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
		$image = new Image(DataDesignTest::INVALID_KEY, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());
	}

	/**
	 * test inserting an Image, editing it, and then updating it
	 **/
	public function testUpdateValidImage() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCound("image");

		//create a new Image and insert into MySQL
		$image = new Image(null, $this->VALID_IMAGEPATH, $this->VALID_IMAGETYPE);
		$image->insert($this->getPDO());

		//edit the Image and update it in MySQL
		$image->setImagePath($this->VALID_IMAGEPATH2);
		$image->update($this->getPDO());

		//grab the data from MySQL and enforce that the fields match our expectations
	}



}
