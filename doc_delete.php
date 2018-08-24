<?php
session_start();
include('conn.php');
$di_id = $_GET['di_id'];

$target_dir = "doc_files/".$di_id."/";

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}

if (deleteDirectory($target_dir)) {
	$qdeldi = "DELETE FROM doc_info WHERE di_id = '$di_id'";
	if (mysqli_query($conn, $qdeldi)) {
		$qdeldf = "DELETE FROM doc_file WHERE di_id = '$di_id'";
		if (mysqli_query($conn, $qdeldf)) {
			$_SESSION['flag'] = 2;
		} else {
			die("Error description: " . mysqli_error($conn));
		}
	} else {
		die("Error description: " . mysqli_error($conn));
	}
}

header('location:./');
mysqli_close($conn);
?>