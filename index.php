<?php 
        include_once("ensureLoggedIn.php");
        include_once("utils.php");         
        date_default_timezone_set("America/Chicago");
        $dbm = new SqlDataManager();
        $MAX_NUM_SPLIT_TRANSACTIONS = 10;
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

    <script>
    $(document).ready(function() {
        // Hide split transactions buttons on page load
        $("#removeTx").hide();
        $("#calcSplit").hide();

        $("#addTx").click(function() {
            $("fieldset.hidden:first").addClass("working visible");
            $("fieldset.working").removeClass("hidden working");

            // Make split buttons visible
            $("#removeTx").show();
            $("#calcSplit").show();
        })
        $("#removeTx").click(function() {
            $("fieldset.visible:last").addClass("working hidden");
            $("fieldset.working").removeClass("visible working");

            if ($("fieldset.visible").length < 2) {
                // Hide split transactions buttons when not relevant
                $("#removeTx").hide();
                $("#calcSplit").hide();
            }
        })   
        $("#calcSplit").click(function() {
            var splitTotal = Number(prompt("Input total transaction amount ($)"));

            // Get totals from all but last visible input
            var totalEntered = 0;
            $("fieldset.visible:not(:last) input[name*='amount']").each(function() {
                totalEntered += isNaN(this.valueAsNumber) ? 0 : this.valueAsNumber;
            });
            
            // Set final visible amount to the difference between total and entered
            var finalAmt = splitTotal - totalEntered;
            finalAmtRounded = Math.round(finalAmt * 100) / 100; // Round to 2 decimal places
            $("fieldset.visible:last input[name*='amount']").val(finalAmtRounded.toString());
        })        
    });
    $(document).on("pagebeforeshow","#entry", function(event) { // When entering this page
            var prevPage = event.handleObj.handler.arguments["1"].prevPage;
            if (prevPage[0] != null && prevPage.attr("data-url").indexOf("insert.php") > -1) {
                // If last page was insert.php, reset form
                $("#tForm")[0].reset();

                // Hide all but first split transaction elements
                $("fieldset").each(function(ii, elem) {
                    if (ii > 0) {
                        $(elem).removeClass("visible");
                        $(elem).addClass("hidden");
                    }
                });
            }            
    });
    </script>
    <meta charset="UTF-8">
    <title>Transaction Form</title>
</head>
<body>
<div data-role="page" id="entry">
    <div data-role="navbar">
        <ul>
            <li><a href="index.php" class="ui-btn-active ui-state-persist">Enter</a></li>
            <li><a href="view.php" rel="external">View</a></li>
        </ul>
    </div>
    <div data-role="main" class="ui-content">
        <form action="insert.php" method="post" id="tForm">
            <p>
                <label for="date_entry">Date</label>
                <?php
                    $today = date('Y-m-d');
                    echo "<input type='date' name='date' id='date_entry' value='{$today}'>"
                ?>        
            </p>
            <p>
                <label for="deposit_entry">Deposit</label>
                <input type="checkbox" name="deposit" id="deposit_entry">
            </p>
            <p>
                <label for="store_name_dropdown">Store Name</label>
                <select name="storeNameDropdown" id="store_name_dropdown">
                    <?php
                        $opt = $dbm->sqlQuery("SELECT id, names FROM stores ORDER BY names ASC");
                        echo "<option value=''></option>\n";
                        DataPresenter::printArrayAsFormOptions($opt);
                    ?>
                </select>
                <input type="text" name="storeNameTextbox" id="store_name_textbox">
            </p>
            <?php
            for ($ii = 0; $ii < $MAX_NUM_SPLIT_TRANSACTIONS; $ii++) {
                $label = $ii + 1;
                if ($ii > 0) {
                    $class = "hidden";
                } else {
                    $class = "visible";
                }
                echo "                
                <fieldset class='{$class}' data-role='collapsible' data-collapsed='false'>
                    <legend>Transaction {$label}</legend>                    
                    <label for='category_entry{$ii}'>Category</label>
                    <select name='category{$ii}' id='category_entry{$ii}''>";
                        $opt = $dbm->sqlQuery("SELECT id, names FROM categories ORDER BY names ASC");
                        DataPresenter::printArrayAsFormOptions($opt);
                    echo "
                    </select>

                    <label for='description_entry{$ii}'>Description</label>
                    <input type='text' name='description{$ii}' id='description_entry{$ii}'>

                    <label for='amount_entry{$ii}'>Amount ($)</label>
                    <input type='number' step=0.01 name='amount{$ii}' id='amount_entry{$ii}'>                    
                </fieldset>
                ";
            }
            ?>    
            <button type="button" id="addTx">Add Split Transaction</button>
            <button type="button" id="removeTx">Remove Split Transaction</button>
            <button type="button" id="calcSplit">Calculate Final Split Amount</button>
            
            <input type="submit" value="Submit">
        </form>
    </div>
</div>    
</body>
</html>