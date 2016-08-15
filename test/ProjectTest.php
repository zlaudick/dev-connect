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
	 *
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
	 * @var \DateTime $VALID_PROJECTDATE
	 **/
	protected $VALID_PROJECTDATE = null;
	/**
	 * Name of the project
	 * @var string $VALID_PROJECTNAME
	 **/
	protected $VALID_PROJECTNAME = "Something Awesome";
	/**
	 * updated project name
	 * @var string $VALID_PROJECTNAME2
	 */
	protected $VALID_PROJECTNAME2 = "Something more awesome";
	/**
	 * profile that created the project
	 * @var Profile profile
	 */
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
		$numRows = $this->getConnection()->getRowCount("project");

		// create a new Project and insert to into mySQL
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE, $this->VALID_PROJECTNAME);
		$project->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProject = Project::getProjectByProjectId($this->getPDO(), $project->getProjectId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("project"));
		$this->assertEquals($pdoProject->getProjectProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProject->getProjectContent(), $this->VALID_PROJECTCONTENT);
		$this->assertEquals($pdoProject->getProjectDate(), $this->VALID_PROJECTDATE);
		$this->assertEquals($pdoProject->getProjectName(), $this->VALID_PROJECTNAME);
	}

	/**
	 * test inserting a Project that already exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testInsertInvalidProject() {
		// create a Project with a non null Project id and watch it fail
		$project = new Project(DevConnectTest::INVALID_KEY, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE, $this->VALID_PROJECTNAME);
		$project->insert($this->getPDO());
	}

	/**
	 * test inserting a Project, editing it, and then updating it
	 **/
	public function testUpdateValidProject() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("project");
		// create a new Project and insert to into mySQL
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE,$this->VALID_PROJECTNAME);
		$project->insert($this->getPDO());
		// edit the Project and update it in mySQL
		$project->setProjectContent($this->VALID_PROJECTCONTENT2);
		$project->setProjectName($this->VALID_PROJECTNAME2);
		$project->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProject = Project::getProjectByProjectId($this->getPDO(), $project->getProjectId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("project"));
		$this->assertEquals($pdoProject->getProjectProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProject->getProjectContent(), $this->VALID_PROJECTCONTENT2);
		$this->assertEquals($pdoProject->getProjectDate(), $this->VALID_PROJECTDATE);
		$this->assertEquals($pdoProject->getProjectName(), $this->VALID_PROJECTNAME2);
	}

	/**
	 * test updating a Project that does not exists
	 *
	 * @expectedException \PDOException
	 **/
	public function testUpdateInvalidProject() {
		// create a Project with a non null Project id and watch it fail
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE, $this->VALID_PROJECTNAME);
		$project->update($this->getPDO());
	}

	/**
	 * test creating a project and then deleting it
	 **/
	public function testDeleteValidProject() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("project");

		// create a new project and insert to into mySQL
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE,$this->VALID_PROJECTNAME);
		$project->insert($this->getPDO());

		// delete the project from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("project"));
		$project->delete($this->getPDO());

		// grab the data from mySQL and enforce the project does not exist
		$pdoProject = Project::getProjectByProjectId($this->getPDO(), $project->getProjectId());
		$this->assertNull($pdoProject);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("project"));
	}


	/**
	 * test deleting a project that does not exist
	 *
	 * @expectedException \PDOException
	 **/
	public function testDeleteInvalidProject() {
		// create a project and try to delete it without actually inserting it
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE, $this->VALID_PROJECTNAME);
		$project->delete($this->getPDO());
	}

	/**
	 * test grabbing a Project that does not exist
	 **/
	public function testGetInvalidProjectByProjectId() {
		// grab a profile id that exceeds the maximum allowable profile id
		$project = Project::getProjectByProjectId($this->getPDO(), DevConnectTest::INVALID_KEY);
		$this->assertNull($project);
	}

	/**
	 * test grabbing all Projects by project profile id
	 **/
	public function testGetValidProjectByProjectProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("project");

		// create a new Project and insert it in mySQL
		$project = new Project(null, $this->profile->getProfileId(), $this->VALID_PROJECTCONTENT, $this->VALID_PROJECTDATE,$this->VALID_PROJECTNAME);
		$project->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$results = Project::getProjectByProjectProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("project"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DevConnect\\Project", $results);

		// grab the result from the array and validate it
		$pdoProject = $results [0];
		$this->assertEquals($pdoProject->getProjectProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoProject->getProjectContent(), $this->VALID_PROJECTCONTENT);
		$this->assertEquals($pdoProject->getProjectDate(), $this->VALID_PROJECTDATE);
		$this->assertEquals($pdoProject->getProjectName(), $this->VALID_PROJECTNAME);
	}

	/**
	 * test grabbing a Project profile id that does not exist
	 **/
	public function testGetInvalidProjectByProjectProfileId() {
		// grab a project profile id that exceeds the maximum allowable project profile  id
		$project = Project::getProjectByProjectProfileId($this->getPDO(), DevConnectTest::INVALID_KEY);
		$this->assertCount(0, $project);
	}
}

