<?php
namespace Edu\Cnm\DevConnect\Test;
use Edu\Cnm\DevConnect\{Review, Profile};
// grab the project test parameters
require_once("DevConnectTest.php");
// grab the class under scrutiny
require_once(dirname(__DIR__) . "/public_html/php/classes/autoload.php");
/**
 * Full PHPUnit test for the Review class
 *
 * This is a complete PHPUnit test of the Review class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Review
 * @author Gerald Sandoval <gsandoval16@cnm.edu>
 **/
class ReviewTest extends DevConnectTest {
	/**
	 * content of the Review
	 * @var string $VALID_REVIEWONTENT
	 **/
	protected $VALID_REVIEWONTENT = "Valid Review Content 1";
	/**
	 * content of the updated Review
	 * @var string $VALID_TAGCONTENT2
	 **/
	protected $VALID_TAGCONTENT2 = "Valid Review Content 2";

	/**
	 * timestamp of the Review; this starts as null and is assigned later
	 * @var DateTime $VALID_REVIEWDATE
	 **/
	protected $VALID_REVIEWDATE = null;
	/**
	 * Profile that created the Review; this is for foreign key relations
	 * @var Profile profile
	 **/
	protected $profile = null;
	/**
	 * approved value of the Profile
	 * @var bool $VALID_PROFILEAPPROVED
	 **/
	protected $VALID_PROFILEAPPROVED = 1;

	/**
	 * setUp function
	 * create dependent objects before running each test
	 **/
	public final function setUp() {
		// run the default setUp() method first
		parent::setUp();

		// create and insert a Profile to own the test Tweet
		$this->profileWrite = new Profile(null, "Q", "12345678901234567890123456789012", false, 1, null, "content", "foo@bar.com", "1234567890123456789012345678901234567890123456789012345678901234", "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678", "Abq, NM", "Elvis", "1234567890123456789012345678901234567890123456789012345678901234");
		$this->profileReceive = new Profile(null, "Q", "12345678901234567890123456789012", false, 1, null, "content", "foofoo@bar.com", "1234567890123456789012345678901234567890123456789012345678901234", "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678", "Abq, NM", "Priscilla", "1234567890123456789012345678901234567890123456789012345678901234");

		$this->profileWrite->insert($this->getPDO());
		$this->profileReceive->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_REVIEWDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Review and verify that the actual mySQL data matches
	 **/
	public function testInsertValidReview() {
		// count the number of rows and save it for later

		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileWrite->getProfileId(),
									$this->profileReceive->getProfileId(), "sucks", null, 1);
		$review->insert($this->getPDO());

		// grab the data from mySQL and enforce the fields match our expectations
		$pdoReview = Review::getReviewByReviewReceiveProfileId($this->getPDO(), $review->getReviewReceiveProfileId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));

		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $this->profileReceive->getProfileId());
	} /*
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $this->profileWrite->getProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), "sucks");
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), 1);


	}
*/

}