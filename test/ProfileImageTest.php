<?php

namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\Test\{Profile, Image};

// grab the project test parameters
require_once(DevConnectTest.php);

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/publichtml/php/classes/autoload.php");

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
		$this->profile = new Profile(null, "Zac", "zlaudick@cnm.edu");
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
		$pdoProfileImage = ProfileImage::getProfileImagebyProfileImageProfileIdAndImageId($this->getPDO(), $profileImage->getProfileImageProfileId(), $profileImage->getProfileImageImageId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profileImage"));
		$this->assertEquals($pdoProfileImage->getProfileImageProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProfileImage->getProfileImageImageId(), $this->image->getImageId());
	}
}