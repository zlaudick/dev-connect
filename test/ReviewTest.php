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
 * are tested for both valid and invalid inputs.
 *
 * @see Review
 * @author Gerald Sandoval <gsandoval16@cnm.edu>
 **/
class ReviewTest extends DevConnectTest {
	/**
	 * content of the Profile Activation Token
	 * @var string $VALID_PROFILEACTIVETOKEN
	 **/
	protected $VALID_PROFILEACTIVETOKEN = "12345678901234567890123456789012";
	/**
	 * content of the Profile GITHUB Activation Token and Salt
	 * @var string $VALID_PROFILEACTIVETOKENANDSALT
	 **/
	protected $VALID_PROFILEGITHUBANDSALT= "1234567890123456789012345678901234567890123456789012345678901234";
	/**
	 * content of the Profile Hash
	 * @var string $VALID_PROFILEHASH
	 **/
	protected $VALID_PROFILEHASH= "12345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678";
	/**
	 * content of the Review
	 * @var string $VALID_REVIEWONTENT
	 **/
	protected $VALID_REVIEWCONTENT = "Valid Review Content 1";
	/**
	 * content of the updated Review
	 * @var string $VALID_TAGCONTENT2
	 **/
	protected $VALID_REVIEWCONTENT2 = "Updated Valid Review Content 2";

	/**
	 * timestamp of the Review; this starts as null and is assigned later
	 * @var DateTime $VALID_REVIEWDATE
	 **/
	protected $VALID_REVIEWDATE = null;
	/**
	 * content of the Review Rating
	 * @var int $VALID_REVIEWRATING
	 **/
	protected $VALID_REVIEWRATING = 1;
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
		$this->profileWrite = new Profile(null, "Q",
					$this->VALID_PROFILEACTIVETOKEN, false, 1,
					null, "content", "foo@bar.com",
					$this->VALID_PROFILEGITHUBANDSALT,
					$this->VALID_PROFILEHASH,
					"Abq, NM", "Elvis",
					$this->VALID_PROFILEGITHUBANDSALT);

		$this->profileReceive = new Profile(null, "Q",
					$this->VALID_PROFILEACTIVETOKEN, false, 1,
					null, "content", "foofoo@bar.com",
					$this->VALID_PROFILEGITHUBANDSALT,
					$this->VALID_PROFILEHASH,
					"Abq, NM", "Priscilla",
					$this->VALID_PROFILEGITHUBANDSALT);

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
		$review = new Review($this->profileReceive->getProfileId(),
									$this->profileWrite->getProfileId(),
									$this->VALID_REVIEWCONTENT,
									$this->VALID_REVIEWDATE,
									$this->VALID_REVIEWRATING);

		$review->insert($this->getPDO());

		// query the data from mySQL and enforce the fields match our expectations
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
																	$review->getReviewReceiveProfileId(),
																	$review->getReviewWriteProfileId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);
	}
	/**
	 * test inserting a Review that already exists
	 *
	 * @expectedException PDOException
	 **/

	public function testInsertInvalidReview() {
		// create a Review with a non null composite id and watch it fail

			$reviewInvalidReceive = new Review(DevConnectTest::INVALID_KEY,
												$this->profileWrite->getProfileId(),
												$this->VALID_REVIEWCONTENT,
												$this->VALID_REVIEWDATE,
												$this->VALID_REVIEWRATING);

			$reviewInvalidReceive->insert($this->getPDO());

			$reviewInvalidWrite = new Review($this->profileReceive->getProfileId(),
												DevConnectTest::INVALID_KEY,
												$this->VALID_REVIEWCONTENT,
												$this->VALID_REVIEWDATE,
												$this->VALID_REVIEWRATING);

		$reviewInvalidWrite->insert($this->getPDO());
	}
	/**
	 * test inserting a Review, editing it, and then updating it
	 **/
	public function testUpdateValidReview() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
										$this->profileWrite->getProfileId(),
										$this->VALID_REVIEWCONTENT,
										$this->VALID_REVIEWDATE,
										$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());

		// query the data from mySQL and verify the Content data before calling update method
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
																	$review->getReviewReceiveProfileId(),
																	$review->getReviewWriteProfileId());

		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);

		// modify the Review and update the content in mySQL
		$review->setReviewContent($this->VALID_REVIEWCONTENT2);
		$review->update($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
																	$review->getReviewReceiveProfileId(),
																	$review->getReviewWriteProfileId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT2);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);
		//
		}
	/**
	 * test updating a Review that does not exists
	 *
	 * @expectedException \TypeError
	 **/
	public function testUpdateInvalidReview() {
/*
				$review = new Review(DevConnectTest::INVALID_KEY,
											DevConnectTest::INVALID_KEY,
*/
		$review = new Review(null, null,
									$this->VALID_REVIEWCONTENT,
									$this->VALID_REVIEWDATE,
									$this->VALID_REVIEWRATING);

		$review->update($this->getPDO());
	}
	/**
	 * test creating a Review and then deleting it
	 **/
	public function testDeleteValidReview() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
			$this->profileWrite->getProfileId(),
			$this->VALID_REVIEWCONTENT,
			$this->VALID_REVIEWDATE,
			$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());

		// delete the Review from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$review->delete($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
																	$review->getReviewReceiveProfileId(),
																	$review->getReviewWriteProfileId());
		$this->assertNull($pdoReview);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("review"));
	}
	/**
	 * test deleting a Review that does not exist
	 *
	 * @expectedException \TypeError
	 **/
	public function testDeleteInvalidReview() {
		// create a Review and try to delete it without actually inserting it

		$review = new Review(null, null,
									$this->VALID_REVIEWCONTENT,
									$this->VALID_REVIEWDATE,
									$this->VALID_REVIEWRATING);
		$review->delete($this->getPDO());
	}
	/**
	 * test grabbing a Review by review content
	 **/
	public function testGetValidReviewByReviewContent() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
										$this->profileWrite->getProfileId(),
										$this->VALID_REVIEWCONTENT,
										$this->VALID_REVIEWDATE,
										$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$results = Review::getReviewByReviewContent($this->getPDO(),
																	$this->VALID_REVIEWCONTENT);


		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DevConnect\\Review", $results);

		// grab the result from the array and validate it
		$pdoReview = $results[0];
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);
	}
	/**
	 * query a Review by content that does not exist
	 **/
	public function testGetInvalidReviewByReviewContent() {
		// query a review by searching for content that does not exist
		$review = Review::getReviewByReviewContent($this->getPDO(), "you will find nothing");
		$this->assertCount(0, $review);
	}
	/**
	 * test:  query all Reviews
	 **/
	public function testGetAllValidReviews() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
										$this->profileWrite->getProfileId(),
										$this->VALID_REVIEWCONTENT,
										$this->VALID_REVIEWDATE,
										$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$results = Review::getAllReviews($this->getPDO());

$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
$this->assertCount(1, $results);

		$this->assertContainsOnlyInstancesOf("Edu\\Cnm\\DevConnect\\Review", $results);

		// grab the result from the array and validate it
		$pdoReview = $results[0];
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);
	}
	/**
	 * test:  query all Reviews by Receive Id's
	 **/
	public function testGetAllReviewsByReceiveProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");


		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
			$this->profileWrite->getProfileId(),
			$this->VALID_REVIEWCONTENT,
			$this->VALID_REVIEWDATE,
			$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());
/*
		// query the data from mySQL and verify the Content data before calling update method
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
			$review->getReviewReceiveProfileId(),
			$review->getReviewWriteProfileId());   !!!!!!!!!!!!!!!!!!
		*/

		// query the data from mySQL and verify the fields match our expectations
		$pdoReview = Review::getReviewByReviewReceiveProfileId($this->getPDO(), $review->getReviewReceiveProfileId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);

	}
	/**
	 * test:  query all Reviews by Write Id's
	 **/
	public function testGetAllReviewsByWriteProfileId() {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
			$this->profileWrite->getProfileId(),
			$this->VALID_REVIEWCONTENT,
			$this->VALID_REVIEWDATE,
			$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());
		/*
				// query the data from mySQL and verify the Content data before calling update method
				$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
					$review->getReviewReceiveProfileId(),
					$review->getReviewWriteProfileId());   !!!!!!!!!!!!!!!!!!
				*/

		// query the data from mySQL and verify the fields match our expectations
		$pdoReview = Review::getReviewByReviewWriteProfileId($this->getPDO(), $review->getReviewWriteProfileId());

		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));
		$this->assertEquals($pdoReview->getReviewReceiveProfileId(), $review->getReviewReceiveProfileId());
		$this->assertEquals($pdoReview->getReviewWriteProfileId(), $review->getReviewWriteProfileId());
		$this->assertEquals($pdoReview->getReviewContent(), $this->VALID_REVIEWCONTENT);
		$this->assertEquals($pdoReview->getReviewDateTime(), $this->VALID_REVIEWDATE);
		$this->assertEquals($pdoReview->getReviewRating(), $this->VALID_REVIEWRATING);

	}
	/**
	 * tests the JSON serialization
	 **/
	public function testJsonSerialize() {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("review");

		// create a new Review object and insert to into mySQL
		$review = new Review($this->profileReceive->getProfileId(),
			$this->profileWrite->getProfileId(),
			$this->VALID_REVIEWCONTENT,
			$this->VALID_REVIEWDATE,
			$this->VALID_REVIEWRATING);
		$review->insert($this->getPDO());

		// query the data from mySQL and verify the fields match our expectations
		$pdoReview = Review::getReviewByReceiveProfileIdAndWriteProfileId($this->getPDO(),
																	$review->getReviewReceiveProfileId(),
																	$review->getReviewWriteProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("review"));

		$receiveId = $review->getReviewReceiveProfileId();
		$writeId = $review->getReviewWriteProfileId();
		$reviewDate = 1000 * $this->VALID_REVIEWDATE->getTimestamp();


		$expectedJson = <<< EOF
										{"reviewReceiveProfileId": $receiveId, 
										 "reviewWriteProfileId": $writeId,  
										 "reviewContent": "$this->VALID_REVIEWCONTENT",
										 "reviewDateTime": $reviewDate, 
										 "reviewRating": "$this->VALID_REVIEWRATING"} 
EOF;

		$this->assertJsonStringEqualsJsonString($expectedJson, json_encode($pdoReview));

	}

}