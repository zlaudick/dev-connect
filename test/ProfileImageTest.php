<?php

namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\{ProfileImage, Profile, Image};

// grab the project test parameters
require_once("DevConnectTest.php");

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");

/**
 * Full PHPUnit test for the ProfileImage class
 *
 * This is a complete PHPUnit test of the ProfileImage class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs
 *
 * @see ProfileImage
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class ProfileImageTest extends DevConnectTest {
	/**
	 * profile that uses the ProfileImage
	 * @var Profile profile
	 **/
	protected $profile = null;

	/**
	 * image class that stores the ProfileImage
	 * @var Image image
	 **/
	protected $image = null;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp method first
		parent::setUp();

		// create and insert a Profile to own the test Profile Image
		$this->profile = new Profile(null, "Q", "12345678901234567890123456789012", false, 1, null, "content", "foo@bar.com", "1234567890123456789012345678901234567890123456789012345678901234", "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678", "Abq, NM", "Zac", "1234567890123456789012345678901234567890123456789012345678901234");
		$this->profile->insert($this->getPDO());

		// create and insert an Image to own the test Profile Image
		$this->image = new Image(null, "filename", "image/jpg");
		$this->image->insert($this->getPDO());
	}

	/**
	 * test inserting a valid ProfileImage composite key and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfileImage() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profileImage");

		// create a new profileImage and insert into mySQL
		$profileImage = new ProfileImage($this->profile->getProfileId(), $this->image->getImageId());
		$profileImage->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfileImage = ProfileImage::getProfileImageByProfileImageProfileIdAndImageId($this->getPDO(), $profileImage->getProfileImageProfileId(), $profileImage->getProfileImageImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profileImage"));
		$this->assertEquals($pdoProfileImage->getProfileImageProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProfileImage->getProfileImageImageId(), $this->image->getImageId());
	}

	/**
	 * test inserting a ProfileImage that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidProfileImage() {
		// create an invalid Profile Image and try to insert it
		$profileImage = new ProfileImage(DevConnectTest::INVALID_KEY, DevConnectTest::INVALID_KEY);
		$profileImage->insert($this->getPDO());
	}

	/**
	 * test creating a ProfileImage using a profileId and imageId and then deleting it
	 **/
	public function testDeleteValidProfileImageProfileIdAndImageId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profileImage");

		// create a new profileImage and insert into mySQL
		$profileImage = new ProfileImage($this->profile->getProfileId(), $this->image->getImageId());
		$profileImage->insert($this->getPDO());

		// delete the profileImage from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profileImage"));
		$profileImage->delete($this->getPDO());

		// grab the data from mySQL and enforce the profileImage does not exist
		$pdoProfileImage = ProfileImage::getProfileImageByProfileImageProfileIdAndImageId($this->getPDO(), $profileImage->getProfileImageProfileId(), $profileImage->getProfileImageImageId());
		$this->assertNull($pdoProfileImage);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("profileImage"));
	}

	/**
	 * test deleting a profileImage that does not exist
	 **/
	public function testDeleteInvalidProfileImage() {
		// create a ProfileImage and try to delete it without actually inserting it
		$profileImage = new ProfileImage($this->profile->getProfileId(), $this->image->getImageId());
		$profileImage->delete($this->getPDO());
	}

	/**
	 * test grabbing a ProfileImage by a ProfileId and ImageId that does not exist
	 **/
	public function testGetProfileImageByInvalidProfileImageProfileIdAndImageId() {
		// grab a profile image profileId and imageId that exceed the maximum allowable profileId and imageId
		$profileImage = ProfileImage::getProfileImageByProfileImageProfileIdAndImageId($this->getPDO(), DevConnectTest::INVALID_KEY, DevConnectTest::INVALID_KEY);
		$this->assertNull($profileImage);
	}

	/**
	 * test grabbing all ProfileImage Primary Composite Keys
	 **/
	public function testGetAllValidProfileImage() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profileImage");

		// create a new profileImage and insert into mySQL
		$profileImage = new ProfileImage($this->profile->getProfileId(), $this->image->getImageId());
		$profileImage->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = ProfileImage::getAllProfileImages($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profileImage"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DevConnect\\ProfileImage", $results);

		// grab the result from the array and validate it
		$pdoProfileImage = $results[0];
		$this->assertEquals($pdoProfileImage->getProfileImageProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProfileImage->getProfileImageImageId(), $this->image->getImageId());
	}
}