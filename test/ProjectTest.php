<?php
namespace Edu\Cnm\DevConnect\Project;
use Edu\Cnm\DevConnect\{Project};
// grab the project test parameters
require_once("DevConnectTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");
/**
* Full PHPUnit test for the Project class
*
* This is a complete PHPUnit test of the Project class. It is complete because *ALL* mySQL/PDO enabled methods
* are tested for both invalid and valid inputs.
*
* @see Project
* @author Marcelo Ibarra <mibarra5@cnm.edu>
**/
class ProjectTest extends DevConnectTest {
	/**
	 * content of the Project
	 * @var string $VALID_PROJECTCONTENT
	 **/
	protected $VALID_PROJECTCONTENT = "Valid Project Content 1";
	/**
	 * content of the updated Project
	 * @var string $VALID_PROJECTCONTENT2
	 **/
	protected $VALID_PROJECTCONTENT2 = "Valid Project Content 2";
	/**
	 * timestamp of the Tweet; this starts as null and is assigned later
	 * @var DateTime $VALID_TWEETDATE
	 **/
	protected $VALID_PROJECTDATE = null;
	/**
	 * Profile that created the Tweet; this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;

	/**
	 * setUp function
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
// run the default setUp() method first
		parent::setUp();

// create and insert a Profile to own the test Project
		$this->profile = new Profile(null, "my profile", "profile account one", "My new Token", "Profile valid", "My profile id", "profile date", "in my profile", "juana@her.com", "my Git-Hub token", "this profile hash", "profile here", "me profile", "the salt & pepper");
		$this->profile->insert($this->getPDO());
// calculate the date (just use the time the unit test was setup...)
		$this->VALID_PROJECTDATE = new \DateTime();
	}

}