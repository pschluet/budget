<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $("#addTx").click(function() {
            $("fieldset.hidden:first").addClass("working visible");
            $("fieldset.working").removeClass("hidden working");
        })
        $("#removeTx").click(function() {
            $("fieldset.visible:last").addClass("working hidden");
            $("fieldset.working").removeClass("visible working");
        })
    })
    </script>
    <?php 
        include "utils.php"; 
        date_default_timezone_set("America/Chicago");
        $dbm = new SqlDataManager();
        $MAX_NUM_SPLIT_TRANSACTIONS = 10;
    ?>
    <meta charset="UTF-8">
    <title>Transaction Form</title>
</head>
<body>
<form action="insert.php" method="post">
    <p>
        <label for="date_entry">Date</label><br>
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
        <label for="store_name_dropdown">Store Name</label><br>
        <select name="storeNameDropdown" id="store_name_dropdown">
            <?php
                $opt = $dbm->sqlQuery("SELECT * FROM stores ORDER BY names ASC");
                echo "<option value=''></option>\n";
                printArrayAsFormOptions($opt);
            ?>
        </select><br>
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
        <fieldset class='{$class}'>
            <legend>Transaction {$label}</legend>
            <label for='category_entry{$ii}'>Category</label><br>
            <select name='category{$ii}' id='category_entry{$ii}''>";
                $opt = $dbm->sqlQuery("SELECT * FROM categories ORDER BY names ASC");
                printArrayAsFormOptions($opt);
            echo "
            </select></br>

            <label for='description_entry{$ii}'>Description</label><br>
            <input type='text' name='description{$ii}' id='description_entry{$ii}'></br>

            <label for='amount_entry{$ii}'>Amount<br>$</label>
            <input type='number' name='amount{$ii}' id='amount_entry{$ii}'>
        </fieldset>
        ";
    }
    ?>    
    <button type="button" id="addTx">Add Split Transaction</button>
    <button type="button" id="removeTx">Remove Split Transaction</button>
    </br>
    <input type="submit" value="Submit">
</form>
</body>
</html>