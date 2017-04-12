<!-- Handle Form Submission -->
<?php include_once("ensureLoggedIn.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Submission</title>
</head>
<body>
<?php

date_default_timezone_set("America/Chicago");
include_once("utils.php");
include_once("ensureLoggedIn.php"); 

$dbm = new SqlDataManager();
$newTransactionId = (int)$dbm->sqlQuery("SELECT MAX(transactionId) FROM transactions")[0]["MAX(transactionId)"] + 1;

// Insert store name into DB if it's not already there
$storeEntered = $dbm->secureFormInputText($_REQUEST["storeNameTextbox"]);
if (!empty($storeEntered) and !($dbm->doesEntryExist("stores", "names", $storeEntered))) {
	$dbm->insertIntoTable("stores", array("names" => $storeEntered));
	$query = $dbm->sqlQuery("SELECT id FROM stores WHERE names='$storeEntered'");
	$storeId = $query[0]["id"];
} else {
	$storeId = $_REQUEST["storeNameDropdown"];
}

// Process the form data
$fp = new TransactionFormProcessor($_REQUEST, $newTransactionId, $storeId);
$transactionsToInsert = $fp->getDataToInsertToDb();

// Insert the transaction data into the database
foreach ($transactionsToInsert as $singleTransaction) {
    if ($dbm->insertIntoTable("transactions", $singleTransaction)) {
        echo "<h1>Success!</h1>";
    } else {
        echo "<h1>Fail!</h1>";
    }    
}


?>
<div><a href="index.php">Enter Another Transaction</a></div>
</body>
</html>