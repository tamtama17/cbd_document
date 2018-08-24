<?php
session_start();
include('conn.php');
if (isset($_POST['new_file'])) {
	$di_id = $_POST['di_id'];

	$di_dept = $_POST['di_dept'];
	$df_month = $_POST['df_month'];
	$df_year = $_POST['df_year'];
	$df_exp_date = $_POST['df_exp_date'];

	if ($di_id == NULL) {
		$di_name = $_POST['di_name'];
		$qinsdi = "INSERT INTO doc_info(di_name, di_dept, di_exp_date) VALUES ('$di_name', '$di_dept', '$df_exp_date')";
		if (mysqli_query($conn, $qinsdi)) {
			$di_id = mysqli_insert_id($conn);
		} else {
			die("Error description: " . mysqli_error($conn));
		}
	} else {
		$qdoc_info = "SELECT * FROM doc_info WHERE di_id = '$di_id'";
		$qdoc_inforun = mysqli_query($conn,$qdoc_info);
		$doc_info = mysqli_fetch_assoc($qdoc_inforun);
		if ($df_exp_date > $doc_info['di_exp_date']) {
			$qupdateexp = "UPDATE doc_info SET di_exp_date='$df_exp_date' WHERE di_id = '$di_id'";
			if (!mysqli_query($conn, $qupdateexp)) {
				die("Error description: " . mysqli_error($conn));
			}
		}
	}

	if ($_FILES["doc_file"]["error"] == 0) {
		$filename=$_FILES["doc_file"]["name"];
		$tmp = explode(".", $filename);
		$extension=end($tmp);
		$newfilename= $di_id."_".$df_month."_".$df_year.".".$extension;
		$target_dir = "doc_files/".$di_id."/";
		if (!file_exists($target_dir)) {
			mkdir($target_dir);
			$target_dir = $target_dir.$df_year."/";
			if (!file_exists($target_dir)) {
				mkdir($target_dir);
				$target_dir = $target_dir.$df_month."/";
				if (!file_exists($target_dir)) {
					mkdir($target_dir);
				}
			} else {
				$target_dir = $target_dir.$df_month."/";
				if (!file_exists($target_dir)) {
					mkdir($target_dir);
				}
			}
		} else {
			$target_dir = $target_dir.$df_year."/";
			if (!file_exists($target_dir)) {
				mkdir($target_dir);
				$target_dir = $target_dir.$df_month."/";
				if (!file_exists($target_dir)) {
					mkdir($target_dir);
				}
			} else {
				$target_dir = $target_dir.$df_month."/";
				if (!file_exists($target_dir)) {
					mkdir($target_dir);
				}
			}
		}
		$target_file = $target_dir . $newfilename;
		echo "$target_file<br>";
		if (move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_file)) {
			$qinsdf = "INSERT INTO doc_file(df_file_name, df_exp_date, df_month, df_year, di_id) VALUES ('$target_file', '$df_exp_date', '$df_month', '$df_year', '$di_id')";
			if (mysqli_query($conn, $qinsdf)) {
				$_SESSION['flag'] = 1;
			} else{
				die("Error description: " . mysqli_error($conn));
			}
		} else{
			die("Something error while uploading your file. Please try again.<br>");
		}
	} else{
		die("Error uploading file.<br>Return Code: ".$_FILES["doc_file"]["error"]."<br>");
	}

	header('location:./');
	mysqli_close($conn);
}