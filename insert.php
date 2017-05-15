<!-- Handle Form Submission -->
<?php include_once("ensureLoggedIn.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include meta tag to ensure proper rendering and touch zooming -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Include jQuery Mobile stylesheets -->
    <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">

    <!-- Include the jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

    <!-- Include the jQuery Mobile library -->
    <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

    <meta charset="UTF-8">
    <title>Form Submission</title>
</head>
<body>
<?php

date_default_timezone_set("America/Chicago");
include_once("utils.php");
include_once("ensureLoggedIn.php"); 

?>
<div data-role="page" data-dialog="true">
    <div data-role="header">
        <h1>Result</h1>
    </div>
    <div data-role="main" class="ui-content">
        <?php
        $dbm = new SqlDataManager();
        $newTransactionId = (int)$dbm->sqlQuery("SELECT MAX(transactionId) FROM transactions")[0]["MAX(transactionId)"] + 1;

        // Insert store name into DB if it's not already there
        $storeRaw = $_REQUEST["storeNameTextbox"];
        $storeEntered = $dbm->secureFormInputText($storeRaw);
        if (!empty($storeEntered) and !($dbm->doesEntryExist("stores", "names", $storeEntered))) {
            if ($dbm->insertIntoTable("stores", array("names" => $storeEntered))) {
                echo "<p>Successfully entered store: $storeRaw</p>";
            } else {
                echo "<p>Failed to enter store: $storeRaw</p>";
            }
        }

        // Get store ID
        $storeEnteredQuery = $dbm->secureFormInputText($storeEntered);
        $sql = "SELECT id FROM stores WHERE names='$storeEnteredQuery'";
        $query = $dbm->sqlQuery($sql);
        $storeId = $query[0]["id"];

        // Process the form data
        $fp = new TransactionFormProcessor($_REQUEST, $newTransactionId, $storeId);
        $transactionsToInsert = $fp->getDataToInsertToDb();

        // Insert the transaction data into the database
        $ii = 1;
        foreach ($transactionsToInsert as $singleTransaction) {
            if ($dbm->insertIntoTable("transactions", $singleTransaction)) {
                printf("<p>Successfully entered transaction %d for \$%s.", $ii, $singleTransaction["amount"]);
            } else {
                printf("<p>Failed to enter transaction %d for \$%s.", $ii, $singleTransaction["amount"]);
            }    
            $ii++;
        }
        ?>
    </div>
</div>

</body>
</html>