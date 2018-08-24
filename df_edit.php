<?php
session_start();
include('conn.php');
if (isset($_POST['editDF'])) {
	$df_id = $_POST['df_id'];
	echo "df_id : ".$df_id."<br>";
	$di_id = $_POST['di_id'];
	echo "di_id : ".$di_id."<br>";

	$df_month = $_POST['df_month'];
	$df_year = $_POST['df_year'];
	$df_exp_date = $_POST['df_exp_date'];

	if ($_FILES["doc_file"]["error"] == 0) {
		$qdf = "SELECT * FROM doc_file WHERE df_id = '$df_id'";
		$qdfrun = mysqli_query($conn,$qdf);
		$dfnya = mysqli_fetch_assoc($qdfrun);
		$filenya = $dfnya['df_file_name'];
		if (file_exists($filenya)) {
			if (unlink($filenya)) {
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
				echo "ada file<br>";
				if (move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_file)) {
					$qupdate = "UPDATE doc_file SET df_file_name='$target_file',df_exp_date='$df_exp_date',df_month='$df_month',df_year='$df_year' WHERE df_id = '$df_id'";
					if (mysqli_query($conn, $qupdate)) {
						$qcek = "SELECT MAX(df_exp_date) AS latest_exp FROM doc_file WHERE di_id = '$di_id';";
						$qcek = mysqli_query($conn,$qcek);
						$cek = mysqli_fetch_assoc($qcek);
						$latest_exp = $cek['latest_exp'];
						$qupdateexp = "UPDATE doc_info SET di_exp_date='$latest_exp' WHERE di_id = '$di_id'";
						if (mysqli_query($conn, $qupdateexp)) {
							$_SESSION['flag'] = 1;
						} else {
							die("Error description: " . mysqli_error($conn));
						}
					} else{
						die("Error description: " . mysqli_error($conn));
					}
				} else{
					die("Something error while uploading your file. Please try again.<br>");
				}
			}
		} else {
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
			echo "ada file<br>";
			if (move_uploaded_file($_FILES["doc_file"]["tmp_name"], $target_file)) {
				$qupdate = "UPDATE doc_file SET df_file_name='$target_file',df_exp_date='$df_exp_date',df_month='$df_month',df_year='$df_year' WHERE df_id = '$df_id'";
				if (mysqli_query($conn, $qupdate)) {
					$qcek = "SELECT MAX(df_exp_date) AS latest_exp FROM doc_file WHERE di_id = '$di_id';";
					$qcek = mysqli_query($conn,$qcek);
					$cek = mysqli_fetch_assoc($qcek);
					$latest_exp = $cek['latest_exp'];
					$qupdateexp = "UPDATE doc_info SET di_exp_date='$latest_exp' WHERE di_id = '$di_id'";
					if (mysqli_query($conn, $qupdateexp)) {
						$_SESSION['flag'] = 1;
					} else {
						die("Error description: " . mysqli_error($conn));
					}
				} else{
					die("Error description: " . mysqli_error($conn));
				}
			} else{
				die("Something error while uploading your file. Please try again.<br>");
			}
		}
	} elseif ($_FILES["doc_file"]["error"] == 4) {
		echo "ga ada file<br>";
		$qnamafile = "SELECT * FROM doc_file WHERE df_id = '$df_id'";
		$qnamafilerun = mysqli_query($conn,$qnamafile);
		$namefilenya = mysqli_fetch_assoc($qnamafilerun);
		$filename = $namefilenya['df_file_name'];
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
		if (rename($filename, $target_file)) {
			$qupdate = "UPDATE doc_file SET df_file_name='$target_file',df_exp_date='$df_exp_date',df_month='$df_month',df_year='$df_year' WHERE df_id = '$df_id'";
			if (mysqli_query($conn, $qupdate)) {
				$qnamafile = "SELECT * FROM doc_file WHERE df_id = '$df_id'";
				$qnamafilerun = mysqli_query($conn,$qnamafile);
				$namefilenya = mysqli_fetch_assoc($qnamafilerun);
				$filename = $namefilenya['df_file_name'];

				$qcek = "SELECT MAX(df_exp_date) AS latest_exp FROM doc_file WHERE di_id = '$di_id';";
				$qcek = mysqli_query($conn,$qcek);
				$cek = mysqli_fetch_assoc($qcek);
				$latest_exp = $cek['latest_exp'];
				$qupdateexp = "UPDATE doc_info SET di_exp_date='$latest_exp' WHERE di_id = '$di_id'";
				if (mysqli_query($conn, $qupdateexp)) {
					$_SESSION['flag'] = 1;
				} else {
					die("Error description: " . mysqli_error($conn));
				}
			} else{
				die("Error description: " . mysqli_error($conn));
			}
		}
	} else{
		die("Error uploading file.<br>Return Code: ".$_FILES["doc_file"]["error"]."<br>");
	}
}
header('location:doc_detail.php?id='.$di_id);
mysqli_close($conn);
?>