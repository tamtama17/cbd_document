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
	?>
	<div style="padding: 2rem;">
		<?php
		$qcekmin = "SELECT MIN(di_exp_date) AS minimal FROM doc_info;";
		$qcekminrun = mysqli_query($conn,$qcekmin);
		$cekmin = mysqli_fetch_assoc($qcekminrun);
		$tahun = date('Y');
		$bulan = date('m')+4;
		if ($bulan > 12) {
			$tahun = $tahun+1;
			$bulan = $bulan-12;
		}
		$tgl = date('d');
		$max = $tahun."-".$bulan."-".$tgl;
		if ($cekmin['minimal'] < $max) {
			?>
			<div class="modal fade" tabindex="-1" id="reminder">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h4 class="modal-title">Warning!</h4>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
						</div>
						<div class="modal-body">
							<p>This documents will expire within 4 month, please update your document.</p>
							<div class="row">
								<div class="col"><strong>Name</strong></div>
								<div class="col"><strong>Department</strong></div>
								<div class="col"><strong>Expired Date</strong></div>
							</div>
							<?php
							$qdi = "SELECT * FROM doc_info WHERE di_exp_date < '$max';";
							$qdirun = mysqli_query($conn,$qdi);
							while ($di_list = mysqli_fetch_assoc($qdirun)) {
								?>
								<div class="row">
									<div class="col"><?php echo $di_list['di_name']; ?></div>
									<div class="col"><?php echo $di_list['di_dept']; ?></div>
									<div class="col"><?php echo date("F jS, Y", strtotime($di_list['di_exp_date'])); ?></div>
								</div>
								<?php
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<script>
				$(document).ready(function(){
					$("#reminder").modal();
				});
			</script>
			<?php
		}
		if (isset($_SESSION['flag'])) {
			$flag = $_SESSION['flag'];
			if ($flag == 1) {
				?>
				<div class="alert alert-primary alert-dismissible fade show" role="alert">
					<strong>Success!</strong> New data has been added!
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<?php
			}
			if ($flag == 2) {
				?>
				<div class="alert alert-danger alert-dismissible fade show" role="alert">
					<strong>Success!</strong> Data has been deleted!
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				</div>
				<?php
			}
		}
		session_unset();
		session_destroy();
		?>
		<button type="button" class="btn btn-primary" style="float: left;" data-toggle="modal" data-target="#newfileModal"><span><i class="fa fa-plus"></i></span> New File</button>
		<table class="table table-hover table-bordered shadow-sm" id="ml_Table">
			<thead class="thead-dark">
				<tr>
					<th width="40%" scope="col">Document Name</th>
					<th width="30%" scope="col">Department</th>
					<th width="30%" scope="col">Expired Date</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$qselect = "SELECT * FROM doc_info;";
				$qrun = mysqli_query($conn,$qselect);
				while ($result = mysqli_fetch_assoc($qrun)) {
					$di_id = $result['di_id'];
				?>
				<tr style="cursor: pointer;" onclick="window.location.href = 'doc_detail.php?id=<?php echo $result['di_id']; ?>';">
					<td><?php echo $result['di_name']; ?></td>
					<td><?php echo $result['di_dept']; ?></td>
					<td><?php echo $result['di_exp_date'];?></td>
				</tr>
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
					<div class="modal-body">
						<div id="existingDocName">
							<div class="form-group">
								<label for="doc_name_select"><h5>Document Name</h5></label>
								<div class="row">
									<div class="col">
										<select class="form-control custom-select" id="doc_name_select" onchange="selectDocName(this.value)" name="di_id" required="">
											<option value="" selected="">Select document name</option>
											<?php
											$qname = "SELECT DISTINCT di_id, di_name FROM doc_info;";
											$qnamerun = mysqli_query($conn,$qname);
											while ($doc_name_list = mysqli_fetch_assoc($qnamerun)) {
											?>
												<option value='<?php echo $doc_name_list['di_id']; ?>'><?php echo $doc_name_list['di_name']; ?></option>
											<?php
												}
											?>
										</select>
										<small id="topicHelp" class="form-text text-muted">If document type doesn't exist, click '<span><i class="fa fa-plus"></i></span> New' button to create new</small>
									</div>
									<div style="margin-right: 1rem;">
										<button type="button" onclick="newDoc()" class="btn btn-primary"><span><i class="fa fa-plus"></i></span> New</button>
									</div>
								</div>
							</div>
							<div id="dept_doc_info"></div>
						</div>
						<div id="newDocName" style="display: none;">
							<div class="form-group">
								<label for="doc_name_form"><h5>Document Name</h5></label>
								<div class="row">
									<div class="col">
										<input class="form-control" name="di_name" id="doc_name_form">
										<small id="comHelp" class="form-text text-muted">Click '<span><i class="fa fa-times"></i></span> Cancel' button to select from existing document name</small>
									</div>
									<div style="margin-right: 1rem;">
										<button type="button" onclick="newDocCancel()" class="btn btn-danger"><span><i class="fa fa-times"></i></span> Cancel</button>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="dept_form"><h5>Department</h5></label>
								<input class="form-control" name="di_dept" id="dept_form" required="">
							</div>
						</div>
						<div class="row">
							<div class="col">
								<div class="form-group">
									<label><h5>Document File</h5></label>
									<div class="input-group mb-3">
										<div class="custom-file">
											<input type="file" id="fileImport" name="doc_file" class="custom-file-input" required="">
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
					</div>
				</form>
			</div>
		</div>
	</div>

	<style type="text/css">
		th{
			text-align: center;
		}
	</style>

	<script>
	$(document).ready( function () {
		$('#ml_Table').DataTable({
			"lengthChange": false,
			"pageLength": 25
		});
	} );

	function newDoc() {
		document.getElementById("newDocName").style.display = "block";
		document.getElementById("existingDocName").style.display = "none";
		document.getElementById('doc_name_select').value = '';
		document.getElementById("doc_name_select").required = false;
		document.getElementById("doc_name_form").required = true;
	}
	function newDocCancel() {
		document.getElementById("newDocName").style.display = "none";
		document.getElementById("existingDocName").style.display = "block";
		document.getElementById('doc_name_form').value = '';
		document.getElementById("doc_name_form").required = false;
		document.getElementById("dept_form").required = false;
		document.getElementById("doc_name_select").required = true;
	}

	function selectDocName(str) {
		if (str=="") {
			document.getElementById("dept_doc_info").innerHTML="";
			return;
		}
		if (window.XMLHttpRequest) {
			// code for IE7+, Firefox, Chrome, Opera, Safari
			xmlhttp=new XMLHttpRequest();
		} else { // code for IE6, IE5
			xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
		xmlhttp.onreadystatechange=function() {
			if (this.readyState==4 && this.status==200) {
				document.getElementById("dept_doc_info").innerHTML=this.responseText;
			}
		}
		xmlhttp.open("GET","di_detail.php?id="+str,true);
		xmlhttp.send();
	}
	</script>

	<?php
	mysqli_close($conn);
	?>
</body>
</html>