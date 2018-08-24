<?php
session_start();
include('conn.php');
if (isset($_POST['editDI'])) {
	$di_id = $_POST['di_id'];
	echo "di_id : ".$di_id."<br>";
	$di_name = $_POST['di_name'];
	echo "di_name : ".$di_name."<br>";
	$di_dept = $_POST['di_dept'];
	echo "di_dept : ".$di_dept."<br>";

	$qupdate = "UPDATE doc_info SET di_name='$di_name', di_dept='$di_dept' WHERE di_id = '$di_id'";
	if (mysqli_query($conn, $qupdate)) {
		$_SESSION['flag'] = 1;
	} else {
		die("Error description: " . mysqli_error($conn));
	}	
}
header('location:doc_detail.php?id='.$di_id);
mysqli_close($conn);
?>