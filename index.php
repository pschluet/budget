<!DOCTYPE html>
<html lang="en">
<head>
    <?php 
        include "utils.php"; 
        date_default_timezone_set("America/Chicago");
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
        <label for="category_entry">Category</label><br>
        <select name="category" id="category_entry">
            <?php
                $dbm = new SqlDataManager();
                $opt = $dbm->sqlQuery("SELECT * FROM categories ORDER BY names ASC");
                printArrayAsFormOptions($opt);
            ?>
        </select>
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
    <p>
        <label for="description_entry">Description</label><br>
        <input type="text" name="description" id="description_entry">
    </p>
    <p>
        <fieldset>
            <legend>Type</legend>
            <label for="withdrawal_entry">Withdrawal</label>
            <input type="radio" name="transType" id="withdrawal" value="withdrawal" checked="withdrawal"><br>
            <label for="female">Deposit</label>
            <input type="radio" name="transType" id="deposit" value="deposit"><br>
        </fieldset>
    </p>
    <p>
        <label for="split_entry">Split Transaction</label><br>
        <input type="checkbox" name="split" id="split_entry">
    </p>
    <p>
        <label for="amount_entry">Amount <br>$</label>
        <input type="number" name="amount" id="amount_entry">
    </p>
    <input type="submit" value="Submit">
</form>
</body>
</html>