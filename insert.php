<!-- Handle Form Submission -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Form Submission</title>
</head>
<body>
<?php

date_default_timezone_set("America/Chicago");
include "utils.php";

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

?>
</body>
</html>