<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8"/>
		<title>Dev Connect Conceptual Model</title>
	</head>
	<body>
		<p>sanity has been compromised</p>
		<h1>Entities and Attributes</h1>
		<ol>
			<li>profile</li>
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
			<li>project</li>
			<ul>
				<li>projectId(primary key)</li>
				<li>projectProfileId(foriegn key)</li>
				<li>projectPicturePath(foriegn key)</li>
				<li>projectName</li>
				<li>projectContent</li>
				<li>projectDate</li>
			</ul>
			<li>tag</li>
			<ul>
				<li>tagId(primary key)</li>
				<li>tagName</li>
			</ul>
			<li>message(weak)</li>
			<ul>
				<li>messageSentProfileId(primary composite key)</li>
				<li>messageRecieveProfileId(primary composite key)</li>
				<li>messageContent</li>
			</ul>
			<li>review(weak)</li>
			<ul>
				<li>reviewWriteProfileId(primary composite key)</li>
				<li>reviewReceiveProfileId(primary composite key</li>
				<li>reviewContent</li>
				<li>reviewRating</li>
			</ul>
			<li>projectTag(weak)</li>
				<ul>
					<li>projectTagProjectId(primary composite key)</li>
					<li>projectTagTagId(primary composite key)</li>
				</ul>
		</ol>
		<h1>Conceptual Model</h1>
		<ul>
			<li>one <strong>profile</strong> can create many <strong>projects</strong></li>
			<li>many <strong>profiles</strong> can view many <strong>projects</strong></li>
			<li>many <strong>profiles</strong> can message many <strong>profiles</strong></li>
			<li>many <strong>profiles</strong> can review many <strong>profiles</strong></li>
			<li>many <strong>projects</strong> can have many <strong>tags</strong></li>
		</ul>
	</body>
</html>