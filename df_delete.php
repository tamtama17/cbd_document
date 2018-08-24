<?php
session_start();
include('conn.php');
$df_id = $_GET['df_id'];
$di_id = $_GET['di_id'];

$qdf = "SELECT * FROM doc_file WHERE df_id = '$df_id'";
$qdfrun = mysqli_query($conn,$qdf);
$dfnya = mysqli_fetch_assoc($qdfrun);
$filenya = $dfnya['df_file_name'];
if (file_exists($filenya)) {
	if (unlink($filenya)) {
		$qdeldf = "DELETE FROM doc_file WHERE df_id = '$df_id'";
		if (mysqli_query($conn, $qdeldf)) {
			$qcek = "SELECT MAX(df_exp_date) AS latest_exp FROM doc_file WHERE di_id = '$di_id';";
			$qcek = mysqli_query($conn,$qcek);
			$cek = mysqli_fetch_assoc($qcek);
			$latest_exp = $cek['latest_exp'];
			$qupdateexp = "UPDATE doc_info SET di_exp_date='$latest_exp' WHERE di_id = '$di_id'";
			if (mysqli_query($conn, $qupdateexp)) {
				$_SESSION['flag'] = 2;
			} else {
				die("Error description: " . mysqli_error($conn));
			}
		} else {
			die("Error description: " . mysqli_error($conn));
		}
	}
}else{
	$qdeldf = "DELETE FROM doc_file WHERE df_id = '$df_id'";
	if (mysqli_query($conn, $qdeldf)) {
		$qcek = "SELECT MAX(df_exp_date) AS latest_exp FROM doc_file WHERE di_id = '$di_id';";
		$qcek = mysqli_query($conn,$qcek);
		$cek = mysqli_fetch_assoc($qcek);
		$latest_exp = $cek['latest_exp'];
		$qupdateexp = "UPDATE doc_info SET di_exp_date='$latest_exp' WHERE di_id = '$di_id'";
		if (mysqli_query($conn, $qupdateexp)) {
			$_SESSION['flag'] = 2;
		} else {
			die("Error description: " . mysqli_error($conn));
		}
	} else {
		die("Error description: " . mysqli_error($conn));
	}
}

header('location:doc_detail.php?id='.$di_id);
mysqli_close($conn);
?>