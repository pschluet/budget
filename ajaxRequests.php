<?php

include_once("utils.php");
include_once("ensureLoggedIn.php"); 

// Handle deleting a row from the transactions table
if(isset($_POST["deleteId"])) {
	$dbm = new SqlDataManager();
	$deleteId = $dbm->secureFormInputText($_POST["deleteId"]);

	$sql = "DELETE FROM transactions WHERE id=$deleteId";
	
	if ($res = $dbm->sql($sql)) {
		echo "success";
	} else {
		echo "fail";
	}
}

// Get data for the budget category plot
if (isset($_POST["chartType"]) && $_POST["chartType"] == "categoryBar") {
	$dbm = new SqlDataManager();
	$month = $_POST["month"];
	$year = $_POST["year"];
	$sql = "SELECT
                c.names as category,
                SUM(t.amount) as amount
            FROM transactions AS t
            INNER JOIN 
                categories AS c ON t.categoryId=c.id
            WHERE MONTH(t.date) in ($month) AND YEAR(t.date) in ($year)
            GROUP BY category
            ORDER BY category";
	$res = $dbm->sqlQuery($sql);

	echo json_encode($res);
}

?>
