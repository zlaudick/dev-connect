-- drops tables if they exist in the database
DROP TABLE IF EXISTS projectTag;
DROP TABLE IF EXISTS profileImage;
DROP TABLE IF EXISTS review;
DROP TABLE IF EXISTS message;
DROP TABLE IF EXISTS project;
DROP TABLE IF EXISTS tag;
DROP TABLE IF EXISTS image;
DROP TABLE IF EXISTS profile;

-- create profile entity
CREATE TABLE profile (
	profileId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	profileAccountType CHAR(1) NOT NULL,
	profileActivationToken CHAR(32),
	profileApproved BOOLEAN,
	profileApprovedById INT UNSIGNED,
	profileApprovedDateTime DATETIME,
	profileContent VARCHAR(2000),
	profileEmail VARCHAR(128) NOT NULL,
	profileGithubAccessToken VARCHAR(64),
	profileHash CHAR(128),
	profileLocation VARCHAR(64),
	profileName VARCHAR(32) NOT NULL,
	profileSalt CHAR(64),
	UNIQUE (profileEmail),
	PRIMARY KEY (profileId)
);

-- create image entity
CREATE TABLE image (
	imageId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	imagePath VARCHAR(255) NOT NULL,
	imageType VARCHAR(32) NOT NULL,
	PRIMARY KEY (imageId)
);

-- create tag entity
CREATE TABLE tag (
	tagId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	tagName VARCHAR(64) NOT NULL,
	PRIMARY KEY (tagId)
);

-- create project entity
CREATE TABLE project (
	projectId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	projectContent VARCHAR(2000) NOT NULL,
	projectDate DATETIME NOT NULL,
	projectName VARCHAR(64) NOT NULL,
	projectProfileId INT UNSIGNED NOT NULL,
	FOREIGN KEY(projectProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(projectId)
);

-- create message entity (weak try hard entity)
CREATE TABLE message (
	messageId INT UNSIGNED AUTO_INCREMENT NOT NULL,
	messageReceiveProfileId INT UNSIGNED NOT NULL,
	messageSentProfileId INT UNSIGNED NOT NULL,
	messageContent VARCHAR(2000) NOT NULL,
	messageDateTime DATETIME NOT NULL,
	messageMailgunId VARCHAR(128),
	messageSubject VARCHAR(140) NOT NULL,
	FOREIGN KEY (messageReceiveProfileId) REFERENCES profile (profileId),
	FOREIGN KEY (messageSentProfileId) REFERENCES profile (profileId),
	PRIMARY KEY (messageId)
);

-- create review entity (weak entity)
CREATE TABLE review (
	reviewReceiveProfileId INT UNSIGNED NOT NULL,
	reviewWriteProfileId INT UNSIGNED NOT NULL,
	reviewContent VARCHAR(2000) NOT NULL,
	reviewDateTime DATETIME NOT NULL,
	reviewRating INT UNSIGNED NOT NULL,
	FOREIGN KEY(reviewReceiveProfileId) REFERENCES profile(profileId),
	FOREIGN KEY(reviewWriteProfileId) REFERENCES profile(profileId),
	PRIMARY KEY(reviewReceiveProfileId, reviewWriteProfileId)
);

-- create profileImage entity (weak entity)
CREATE TABLE profileImage (
	profileImageImageId INT UNSIGNED NOT NULL,
	profileImageProfileId INT UNSIGNED NOT NULL,
	FOREIGN KEY (profileImageImageId) REFERENCES image(imageId),
	FOREIGN KEY (profileImageProfileId) REFERENCES profile(profileId),
	PRIMARY KEY (profileImageImageId, profileImageProfileId)
);

-- create projectTag entity (weak entity)
CREATE TABLE projectTag (
	projectTagProjectId INT UNSIGNED NOT NULL,
	projectTagTagId INT UNSIGNED NOT NULL,
	FOREIGN KEY(projectTagProjectId) REFERENCES project(projectId),
	FOREIGN KEY(projectTagTagId) REFERENCES tag (tagId),
	PRIMARY KEY(projectTagProjectId, projectTagTagId)
);