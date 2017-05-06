<?php 
        include_once("ensureLoggedIn.php");
        include_once("utils.php");         
        date_default_timezone_set("America/Chicago");
        $dbm = new SqlDataManager();
        $NUM_LATEST_TRANSACTIONS = 20;
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

<!-- Handle Deleting Rows -->
<script>
    $(document).ready(function() {
        $(".deleteX").click(function() {
            var delId = $(this).parent().parent().attr("id");
            var rowElement = $(this).parent().parent();

            $.ajax({
                type:"POST",
                url:"deleteTransactionRow.php",
                data:{deleteId: delId},
                success:function(data) {
                    if (data == "success") {
                        rowElement.fadeOut(500).remove();
                    }
                }
            })
        });
    });
</script>

<!-- Build table -->
<div data-role="page">
    <div data-role="navbar">
        <ul>
            <li><a href="index.php" rel="external">Enter</a></li>
            <li><a href="view.php" class="ui-btn-active ui-state-persist">View</a></li>
        </ul>
    </div>
    <?php
    // Get latest transactions
    $latestId = (int)$dbm->sqlQuery("SELECT MAX(transactionId) FROM transactions")[0]["MAX(transactionId)"];
    $sql = "SELECT
                t.transactionId,
                t.date,
                c.names as catNames,
                s.names as storeNames,
                t.description,
                t.amount
            FROM transactions AS t
            INNER JOIN 
                categories AS c ON t.categoryId=c.id
            INNER JOIN 
                stores AS s ON t.storeId=s.id
            WHERE t.transactionId > $latestId - $NUM_LATEST_TRANSACTIONS
            ORDER BY t.date DESC, t.transactionId DESC";
    $data = $dbm->sqlQuery($sql);

    // Get unique ids of the transactions
    $ids = $dbm->sqlQuery(
        "SELECT id 
         FROM transactions 
         WHERE transactionId > $latestId - $NUM_LATEST_TRANSACTIONS
         ORDER BY date DESC, transactionId DESC"
    );
    foreach ($ids as $row) {
        $rowIds[] = $row["id"];
    }

    // Display the data in a table
    $hdr = array(
        "Transaction ID",
        "Date",
        "Category",
        "Store",
        "Description",
        "Amount");
    DataPresenter::printArrayAsTable($data, "transactions", $hdr, $rowIds, true);
    ?>

    <!-- Handle exporting data to CSV -->
    <form data-ajax="false" action="exportCsv.php" method="post" id="exportCsvForm">
        <input type="submit" name="export_csv" value="Download All Transactions">
    </form>
</div>
</body>
</html>