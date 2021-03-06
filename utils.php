<?php
class SqlDataManager {
	private $dBserverName = "localhost";
	private $dBuserName = "paulschl_money";
	private $dBpwd = "examplePassword";
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

	public function sql($sqlStr) {
		// Returns the result directly
		$this->connect();

	    $result = $this->dBconn->query($sqlStr);

	    $this->disconnect();
	    return $result;
	}

	public function doesEntryExist($tableName, $columnName, $value) {
		// tableName: string name of table to check
		// columnName: string name of column within the table to check
		// value: value to check for in that column
		// Returns true or false

		$valueEscaped = $this->secureFormInputText($value);

		$sql = "SELECT $columnName FROM $tableName WHERE $columnName = '$valueEscaped'";
		$match = $this->sqlQuery($sql);

		return !empty($match);
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

class DataPresenter {

	public static function printArrayAsFormOptions($inArr) {
		foreach ($inArr as $opt) {
			$text = str_replace("\\","",$opt["names"]);
			printf('<option value="%s">%s</option>', $opt["id"], $text);
		}
	}

	public static function printArrayAsListItems($inArr) {
		foreach ($inArr as $opt) {
			$text = str_replace("\\","",$opt["names"]);
			printf('<li value="%s" class="storeItem"><a href="#">%s</a></li>', $opt["id"], $text);
		}
	}

	public static function printArrayAsTable($arr, $tableId, $hdrs, $rowIds, $deleteCol) {
		echo "<table data-role='table' class='ui-responsive table-stroke table-stripe' id='$tableId' data-mode='reflow'>";
		echo '<thead>';
		echo '<tr>';
		foreach($hdrs as $hdr) {
			echo "<th>" . $hdr . "</th>";
		}
		if ($deleteCol) {
			echo "<th></th>";
		}
		echo '</tr>';
		echo '</thead>';

		echo '<tbody>';
		$ii = 0;
		foreach($arr as $key=>$row) {
		    printf("<tr id=%s>", $rowIds[$ii]);
		    $ii++;
		    foreach($row as $key2=>$row2){
		    	$text = str_replace("\\","",$row2);
		        echo "<td>" . $text . "</td>";
		    }
		    if ($deleteCol) {
		    	echo "<td><a href='#' class='ui-btn ui-icon-delete ui-btn-icon-notext ui-corner-all ui-mini deleteX'></a></td>";
			}	
		    echo "</tr>";
		}
		echo '</tbody>';
		echo "</table>";
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

	public function __construct($postArr, $transId, $storeId) {		
		$this->transId = $transId;
		$this->storeName = $storeId;
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
				"storeId" => $this->storeName,
				"categoryId" => $this->categories[$ii],
				"description" => $this->descriptions[$ii],
				"amount" => ($this->isDeposit ? $this->amts[$ii] : -$this->amts[$ii])
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

function exportCsv($sqlQuery, $dm) {
	// dm: of type SqlDataManager
	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename=transactions.csv');
	$output = fopen("php://output", "w");
	$data = $dm->sqlQuery($sqlQuery);

	// Put headers into CSV
	fputcsv($output, array_keys($data[0]));

	// Put data into CSV
	foreach($data as $row) {
		fputcsv($output, $row);
	}
	fclose($output);
}
?>