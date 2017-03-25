<?php
class SqlDataManager {
	private $dBserverName = "localhost";
	private $dBuserName = "paulschl_transactions";
	private $dBpwd = "***REMOVED***";
	private $dBname = "paulschl_transactions";
	private $dBconn;

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
		echo "<option value={$opt["names"]}>{$opt["names"]}</option>\n";
	}
}
?>