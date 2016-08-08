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
	 * @param int $newProfileImageProfileId foreign key part of composite primary key
	 * @param int $newProfileImageImageId foreign key part of composite primary key
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

	/**
	 * accessor method for profileImageProfileId
	 * @return int|null value of profileImageProfileId
	 **/
	public function getProfileImageProfileId() {
		return($this->profileImageProfileId);
	}

	/**
	 * mutator method for profileImageProfileId
	 *
	 * @param int|null $newProfileImageProfileId new value of profileImageProfileId
	 * @throws \RangeException if $newProfileImageProfileId is not positive
	 * @throws \TypeError if $newProfileImageProfileId is not an integer
	 **/
	public function setProfileImageProfileId(int $newProfileImageProfileId) {
		// verify the profileImageProfileId is positive
		if($newProfileImageProfileId <= 0) {
			throw(new \RangeException("profileImageProfileId is not positive"));
		}

		// convert and store the profileImageProfileId
		$this->profileImageProfileId = $newProfileImageProfileId;
	}

	/**
	 * accessor method for profileImageImageId
	 * @return int|null value of profileImageImageId
	 **/
	public function getProfileImageImageId() {
		return($this->profileImageImageId);
	}

	/**
	 * mutator method for profileImageImageId
	 *
	 * @param int|null $newProfileImageImageId new value of profileImageImageId
	 * @throws \RangeException if $newProfileImageImageId is not positive
	 * @throws \TypeError if $newProfileImageImageId is not an integer
	 **/
	public function setProfileImageImageId(int $newProfileImageImageId) {
		// verify the profileImageImageId is positive
		if($newProfileImageImageId <= 0) {
			throw(new \RangeException("profileImageImageId is not positive"));
		}

		// convert and store the profileImageImageId
		$this->profileImageImageId = $newProfileImageImageId;
	}
}