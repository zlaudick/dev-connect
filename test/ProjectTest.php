<?php
namespace Edu\Cnm\DevConnect\Test;
use Edu\Cnm\DevConnect\{Project, Profile};
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
	 * timestamp of the Project; this starts as null and is assigned later
	 * @var DateTime $VALID_PROJECTDATE
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
		$this->profile = new Profile(null, "Q", "12345678901234567890123456789012", false, 1, null, "content", "foo@bar.com", "1234567890123456789012345678901234567890123456789012345678901234", "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678", "Abq, NM", "Elvis", "1234567890123456789012345678901234567890123456789012345678901234");
		$this->profile->insert($this->getPDO());
// calculate the date (just use the time the unit test was setup...)
		$this->VALID_PROJECTDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Project and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProject() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("Project");

		// create a new Project and insert to into mySQL
		$Project = new Project(null, $this->VALID_PROJECTCONTENT);
		$Project->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProject = Project::getProjectByProjectId($this->getPDO(), $Project->getProjectId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("Project"));
		$this->assertEquals($pdoProject->getProjectName($this->VALID_PROJECTCONTENT), $this->VALID_PROJECTCONTENT);
	}
}
