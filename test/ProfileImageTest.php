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
}