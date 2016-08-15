<?php
namespace Edu\Cnm\DevConnect\Test;
use Edu\Cnm\DevConnect\{Project, ProjectTag, Profile, Tag};
// grab the project test parameters
require_once("DevConnectTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");
/**
 * Full PHPUnit test for the ProjectTag class
 *
 * This is a complete PHPUnit test of the ProjectTag class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see ProjectTag
 * @author Marcelo Ibarra <mibarra5@cnm.edu>
 *
 * **/
class ProjectTagTest extends DevConnectTest {
	/**
	 * project that uses a project tag
	 * @var Project project
	 **/
	protected $project = null;

	/**
	 * tag of project tag
	 * @var Tag tag
	 **/
	protected $tag = null;

	/**
	 * profile that created the project that has the project tag
	 * @var Profile profile
	 **/

	protected $profile = null;

	/**
	 * create the dependent object before test
	 **/
	public final function setUp() {
		//run the default set up method
		parent::setUp();

		//create a profile to own the project that has the tag
		$this->profile = new Profile(null, "Q", "12345678901234567890123456789012", true, 1, null, "Hi, I'm Markimoo!", "foo@bar.com", "4018725372539424208555279506880426447359803448671421461653568500", "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678", "Los Angeles", "Mark Fischbach", "1234567890123456789012345678901234567890123456789012345678901234");
		$this->profile->insert($this->getPDO());

		//create and insert a project to have the project tag
		$this->project = new Project(null, $this->profile->getProfileId(), "this is the project content", null, "this is the project name");
		$this->project->insert($this->getPDO());

		//create an insert tag to reference the project tag
		$this->tag = new Tag(null, "save the pandas");
		$this->tag->insert($this->getPDO());
	}


	/**
	 * test inserting a valid project tag composite key and verify that the actual mySQL data matches
	 **/
	public function testInsertValidProjectTag() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("projectTag");

		// create a new projectTag and insert into mySQL
		$projectTag = new ProjectTag($this->project->getProjectId(), $this->tag->getTagId());
		$projectTag->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoProjectTag = ProjectTag::getProjectTagByProjectIdAndTagId($this->getPDO(), $projectTag->getProjectTagProjectId(), $projectTag->getProjectTagTagId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("projectTag"));
		$this->assertEquals($pdoProjectTag->getProjectTagProjectId(), $this->project->getProjectId());
		$this->assertEquals($pdoProjectTag->getProjectTagTagId(), $this->tag->getTagId());
	}

	/**
	 * test that tries inserting a project tag that already exists
	 *
	 * @expectedException \TypeError
	 **/
	public function testInsertInvalidProjectTag() {
		//create a project with a non-null tag and watch it fail
		$review = new ProjectTag(null,null);
		$review->insert($this->getPDO());
	}

	/**
	 * test creating a ProjectTag and then deleting it
	 **/
	public function testDeleteValidTag() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("projectTag");

		// create a new projectTag and insert into mySQL
		$projectTag = new ProjectTag($this->project->getProjectId(), $this->tag->getTagId());
		$projectTag->insert($this->getPDO());

		// delete the ProjectTag from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("projectTag"));
		$projectTag->delete($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$pdoProjectTag = ProjectTag::getProjectTagByProjectIdAndTagId($this->getPDO(),
			$projectTag->getProjectTagProjectId(), $projectTag->getProjectTagTagId());

		$this->assertNull($pdoProjectTag);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("projectTag"));
	}

	/**
	 * test deleting a projectTag that does not exist
	 **/
	public function testDeleteInvalidProjectTag() {
		//create a projectTag and try deleting without actually inserting it
		$projectTag = new ProjectTag($this->project->getProjectId(), $this->tag->getTagId());
		$projectTag->delete($this->getPDO());
	}
}
