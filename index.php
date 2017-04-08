<?php
// TODO:
//	- Add logout button
//  - Update page that shows successful submission
//  - Reset form when link clicked to go back to entry
	include_once("utils.php");	

	if (isset($_POST['username']) && isset($_POST['password'])) {
		// Username and password sent from form

		$dm = new SqlDataManager();

		// Secure form inputs
		$formUsername = $dm->secureFormInputText($_POST["username"]);
		$formPassword = $dm->secureFormInputText($_POST["password"]);
		$formPassword = md5($formPassword);

		// Check credentials with database
		$sql = "SELECT username FROM logins WHERE username = '$formUsername' AND password = '$formPassword'";
		$credentialMatch = $dm->sqlQuery($sql);
		
		if (empty($credentialMatch)) {
			$error = "Invalid Username or Password";
		} else {
			$domain = ($_SERVER['HTTP_HOST'] != "localhost") ? $_SERVER['HTTP_HOST'] : false;
			if (isset($_POST["rememberMe"])) {
				// Set cookie to last a long time
				$COOKIE_EXP_TIME_DAYS = 365;
				setcookie("username", $_POST["username"], time() + $COOKIE_EXP_TIME_DAYS * 60 * 60 * 24, '/', $domain);
				setcookie("password", md5($_POST["password"]), time() + $COOKIE_EXP_TIME_DAYS * 60 * 60 * 24, '/', $domain);
			} else {
				setcookie('username', $_POST['username'], false, '/', $domain);
            	setcookie('password', md5($_POST['password']), false, '/', $domain);
			}
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
	        <label for="reme_entry">Remember Me</label>
            <input type="checkbox" name="rememberMe" id="reme_entry">
	        <input type = "submit" value = "Submit"/>
		</form>
		<div><?php echo $error;?></div>
	</div>
</div>
</body>

</html>