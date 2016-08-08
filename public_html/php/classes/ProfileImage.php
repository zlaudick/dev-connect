<?php
namespace Edu\Cnm\DevConnect;

require_once("autoload.php");

/**
 * ProfileImage class for DevConnect
 *
 * @author Zac Laudick <zlaudick@cnm.edu>
 **/
class ProfileImage {
	/**
	 * foreign key, this is part of the composite key
	 * @var int $profileImageProfileId
	 **/
	private $profileImageProfileId;

	/**
	 * foreign key, this is part of the composite key
	 * @var int $profileImageImageId
	 **/
	private $profileImageImageId;

	/**
	 * constructor for this profile image
	 *
	 * @param int $profileImageProfileId foreign key part of composite primary key
	 * @param int $profileImageImageId foreign key part of composite primary key
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 **/
	public function __construct(int $newProfileImageProfileId, int $newProfileImageImageId) {
		try {
			$this->setProfileImageProfileId($newProfileImageProfileId);
			$this->setProfileImageImageId($newProfileImageImageId);
		} catch(\InvalidArgumentException $invalidArgument) {
			// rethrow the exception to the caller
			throw(new \InvalidArgumentException($invalidArgument->getMessage(), 0, $invalidArgument));
		} catch(\RangeException $range) {
			// rethrows the exception to the caller
			throw(new \RangeException($range->getMessage(), 0, $range));
		} catch(\TypeError $typeError) {
			// rethrow the exception to the caller
			throw(new \TypeError($typeError->getMessage(), 0, $typeError));
		} catch(\Exception $exception) {
			// rethrow the exception to the caller
			throw(new \Exception($exception->getMessage(), 0, $exception));
		}
	}
}