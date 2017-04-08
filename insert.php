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

// Process the form data
$fp = new TransactionFormProcessor($_REQUEST, $newTransactionId);
$transactionsToInsert = $fp->getDataToInsertToDb();

// Insert the data into the database
foreach ($transactionsToInsert as $singleTransaction) {
    if ($dbm->insertIntoTable("transactions", $singleTransaction)) {
        echo "<h1>Success!</h1>";
    } else {
        echo "<h1>Fail!</h1>";
    }    
}

// Insert store name into DB if it's not already there
if (!($dbm->doesEntryExist("stores", "names", $dbm->secureFormInputText($transactionsToInsert[0]["storeName"])))) {
	$dbm->insertIntoTable("stores", array("names" => $transactionsToInsert[0]["storeName"]));
}
?>
<div><a href="index.php">Enter Another Transaction</a></div>
</body>
</html>