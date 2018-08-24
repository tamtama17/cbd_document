<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<?php
	include('head.php');
	?>
</head>
<body>
	<?php
	include('conn.php');
	include('navbar.php');
	$di_id = $_GET['id'];
	$qselect = "SELECT * FROM doc_info WHERE di_id = '$di_id';";
	$qrun = mysqli_query($conn,$qselect);
	$result = mysqli_fetch_assoc($qrun);
	?>
	<div style="padding: 2rem;">
		<?php
		if (isset($_SESSION['flag'])) {
			$flag = $_SESSION['flag'];
			if ($flag == 1) {
				?>
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Data has been updated!
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<?php
			}
			if ($flag == 2) {
				?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Success!</strong> File has been deleted!
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<?php
			}
		}
		session_unset();
		session_destroy();
		?>
		<div class="row align-items-end">
			<div class="col">
				<h1><?php echo $result['di_name']; ?></h1>
				<h3><?php echo $result['di_dept']." Department"; ?></h3>
			</div>
			<div id="buttons" style="padding-right: 1rem;">
				<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newfileModal"><span><i class="fa fa-plus"></i></span> New File</button>
				<button type="button" class="btn btn-success" data-toggle="modal" data-target="#editModal"><span><i class="fa fa-pencil"></i></span> Edit</button>
				<button type="button" class="btn btn-danger" data-toggle="modal" data-target="#deleteModal"><span><i class="fa fa-trash"></i></span> Delete</button>
			</div>
		</div>
		<hr class="my-4">
		<table class="table table-hover table-bordered shadow-sm table-sm" id="detail_Table">
			<thead class="thead-dark">
				<tr>
					<th scope="col">Document Name</th>
					<th scope="col">Month</th>
					<th scope="col">Year</th>
					<th scope="col">Expired Date</th>
					<th scope="col"><i class="fa fa-cogs" aria-hidden="true"></i> Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$qdf = "SELECT * FROM doc_file WHERE di_id = '$di_id' ORDER BY df_exp_date DESC;";
				$qdfrun = mysqli_query($conn,$qdf);
				$dfnum = mysqli_num_rows($qdfrun);
				if ($dfnum<1) {
					?>
					<tr>
						<td colspan="5" style="text-align: center; color: red;"><strong>No Data</strong></td>
					</tr>
					<?php
				}
				while ($dfnya = mysqli_fetch_assoc($qdfrun)) {
					$df_id = $dfnya['df_id'];
					?>
					<tr>
						<td><a href="<?php echo $dfnya['df_file_name']; ?>"><?php echo $result['di_name']; ?></a></td>
						<td><?php echo $dfnya['df_month']; ?></td>
						<td><?php echo $dfnya['df_year']; ?></td>
						<td><?php echo $dfnya['df_exp_date']; ?></td>
						<td width="170px;">
							<center>
								<button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#editdf_<?php echo $df_id; ?>"><span><i class="fa fa-pencil"></i></span> Edit</button>
								<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletedf_<?php echo $df_id; ?>"><span><i class="fa fa-trash"></i></span> Delete</button>
							</center>
						</td>
					</tr>
					<div class="modal fade" tabindex="-1" id="editdf_<?php echo $df_id; ?>">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Edit File Document</h4>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<form method="post" action="df_edit.php" enctype="multipart/form-data">
									<input class="form-control" name="df_id" value="<?php echo $df_id; ?>" hidden="">
									<input class="form-control" name="di_id" value="<?php echo $di_id; ?>" hidden="">
									<div class="modal-body">
										<div class="row">
											<div class="col">
												<div class="form-group">
													<label><h5>Document File</h5></label>
													<div class="input-group mb-3">
														<div class="custom-file">
															<input type="file" id="fileImport" name="doc_file" class="custom-file-input">
															<label class="custom-file-label" for="fileImport">Choose file</label>
														</div>
													</div>
												</div>
											</div>
											<div style="width: 150px;">
												<label><h5>Month</h5></label>
												<select class="form-control custom-select" id="month_select" name="df_month" required="">
													<?php
													$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
													foreach ($months as $month) {
														if ($month == $dfnya['df_month']) {
															?>
															<option selected="" value='<?php echo $month; ?>'><?php echo $month; ?></option>
															<?php
														} else {
															?>
															<option value='<?php echo $month; ?>'><?php echo $month; ?></option>
															<?php
														}
													}
													?>
												</select>
											</div>
											<div style="width: 135px; padding-left: 1rem;">
												<label><h5>Year</h5></label>
												<select class="form-control custom-select" id="year_select" name="df_year" required="">
													<option value="" selected="">Select year</option>
													<?php
													$cur_year = date('Y')+5;
													for ($i=$cur_year; $i > 1970; $i--) {
														if ($i == $dfnya['df_year']) {
															?>
															<option selected="" value='<?php echo $i; ?>'><?php echo $i; ?></option>
															<?php
														} else {
															?>
															<option value='<?php echo $i; ?>'><?php echo $i; ?></option>
															<?php
														}
													}
													?>
												</select>
											</div>
											<div style="width: 175px; padding-left: 1rem; padding-right: 1rem;">
												<label for="df_exp_date_form"><h5>Expired Date</h5></label>
												<input class="form-control" name="df_exp_date" id="df_exp_date_form" type="date" required="" value="<?php echo $dfnya['df_exp_date'];?>">
											</div>
										</div>
									</div>
									<div class="modal-footer">
										<button type="submit" name="editDF" value="editDF" class="btn btn-primary">Edit</button>
										<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
									</div>
								</form>
							</div>
						</div>
					</div>
					<div class="modal fade" tabindex="-1" id="deletedf_<?php echo $df_id; ?>">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h4 class="modal-title">Warning!</h4>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
								<div class="modal-body">
									<div class="row">
										<div class="col-3">
											Name
										</div>:
										<div class="col"><?php echo $result['di_name']; ?></div>
									</div>
									<div class="row">
										<div class="col-3">
											Month
										</div>:
										<div class="col"><?php echo $dfnya['df_month']; ?></div>
									</div>
									<div class="row">
										<div class="col-3">
											Year
										</div>:
										<div class="col"><?php echo $dfnya['df_year']; ?></div>
									</div>
									<div class="row">
										<div class="col-3">
											Expired Date
										</div>:
										<div class="col"><?php echo date("F jS, Y", strtotime($dfnya['df_exp_date'])); ?></div>
									</div>
									<br>
									<p>Are you sure want to <strong>delete</strong> this file?</p>
								</div>
								<div class="modal-footer">
									<button type="button" onclick="window.location.href = 'df_delete.php?df_id=<?php echo $df_id; ?>&di_id=<?php echo $di_id; ?>';" class="btn btn-primary">Yes</button>
									<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
								</div>
							</div>
						</div>
					</div>
					<?php
				}
				?>
			</tbody>
		</table>
	</div>
	<div class="modal fade" tabindex="-1" id="newfileModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">New File Upload</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<form method="post" action="doc_input.php" enctype="multipart/form-data">
					<input class="form-control" name="di_id" value="<?php echo $di_id; ?>" hidden="">
					<div class="modal-body">
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label><h5>Document File</h5></label>
									<div class="input-group mb-3">
										<div class="custom-file">
											<input type="file" id="fileImport" name="doc_file" class="custom-file-input">
											<label class="custom-file-label" for="fileImport">Choose file</label>
										</div>
									</div>
								</div>
							</div>
							<div style="width: 150px;">
								<label><h5>Month</h5></label>
								<select class="form-control custom-select" id="month_select" name="df_month" required="">
									<option value="" selected="">Select month</option>
									<?php
									$months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
									foreach ($months as $month) {
									?>
										<option value='<?php echo $month; ?>'><?php echo $month; ?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div style="width: 135px; padding-left: 1rem;">
								<label><h5>Year</h5></label>
								<select class="form-control custom-select" id="year_select" name="df_year" required="">
									<option value="" selected="">Select year</option>
									<?php
									$cur_year = date('Y')+5;
									for ($i=$cur_year; $i > 1970; $i--) {
									?>
										<option value='<?php echo $i; ?>'><?php echo $i; ?></option>
									<?php
									}
									?>
								</select>
							</div>
							<div style="width: 175px; padding-left: 1rem; padding-right: 1rem;">
								<label for="df_exp_date_form"><h5>Expired Date</h5></label>
								<input class="form-control" name="df_exp_date" id="df_exp_date_form" type="date">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="new_file" value="submited" class="btn btn-primary">Create</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" id="deleteModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Warning!</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Are you sure want to <strong>delete</strong> this data?</p>
				</div>
				<div class="modal-footer">
					<button type="button" onclick="window.location.href = 'doc_delete.php?di_id=<?php echo $di_id; ?>';" class="btn btn-primary">Yes</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" tabindex="-1" id="editModal">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Edit Corporate Document</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<form method="post" action="doc_edit.php" enctype="multipart/form-data">
				<input class="form-control" name="di_id" value="<?php echo $di_id; ?>" hidden="">
					<div class="modal-body">
						<div class="form-group">
							<label for="doc_name_form"><h5>Document Name</h5></label>
							<input class="form-control" name="di_name" id="doc_name_form" value="<?php echo $result['di_name']; ?>">
						</div>
						<div class="form-group">
							<label for="dept_form"><h5>Department</h5></label>
							<input class="form-control" name="di_dept" id="dept_form" value='<?php echo $result['di_dept']; ?>'>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" name="editDI" value="editDI" class="btn btn-primary">Edit</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script>
		$(document).ready( function () {
			$('#detail_Table').DataTable({
				"lengthChange": false,
				"pageLength": 25
			});
		} );
	</script>

	<style type="text/css">
		th {
			text-align: center;
		}
	</style>
	<?php
	mysqli_close($conn);
	?>
</body>
</html>