<?php 
        include_once("ensureLoggedIn.php");
        include_once("utils.php");         
        date_default_timezone_set("America/Chicago");
        $dbm = new SqlDataManager();
        $NUM_LATEST_TRANSACTIONS = 10;
?>
<!DOCTYPE html>
<html lang="en">
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
    <meta charset="UTF-8">
    <title>Data View</title>
</head>
<body>
<div data-role="page">
    <div data-role="navbar">
        <ul>
            <li><a href="index.php">Enter</a></li>
            <li><a href="view.php" class="ui-btn-active ui-state-persist">View</a></li>
        </ul>
    </div>
    <?php
    // Get latest transactions
    $latestId = (int)$dbm->sqlQuery("SELECT MAX(transactionId) FROM transactions")[0]["MAX(transactionId)"];
    $data = $dbm->sqlQuery("SELECT * FROM transactions WHERE transactionId > $latestId - $NUM_LATEST_TRANSACTIONS");
    
    // Display the data in a table
    DataPresenter::printArrayAsTable($data, "transactions");
    ?>
</div>
</body>
</html>