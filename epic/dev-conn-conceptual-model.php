<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Dev Connect Conceptual Model</title>
	</head>
	<body>
		<h1>Entities and Attributes</h1>
		<ol>
			<li>profile
			<ul>
				<li>profileId(primary key)</li>
				<li>profileName</li>
				<li>profileHash</li>
				<li>profileSalt</li>
				<li>profilePicturePath</li>
				<li>profileContent</li>
				<li>profileLocation</li>
				<li>profileAccountType</li>
			</ul>
			</li>
			<li>project
			<ul>
				<li>projectId(primary key)</li>
				<li>projectProfileId(foreign key)</li>
				<li>projectPicturePath(foreign key)</li>
				<li>projectName</li>
				<li>projectContent</li>
				<li>projectDate</li>
			</ul>
			</li>
			<li>tag
			<ul>
				<li>tagId(primary key)</li>
				<li>tagName</li>
			</ul>
			</li>
			<li>message(weak)
			<ul>
				<li>messageSentProfileId(primary composite key)</li>
				<li>messageReceiveProfileId(primary composite key)</li>
				<li>messageContent</li>
			</ul>
			</li>
			<li>review(weak)
			<ul>
				<li>reviewWriteProfileId(primary composite key)</li>
				<li>reviewReceiveProfileId(primary composite key</li>
				<li>reviewContent</li>
				<li>reviewRating</li>
			</ul>
			</li>
			<li>projectTag(weak)
				<ul>
					<li>projectTagProjectId(primary composite key)</li>
					<li>projectTagTagId(primary composite key)</li>
				</ul>
			</li>
		</ol>
		<h1>Conceptual Model</h1>
		<ul>
			<li>one <strong>profile</strong> can create many <strong>projects</strong></li>
			<li>many <strong>profiles</strong> can view many <strong>projects</strong></li>
			<li>many <strong>profiles</strong> can message many <strong>profiles</strong></li>
			<li>many <strong>profiles</strong> can review many <strong>profiles</strong></li>
			<li>many <strong>projects</strong> can have many <strong>tags</strong></li>
		</ul>
		<h1>Dev Connect ERD</h1>
		<img src="../images/dev-connect-erd.svg" alt="Dev Connect ERD" />
	</body>
</html>