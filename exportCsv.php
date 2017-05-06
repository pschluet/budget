<?php 
include_once("ensureLoggedIn.php"); 
include_once("utils.php");

if(isset($_POST["export_csv"])) {
	$dbm = new SqlDataManager();
	$sql = "SELECT 
				t.transactionId as 'Transaction ID',
				t.date as 'Date',
				c.names as 'Category',
				s.names as 'Store',
				t.description as 'Description',
				t.amount as 'Amount'
			FROM transactions as t
			JOIN categories as c
				ON t.categoryId=c.id
			JOIN stores as s
				ON t.storeId=s.id
			ORDER BY t.date DESC, t.transactionId DESC";
    exportCsv($sql, $dbm);
}
?>

