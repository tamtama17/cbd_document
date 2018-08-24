<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "corp_doc";

$conn = mysqli_connect($host, $user, $password, $dbname);

if (!$conn) {
	die("error in connection");
}
?>