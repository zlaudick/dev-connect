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
}