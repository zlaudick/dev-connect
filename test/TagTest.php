<?php
namespace Edu\Cnm\DevConnect\Test;
use Edu\Cnm\DevConnect\{Tag};
// grab the project test parameters
require_once("DevConnectTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");
/**
 * Full PHPUnit test for the Tag class
 *
 * This is a complete PHPUnit test of the Tag class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Tag
 * @author Gerald Sandoval <gsandoval16@cnm.edu>
 **/
class TagTest extends DevConnectTest {
	/**
	 * content of the Tag
	 * @var string $VALID_TAGCONTENT
	 **/
	protected $VALID_TAGCONTENT = "Valid Tag Content 1";
	/**
	 * content of the updated Tag
	 * @var string $VALID_TAGCONTENT2
	 **/
	protected $VALID_TAGCONTENT2 = "Valid Tag Content 2";
	/**
	 * setUp function is not needed since there are no
	 *  dependent objects before running each test
	 **/
	/**
	 * test inserting a valid Tag and verify that the actual mySQL data matches
	 **/
	public function testInsertValidTag() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");

		// create a new Tag and insert to into mySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoTag = Tag::getTagByTagId($this->getPDO(), $tag->getTagId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$this->assertEquals($pdoTag->getTagName($this->VALID_TAGCONTENT), $this->VALID_TAGCONTENT);
	}
	/**
	 * test inserting a Tag that already exists
	 *
	 * @expectedException PDOException
	 **/
	public function testInsertInvalidTag() {
		// create a Tag with a non null tag id and watch it fail
		$tag = new Tag(DevConnectTest::INVALID_KEY, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
	}
	/**
	 * test inserting a Tag, editing it, and then updating it
	 **/
	public function testUpdateValidTag() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");
		// create a new Tag and insert to into mySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		// edit the Tag and update it in mySQL
		$tag->setTagName($this->VALID_TAGCONTENT2);
		$tag->update($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$pdoTag = Tag::getTagByTagId($this->getPDO(), $tag->getTagId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$this->assertEquals($pdoTag->getTagName(), $this->VALID_TAGCONTENT2);
	}
	/**
	 * test updating a Tag that does not exists
	 *
	 * @expectedException PDOException
	 **/
	public function testUpdateInvalidTag() {
		// create a Tag with a non null tag id and watch it fail
		$tag = new Tag(null,  $this->VALID_TAGCONTENT);
		$tag->update($this->getPDO());
	}
	/**
	 * test creating a Tag and then deleting it
	 **/
	public function testDeleteValidTag() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");
		// create a new Tag and insert to into mySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		// delete the Tag from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$tag->delete($this->getPDO());
		// grab the data from mySQL and enforce the Tag does not exist
		$pdoTag = Tag::getTagByTagId($this->getPDO(), $tag->getTagId());
		$this->assertNull($pdoTag);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("tag"));
	}
	/**
	 * test deleting a Tag that does not exist
	 *
	 * @expectedException PDOException
	 **/
	public function testDeleteInvalidTag() {
		// create a Tag and try to delete it without actually inserting it
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->delete($this->getPDO());
	}
	/**
	 * test grabbing a Tag by tag content
	 **/
	public function testGetValidTagByTagContent() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");
		// create a new Tag and insert to into mySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Tag::getTagByTagContent($this->getPDO(), $tag->getTagName());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$this->assertCount(1, $results);
				// grab the result from the array and validate it
		$pdoTag = $results[0];
				$this->assertEquals($pdoTag->getTagName(), $this->VALID_TAGCONTENT);
	}

	/**
	 * test grabbing a Tag that does not exist
	 **/
	public function testGetInvalidTagByTagId() {
		// grab a profile id that exceeds the maximum allowable profile id
		$tag = Tag::getTagByTagId($this->getPDO(), DevConnectTest::INVALID_KEY);
		$this->assertNull($tag);
	}
	/**
	 * test grabbing a Tag by content that does not exist  DYLAN DIDN'T DO THIS!!!!
	 **/
	public function testGetInvalidTagByTagContent() {
		// grab a tag by content that does not exist
		$tag = Tag::getTagByTagContent($this->getPDO(), "nobody ever made this TAG");
		$this->assertCount(0, $tag);
	}
	/**
	 * test grabbing all Tags
	 **/
	public function testGetAllValidTags() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");
		// create a new Tag and insert to into mySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		// grab the data from mySQL and enforce the fields match our expectations
		$results = Tag::getAllTags($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$this->assertCount(1, $results);
				// grab the result from the array and validate it
		$pdoTag = $results[0];
				$this->assertEquals($pdoTag->getTagName(), $this->VALID_TAGCONTENT);
	}
	/**
	 * tests the JSON serialization
	 **/
	public function testJsonSerialize() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("tag");
		//create a new Tag and insert into MySQL
		$tag = new Tag(null, $this->VALID_TAGCONTENT);
		$tag->insert($this->getPDO());
		//grab the data from MySQL and enforce that the JSON data matches our expectations
		$pdoTag = Tag::getTagByTagId($this->getPDO(), $tag->getTagId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("tag"));
		$tagId = $tag->getTagId();
		$expectedJson = <<< EOF
{"tagId": $tagId, "tagName": "$this->VALID_TAGCONTENT"}
EOF;
		$this->assertJsonStringEqualsJsonString($expectedJson, json_encode($pdoTag));
	}
}