<?php
	// Verify cookie set with valid username and password.
	include_once("utils.php");

	if (isset($_COOKIE['username']) && isset($_COOKIE['password'])) {

		// Secure cookie inputs
		$dm = new SqlDataManager();
		$formUsername = $dm->secureFormInputText($_COOKIE["username"]);
		$formPassword = $dm->secureFormInputText($_COOKIE["password"]);

		// Validate login credentials
		$sql = "SELECT username FROM logins WHERE username = '$formUsername' AND password = '$formPassword'";
		$credentialMatch = $dm->sqlQuery($sql);

		if (empty($credentialMatch)) {
			header("Location: login.php");
		}
	} else {
		header("Location: login.php");
	}

?>