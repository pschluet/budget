<?php
	include_once("utils.php");
	session_start();

	$dm = new SqlDataManager();

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		// Username and password sent from form

		// Secure form inputs
		$formUsername = $dm->secureFormInputText($_POST["username"]);
		$formPassword = $dm->secureFormInputText($_POST["password"]);

		// Check credentials with database
		$sql = "SELECT username FROM logins WHERE username = '$formUsername' AND password = '$formPassword'";
		$credentialMatch = $dm->sqlQuery($sql);
		
		if (empty($credentialMatch)) {
			$error = "Invalid Username or Password";
		} else {
			$_SESSION["loginUser"] = $formUsername;
			header("Location: entry.php");
		}
	}
?>
<html>
<head>	
	<link rel="stylesheet" type="text/css" href="style.css">    

    <!-- Include meta tag to ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Include jQuery Mobile stylesheets -->
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>

    <!-- Include the jQuery Mobile library -->
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

    <title>Login Page</title>
</head>
<body>
<div data-role="page">
    <div data-role="main" class="ui-content">
		<form action="" method="post">
			<label>User Name</label><input type = "text" name = "username"/>
	        <label>Password</label><input type = "password" name = "password"/>
	        <input type = "submit" value = "Submit"/>
		</form>
		<div><?php echo $error;?></div>
	</div>
</div>
</body>

</html>