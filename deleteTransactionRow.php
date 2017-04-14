<?php

include_once("utils.php");
include_once("ensureLoggedIn.php"); 

if(isset($_POST["deleteId"])) {
	$dbm = new SqlDataManager();
	$deleteId = $dbm->secureFormInputText($_POST["deleteId"]);

	$sql = "DELETE FROM transactions WHERE id=$deleteId";
	
	if ($res = $dbm->sql($sql)) {
		echo "success";
	} else {
		echo "fail";
	}
} else {
	echo "fail";
}

?>
