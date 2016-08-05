<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Dev Connect Conceptual Model</title>
	</head>
	<body>
		<h1>Entities and Attributes</h1>
		<ol>
			<li>
				image
				<ul>
					<li>imageId</li>
					<li>imagePath</li>
					<li>imageType</li>
				</ul>
			</li>
			<li>message(weak - "try hard")
				<ul>
					<li>messageId (primary key)</li>
					<li>messageReceiveProfileId(foreign key)</li>
					<li>messageSentProfileId(foreign key)</li>
					<li>messageContent</li>
					<li>messageDateTime</li>
					<li>messageMailgunId</li>
					<li>messageSubject</li>
				</ul>
			</li>
			<li>profile
			<ul>
				<li>profileId(primary key)</li>
				<li>profileAccountType</li>
				<li>profileActivationToken</li>
				<li>profileApproved</li>
				<li>profileApprovedById</li>
				<li>profileApprovedDateTime</li>
				<li>profileContent</li>
				<li>profileGithubAccessToken</li>
				<li>profileHash</li>
				<li>profileLocation</li>
				<li>profileName</li>
				<li>profileSalt</li>
			</ul>
			</li>
			<li>profileImage
			<ul>
				<li>profileImageProfileId</li>
				<li>profileImageImageId</li>
			</ul>
			</li>
			<li>project
			<ul>
				<li>projectId(primary key)</li>
				<li>projectProfileId(foreign key)</li>
				<li>projectContent</li>
				<li>projectDate</li>
				<li>projectName</li>
			</ul>
			</li>
			<li>projectTag(weak)
				<ul>
					<li>projectTagProjectId(primary composite key)</li>
					<li>projectTagTagId(primary composite key)</li>
				</ul>
			</li>
			<li>review(weak)
				<ul>
					<li>reviewReceiveProfileId(primary composite key)</li>
					<li>reviewWriteProfileId(primary composite key)</li>
					<li>reviewContent</li>
					<li>reviewDateTime</li>
					<li>reviewRating</li>
				</ul>
			</li>
			<li>tag
			<ul>
				<li>tagId(primary key)</li>
				<li>tagName</li>
			</ul>
			</li>
		</ol>
		<h1>Conceptual Model</h1>
		<ul>
			<li>one <strong>profile</strong> can create many <strong>projects</strong></li>
			<li>many <strong>profiles</strong> can message many <strong>profiles</strong></li>
			<li>many <strong>profiles</strong> can review many <strong>profiles</strong></li>
			<li>many <strong>projects</strong> can have many <strong>tags</strong></li>
			<li>many <strong>images</strong> can be attached to many <strong>profiles</strong></li>
		</ul>
		<h1>Dev Connect ERD</h1>
		<img src="../images/dev-connect-erd.svg" alt="Dev Connect ERD" />
	</body>
</html>