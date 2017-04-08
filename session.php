<?php
	// Verify session. If no session, redirect to login page.
	session_start();

	if(!isset($_SESSION["loginUser"])) {
		header("Location: index.php");
	}

?>