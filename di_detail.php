<?php
include('conn.php');
$id = intval($_GET['id']);

$qdi = "SELECT * FROM doc_info WHERE di_id = '$id'";
$qdirun = mysqli_query($conn,$qdi);
$di = mysqli_fetch_assoc($qdirun);
$dept = $di['di_dept'];
?>

<div class="form-group">
	<label><h5>Department</h5></label>
	<input class="form-control" disabled="" value="<?php echo $dept; ?>" style="background-color: white !important;">
</div>
<?php
mysqli_close($conn);
?>