<?php

namespace Edu\Cnm\DevConnect\Test;

use Edu\Cnm\DevConnect\Test\DevConnectTest;

// grab the project test parameters
require_once(DevConnectTest.php);

// grab the class under scrutiny
require_once(dirname(__DIR__) . "/publichtml/php/classes/autoload.php");

/**
 * Full PHPUnit test for the Profile class
 *
 * This is a complete PHPUnit test of the Profile class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs
 *
 * @see Profile
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
Class ProfileTest extends DevConnectTest {
	/**
	 * account type of the Profile
	 * @var string $VALID_PROFILEACCOUNTTYPE
	 **/
	protected $VALID_PROFILEACCOUNTTYPE = null;
	/**
	 * activation token of the Profile
	 * @var string $VALID_PROFILEACTIVATIONTOKEN
	 **/
	protected $VALID_PROFILEACTIVATIONTOKEN = null;
	/**
	 * activation token of the Profile
	 * @var string $VALID_PROFILEACTIVATIONTOKEN2
	 **/
	protected $VALID_PROFILEACTIVATIONTOKEN2 = null;
	/**
	 * approved value of the Profile
	 * @var boolean $VALID_PROFILEAPPROVED
	 **/
	protected $VALID_PROFILEAPPROVED = null;
	/**
	 * approvedById of the Profile
	 * @var int $VALID_PROFILEAPPROVEDBYID
	 **/
	protected $VALID_PROILEAPPROVEDBYID = null;
	/**
	 * datetime stamp of the Profile approval
	 * @var DateTime $VALID_PROFILEAPPROVEDDATETIME
	 **/
	protected $VALID_PROFILEAPPROVEDDATETIME = null;
	/**
	 * content of the Profile
	 * @var string $VALID_PROFILECONTENT
	 **/
	protected $VALID_PROFILECONTENT = "PHPUnit test passing";
	/**
	 * updated content of the Profile
	 * @var string $VALID_PROFILECONTENT2
	 **/
	protected $VALID_PROFILECONTENT2 = "PHPUnit test still passing";
	/**
	 * email of the profile
	 * @var string $VALID_PROFILEEMAIL
	 **/
	protected $VALID_PROFILEEMAIL = "foo@bar.com";
	/**
	 * email of the profile
	 * @var string $VALID_PROFILEEMAIL2
	 **/
	protected $VALID_PROFILEEMAIL2 = "bar@foo.com";
	/**
	 * github access token of the Profile
	 * @var string $VALID_PROFILEGITHUBACCESSTOKEN
	 **/
	protected $VALID_PROFILEGITHUBACCESSTOKEN = null;
	/**
	 * @var Profile Hash
	 **/
	private $hash;
	/**
	 * location of the Profile
	 * @var string $VALID_PROFILELOCATION
	 **/
	protected $VALID_PROFILELOCATION = "Albuquerque, NM";
	/**
	 * updated location of the Profile
	 * @var string $VALID_PROFILELOCATION2
	 **/
	protected $VALID_PROFILELOCATION2 = "Santa Fe, NM";
	/**
	 * name of the Profile
	 * @var string $VALID_PROFILENAME
	 **/
	protected $VALID_PROFILENAME = "zlaudick";
	/**
	 * @var Profile Salt
	 **/
	private $salt;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp method first
		parent::setUp();

		// create and insert a Profile to own the account
		$this->VALID_PROFILEACTIVATIONTOKEN = bin2hex(random_bytes(16));
		$this->VALID_PROFILEACTIVATIONTOKEN2 = bin2hex(random_bytes(16));
		$this->salt = bin2hex(random_bytes(32));
		$this->hash = hash_pbkdf2("sha512", "123456", $this->salt, 4096, 128);
	}

	/**
	 * test inserting a valid Profile and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("Profile");

		// create a new Profile and insert it into mySQL
		$profile = new Profile(null, $this->VALID_PROFILEACCOUNTTYPE, $this->VALID_PROFILEACTIVATIONTOKEN, $this->VALID_PROFILEAPPROVED, $this->VALID_PROILEAPPROVEDBYID, $this->VALID_PROFILEAPPROVEDDATETIME, $this->VALID_PROFILECONTENT, $this->VALID_PROFILEEMAIL, $this->VALID_PROFILEGITHUBACCESSTOKEN, $this->hash, $this->VALID_PROFILELOCATION, $this->VALID_PROFILENAME, $this->salt);
		$profile->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileAccountType(), $this->VALID_PROFILEACCOUNTTYPE);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILEACTIVATIONTOKEN);
		$this->assertEquals($pdoProfile->getProfileApproved(), $this->VALID_PROFILEAPPROVED);
		$this->assertEquals($pdoProfile->getProfileApprovedById(), $this->VALID_PROILEAPPROVEDBYID);
		$this->assertEquals($pdoProfile->getProfileApprovedDateTime(), $this->VALID_PROFILEAPPROVEDDATETIME);
		$this->assertEquals($pdoProfile->getProfileContent(), $this->VALID_PROFILECONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILEEMAIL);
		$this->assertEquals($pdoProfile->getProfileGithubAccessToken(), $this->VALID_PROFILEGITHUBACCESSTOKEN);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->hash);
		$this->assertEquals($pdoProfile->getProfileLocation(), $this->VALID_PROFILELOCATION);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILENAME);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->salt);
	}

	/**
	 * test inserting a Profile that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidProfile() {
		// create a Profile with a non null profile id and watch it fail
		$profile = new Profile(DevConnectTest::INVALID_KEY, $this->VALID_PROFILEACCOUNTTYPE, $this->VALID_PROFILEACTIVATIONTOKEN, $this->VALID_PROFILEAPPROVED, $this->VALID_PROILEAPPROVEDBYID, $this->VALID_PROFILEAPPROVEDDATETIME, $this->VALID_PROFILECONTENT, $this->VALID_PROFILEEMAIL, $this->VALID_PROFILEGITHUBACCESSTOKEN, $this->hash, $this->VALID_PROFILELOCATION, $this->VALID_PROFILENAME, $this->salt);
		$profile->insert($this->getPDO());
	}

	/**
	 * test inserting a Profile, editing it, and then updating it
	 **/
	public function testUpdateValidProfile() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("profile");

		// create a new Profile and insert it in mySQL
		$profile = new Profile(null, $this->VALID_PROFILEACCOUNTTYPE, $this->VALID_PROFILEACTIVATIONTOKEN, $this->VALID_PROFILEAPPROVED, $this->VALID_PROILEAPPROVEDBYID, $this->VALID_PROFILEAPPROVEDDATETIME, $this->VALID_PROFILECONTENT, $this->VALID_PROFILEEMAIL, $this->VALID_PROFILEGITHUBACCESSTOKEN, $this->hash, $this->VALID_PROFILELOCATION, $this->VALID_PROFILENAME, $this->salt);
		$profile->insert($this->getPDO());

		// edit the Profile and update it in mySQL
		$profile->setProfileActivationToken($this->VALID_PROFILEACTIVATIONTOKEN2);
		$profile->setProfileContent($this->VALID_PROFILECONTENT2);
		$profile->setProfileEmail($this->VALID_PROFILEEMAIL2);
		$profile->setProfileHash($this->hash);
		$profile->setProfileLocation($this->VALID_PROFILELOCATION2);
		$profile->setProfileSalt($this->salt);

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this->getPDO(), $profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("profile"));
		$this->assertEquals($pdoProfile->getProfileAccountType(), $this->VALID_PROFILEACCOUNTTYPE);
		$this->assertEquals($pdoProfile->getProfileActivationToken(), $this->VALID_PROFILEACTIVATIONTOKEN);
		$this->assertEquals($pdoProfile->getProfileApproved(), $this->VALID_PROFILEAPPROVED);
		$this->assertEquals($pdoProfile->getProfileApprovedById(), $this->VALID_PROILEAPPROVEDBYID);
		$this->assertEquals($pdoProfile->getProfileApprovedDateTime(), $this->VALID_PROFILEAPPROVEDDATETIME);
		$this->assertEquals($pdoProfile->getProfileContent(), $this->VALID_PROFILECONTENT);
		$this->assertEquals($pdoProfile->getProfileEmail(), $this->VALID_PROFILEEMAIL);
		$this->assertEquals($pdoProfile->getProfileGithubAccessToken(), $this->VALID_PROFILEGITHUBACCESSTOKEN);
		$this->assertEquals($pdoProfile->getProfileHash(), $this->hash);
		$this->assertEquals($pdoProfile->getProfileLocation(), $this->VALID_PROFILELOCATION);
		$this->assertEquals($pdoProfile->getProfileName(), $this->VALID_PROFILENAME);
		$this->assertEquals($pdoProfile->getProfileSalt(), $this->salt);
	}

	/**
	 * test updating a Profile that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testUpdateInvalidProfile() {
		// create a Profile with a null profile id and watch it fail
		$profile = new Profile(null, $this->VALID_PROFILEACCOUNTTYPE, $this->VALID_PROFILEACTIVATIONTOKEN, $this->VALID_PROFILEAPPROVED, $this->VALID_PROILEAPPROVEDBYID, $this->VALID_PROFILEAPPROVEDDATETIME, $this->VALID_PROFILECONTENT, $this->VALID_PROFILEEMAIL, $this->VALID_PROFILEGITHUBACCESSTOKEN, $this->hash, $this->VALID_PROFILELOCATION, $this->VALID_PROFILENAME, $this->salt);
		$profile->update($this->getPDO());
	}
}