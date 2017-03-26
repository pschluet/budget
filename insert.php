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

var_dump(array_keys($_REQUEST));

$dataToInsert = array(
    "date" => $_REQUEST["date"],
    "category" => $_REQUEST["category"],
    "storeName" => (empty($_REQUEST["storeNameDropdown"]) ? $_REQUEST["storeNameTextbox"] : $_REQUEST["storeNameDropdown"]),
    "description" => $_REQUEST["description"],
    "isWithdrawal" => ($_REQUEST["transType"] == "withdrawal" ? true : false),
    "isSplitTrans" => (array_key_exists("split", $_REQUEST) ? true : false),
    "amount" => $_REQUEST["amount"]
);
if (!empty($dataToInsert["amount"]) && !empty($dataToInsert["description"])) {
    if ($dbm->insertIntoTable("transactions", $dataToInsert)) {
        echo "<h1>Success!</h1>";
    } else {
        echo "<h1>Fail!</h1>";
    }
} else {
    echo "Please fill in at least description and amount.";
}
?>
</body>
</html>