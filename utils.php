<?php
class SqlDataManager {
	private $dBserverName = "localhost";
	private $dBuserName = "paulschl_money";
	private $dBpwd = "***REMOVED***";
	private $dBname = "paulschl_money";
	private $dBconn;

	public function secureFormInputText($inTxt) {
		$this->connect();
		$secureTxt = $this->dBconn->real_escape_string($inTxt);
		$this->disconnect();

		return $secureTxt;
	}

	public function sqlQuery($queryStr) {
		// Returns result in an associative array		
		$this->connect();

		// Perform query and put results into associative array
		$results_array = array();
	    $result = $this->dBconn->query($queryStr);
	    while ($row = $result->fetch_assoc()) {
	    	$results_array[] = $row;
	    }

	    $this->disconnect();
	    return $results_array;
	}

	public function insertIntoTable($tableName, $data) {
		// tableName: string name of table to insert data into
		// data: associative array with column name as key and raw user input value as value
		// returns 1 on success, 0 on failure

		$this->connect();

		// Escape user input for security
		foreach ($data as $key => $value) {
			if (is_bool($value)) {
				$secureData[$key] = ($value ? "TRUE" : "FALSE");
			} else {
				$secureData[$key] = "'" . $this->dBconn->real_escape_string($value) . "'";
			}
		}

		// Insert into sql database
		$sql = "INSERT INTO " . $tableName;
		$sql .= " (".implode(", ", array_keys($secureData)).") VALUES ";
		$sql .= " (".implode(", ", array_values($secureData)).")";

		var_dump($sql);

		if ($this->dBconn->query($sql) == true) {
			$success = true;
		} else {
			$success = false;
			print("Error: " . $this->dBconn->error);
		}

		$this->disconnect();

		return $success;
	}

	private function disconnect() {
		$this->dBconn->close();
	}

	private function connect() {
		// Connect to database
		$this->dBconn = new mysqli($this->dBserverName, 
			$this->dBuserName, 
			$this->dBpwd, 
			$this->dBname);
	    if ($this->dBconn->connect_error) {
	        die("Connection failed: " . $this->dBconn->connect_error);
	    }
	}
}

function printArrayAsFormOptions($inArr) {
	foreach ($inArr as $opt) {
		echo "<option value='{$opt["names"]}''>{$opt["names"]}</option>\n";
	}
}

class TransactionFormProcessor {
	private $numTransactions;
	private $transId;
	private $categories;
	private $storeName;
	private $descriptions;
	private $isDeposit;
	private $amts;
	private $date;

	public function __construct($postArr, $transId) {		
		$this->transId = $transId;
		$this->storeName = $postArr["storeNameDropdown"];
		$this->isDeposit = array_key_exists("deposit", $postArr);
		$this->date = $postArr["date"];

		// Number of non-empty amountXX input fields determines number of transactions
		$this->amts = array_filter($this->getFieldValues($postArr, "amount"));
		$this->numTransactions = count($this->amts);

		$categories = $this->getFieldValues($postArr, "category");
		$this->categories = array_slice($categories, 0, $this->numTransactions);

		$descriptions = $this->getFieldValues($postArr, "description");
		$this->descriptions = array_slice($descriptions, 0, $this->numTransactions);	
	}

	public function getDataToInsertToDb() {
		$outArr = [];

		for ($ii = 0; $ii < $this->numTransactions; $ii++) {
			$outArr[] = array(
				"transactionId" => (string)$this->transId,
				"date" => $this->date,
				"storeName" => $this->storeName,
				"isDeposit" => $this->isDeposit,
				"category" => $this->categories[$ii],
				"description" => $this->descriptions[$ii],
				"amount" => $this->amts[$ii]
			);
		}

		return $outArr;
	}

	private function getFieldValues($postArr, $fieldSubStr) {

		$ii = 0;
		$vals = [];
		foreach ($postArr as $key => $value) {
			if (strpos($key, $fieldSubStr) !== false) {
				$vals[] = $value;
			}
		}

		return $vals;
	}
}
?>