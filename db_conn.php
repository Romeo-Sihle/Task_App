<?php 

$sName = "localhost:3306";
$uName = "root";
$pass = "";
$db_name = "to_do_list";

try 
{
	$conn = new PDO("mysql:host=$sName; dbname=$db_name", $uName, $pass);

	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "Connection failed : ". $e->getMessage();
}