<?php
if (!file_exists('config.php'))  
{ 
    header('location:install.php');die;
}else{
    // @unlink('install.php');
}
include 'config.php';
session_start();
if(!isset($_SESSION['username'])) {
    header('location:login.php');die;
}

if (@$_REQUEST['i'] == 'logout') {
	session_destroy();
	header('location:login.php');die;
}

if (@$_REQUEST['i'] == 'adddatabase') {
		error_reporting(E_NOTICE || E_WARNING);
		$db['name'] = $_POST['name'];
		$db['database'] = $_POST['database'];
		$db['id'] = $_POST['id'];

	$conn = new mysqli($config['server'].$config['port'],$config['usernameserver'],$config['passwordserver'],$db['database']);
	if ($conn->connect_error) {
    	header("Content-Type: application/json");
	    echo json_encode(array('status' => 'failed'), JSON_PRETTY_PRINT);
	  } else {
	  	header("Content-Type: application/json");
	    echo json_encode(array('status' => 'success'), JSON_PRETTY_PRINT);
	    if (!file_exists('db.json'))  
		{ 
		    fopen("db.json", 'w');
		}
		$data = file_get_contents('db.json');
		$data_array = json_decode($data, true);
		$data_array[] = $db;
		$data_array = json_encode($data_array, JSON_PRETTY_PRINT);
		file_put_contents('db.json', $data_array);
	  }
	
	die;
}
if (@$_REQUEST['i'] == 'setdb') {
	$_SESSION['iddb'] = $_GET['iddb'];
}

if(isset($_SESSION['iddb'])) {
    header('location:input.php');die;
}

if (@$_REQUEST['i'] == 'changepass' && isset($_POST['curpass']) && isset($_POST['newpass'])) {
	if (md5($_POST['curpass']) != $config['password']) {
		header("Content-Type: application/json");
	    echo json_encode(array('status' => 'error'), JSON_PRETTY_PRINT);die;
	}elseif (md5($_POST['newpass']) == md5($_POST['curpass'])) {
		header("Content-Type: application/json");
	    echo json_encode(array('status' => 'error2'), JSON_PRETTY_PRINT);die;
	}else{
		$file_config = fopen('config.php', 'w+');
	    $isi = '<?php
	    $config = array(
	        "fullname" => "'.$config['fullname'].'",
	        "username" => "'.$config['username'].'",
	        "password" => "'.md5($_POST['newpass']).'",
	        "server" => "'.$config['server'].'",
	        "usernameserver" => "'.$config['usernameserver'].'",
	        "passwordserver" => "'.$config['passwordserver'].'",
	        "port" => "'.$config['port'].'",
	    );

	    ?>
	    ';

	    fwrite($file_config, $isi);
	    fclose($file_config);

	    header("Content-Type: application/json");
	    echo json_encode(array('status' => 'success'), JSON_PRETTY_PRINT);

	    die;
	}
}

if (@$_REQUEST['i'] == 'editconfig' && isset($_POST['usernameserver'])) {
	$file_config = fopen('config.php', 'w+');
	$port = ($_POST['port'] === "3306" ? "" : ":$_POST[port]");
	    $isi = '<?php
	    $config = array(
	        "fullname" => "'.$config['fullname'].'",
	        "username" => "'.$config['username'].'",
	        "password" => "'.$config['password'].'",
	        "server" => "'.$_POST['server'].'",
	        "usernameserver" => "'.$_POST['usernameserver'].'",
	        "passwordserver" => "'.$_POST['passwordserver'].'",
	        "port" => "'.$port.'",
	    );

	    ?>
	    ';

	    fwrite($file_config, $isi);
	    fclose($file_config);

	    die;
}

?>

<!doctype html>
<html lang="en">
  <head>

    <title>Query Builder</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" type="text/css" href="assets/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="assets/css/mdb.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/sa/sweetalert.css"> 
    <script type="text/javascript" src="assets/js/jquery-2.1.0.js"></script>
    </head>
<body style="background: #ffffff;">
	<nav class="navbar navbar-dark primary-color" style="margin-bottom: 30px;">
	  <span class="navbar-brand">Query</span>

	  <div class="float-right">
	  		<ul class="navbar-nav ml-auto nav-flex-icons">
	  			<li class="nav-item" style="padding-right: 15px;">
	  				<span class="navbar-text white-text">
				      	Hi, <?=$config['fullname']?>
				      </span>
  				</li>
	  			<li class="nav-item" style="padding-right: 15px;"><a data-toggle="modal" data-target="#config" class="nav-link waves-effect waves-light"><i class="fa fa-cogs"></i></a></li>
	  			<li class="nav-item"><a href="?i=logout" class="nav-link waves-effect waves-light"><i class="fa fa-sign-out-alt"></i></a></li>
	  		</ul>
	  </div>
	</nav>
	 <main>
        <div class="container">
                <div class="row justify-content-md-center">
                	<div class="col-md-10">
                		<div class="card">
						  <div class="card-header h5 text-white bg-primary">
						  		Query Builder
						  </div>
						  <div class="card-body">
						  	<div class="h5">
						  		List Database
						  		<div class="float-right">
									<button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#adddatabase">
									  <i class="fa fa-plus"></i>
									</button>

									
									<div class="modal fade" id="adddatabase" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
							  aria-hidden="true">
									  <div class="modal-dialog" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <h4 class="modal-title w-100" id="myModalLabel">Add Database</h4>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">
									        <form id="formdb">
									        		<input type="hidden" name="id">
									                <div class="md-form">
													  <input type="text" id="name" name="name" class="form-control" placeholder="Database 1" autofocus required>
													  <label for="name">Name</label>
													</div>
											      	<div class="md-form">
													  <input type="text" id="database" name="database" class="form-control" placeholder="database" required>
													  <label for="database">Database Name</label>
													</div>
									                <button class="btn btn-outline-primary btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Add Database</button>
									          </form>
									      </div>
									    </div>
									  </div>
								</div>
							  </div>
						  	</div>
						  <hr>
						  <table id="table" class="table table-striped table-bordered" style="width:100%">
					        <thead>
					            <tr>
					                <th>Name</th>
					                <th>Database</th>
					                <th>#</th>
					            </tr>
					        </thead>
					        <tbody id="rowtable">
					        	
					        </tbody>
					    </table>
						  </div>
						</div>
                	</div>
                </div>
        </div>
    </main>

    <div class="modal fade" id="config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
							  aria-hidden="true">
									  <div class="modal-dialog" role="document">
									    <div class="modal-content">
									      <div class="modal-header">
									        <h4 class="modal-title w-100" id="myModalLabel">Configuration</h4>
									        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
									          <span aria-hidden="true">&times;</span>
									        </button>
									      </div>
									      <div class="modal-body">
									      	<b>Change Password</b>
									        <form id="editpass">
									                <div class="md-form">
													  <input type="password" id="curpass" name="curpass" class="form-control" required>
													  <label for="curpass">Current Password</label>
													</div>
											      	<div class="md-form">
													  <input type="password" id="newpass" name="newpass" class="form-control" required>
													  <label for="newpass">New Password</label>
													</div>
									                <button class="btn btn-outline-primary btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Change Password</button>
									          </form>
									        <hr>
									        <b>Server Configuration</b>
									        <form id="editconfig">
									                <div class="md-form">
					                                    <input type="text" name="server" class="form-control" value="<?=$config['server']?>" required>
					                                    <label for="orangeForm-email">Server</label>
					                                </div>

					                                <div class="md-form">
					                                    <input type="text" name="usernameserver" class="form-control" value="<?=$config['usernameserver']?>" required>
					                                    <label for="orangeForm-email">Username</label>
					                                </div>

					                                <div class="md-form">
					                                    <input type="password" name="passwordserver" class="form-control" value="<?=$config['passwordserver']?>">
					                                    <label for="orangeForm-pass">Password</label>
					                                </div>

					                                <div class="md-form">
					                                    <input type="number" name="port" class="form-control" value="<?=$config['port'] === '' ? '3306' : ''?>" required>
					                                    <label for="orangeForm-email">Port</label>
					                                    <small>Default Port 3306</small>
					                                </div>
									                <button class="btn btn-outline-primary btn-rounded btn-block my-4 waves-effect z-depth-0" type="submit">Change</button>
									          </form>
									      </div>
									    </div>
									  </div>
								</div>

	<script type="text/javascript" src="assets/bootstrap/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
	<script type="text/javascript" src="assets/sa/sweetalert.min.js"></script>
	<script type="text/javascript" src="assets/sa/sweetalert-dev.js"></script>
	<script type="text/javascript" src="assets/js/addons/datatables.min.js"></script>
					    <script type="text/javascript">
							// $(document).ready(function() {
							// 	setTimeout(function() {
							// 	// Material Design example
							// 	$('#table').DataTable();
							// 	$('#example_wrapper').find('label').each(function () {
							// 	$(this).parent().append($(this).children());
							// 	});
							// 	$('#example_wrapper .dataTables_filter').find('input').each(function () {
							// 	$(this).attr("placeholder", "Search");
							// 	$(this).removeClass('form-control-sm');
							// 	});
							// 	$('#example_wrapper .dataTables_length').addClass('d-flex flex-row');
							// 	$('#example_wrapper .dataTables_filter').addClass('md-form');
							// 	$('#example_wrapper select').removeClass(
							// 	'custom-select custom-select-sm form-control form-control-sm');
							// 	$('#example_wrapper select').addClass('mdb-select');
							// 	$('#example_wrapper .mdb-select').material_select();
							// 	$('#example_wrapper .dataTables_filter').find('label').remove();
							// }, 100);
							// } );
						</script>
	<script type="text/javascript">
		function genid(){
									$.ajax({
								         type: "GET",
								         url: "db.json",
								         dataType: "json",
								      }).done(function( data ) {
								      	$('input[name="id"]').val(data.length+1);
								     });
		}
		genid();
		function setdb(id) {
									$.ajax({
								         type: "GET",
								         url: "?i=setdb&iddb="+id,
								      }).done(function( data ) {
								      	window.location = 'input.php';
								     });
		}

		function table() {
									$.ajax({
								         type: "GET",
								         url: "db.json",
								         dataType: "json",
								      }).done(function( data ) {
								      	$.each(data, function(k, v) {
										    $('#rowtable').append('<tr><td>'+v.name+'</td><td>'+v.database+'</td><td><span class="btn btn-sm btn-primary" onclick=\'setdb("'+v.id+'")\'>Select Database</span></td></tr>');
										});
								     });


		}

		table();

		$(document).ready(function() {
		$('#formdb').on('submit', function (e) {

							          e.preventDefault();
							          $('#adddatabase').modal('hide');

								      var datas= $("#formdb").serialize();

								      $.ajax({
								         type: "POST",
								         url: "?i=adddatabase",
								         dataType: "json",
								         data: datas,
								      }).done(function( data ) {
								      	toastr.info('Checking Connection');
								      	setTimeout(function() {
								      	if (data.status == "success") {
								      		toastr.success('Successfully to Connect');
								        	toastr.success('Successfully Add Database');
								        	$('#rowtable').append('<tr><td>'+$('#name').val()+'</td><td>'+$('#database').val()+'</td><td><span class="btn btn-sm btn-primary" onclick=\'setdb("'+$('input[name="id"]').val()+'")\'>Select Database</span></td></tr>');
								        	document.getElementById("formdb").reset();
								        	genid();
								    	} else {
								    		toastr.error('Failed to Connect');
								    	}
								    	},500)
								     });
								    });

		$('#editpass').on('submit', function (e) {

							          e.preventDefault();

								      var datas= $("#editpass").serialize();

								      $.ajax({
								         type: "POST",
								         url: "?i=changepass",
								         dataType: "json",
								         data: datas,
								      }).done(function( data ) {
								      	if (data.status == "success") {
								      		toastr.success('Successfully to Change Password');
								        	$('#config').modal('hide');
								    	} else if (data.status == "error") {
								    		toastr.error('Current Password not Same');
								    	}else if (data.status == "error2") {
								    		toastr.error('New Password Same as Current Password');
								    	}
								     });
								    });

		$('#editconfig').on('submit', function (e) {

							          e.preventDefault();

								      var datas= $("#editconfig").serialize();

								      $.ajax({
								         type: "POST",
								         url: "?i=editconfig",
								         data: datas,
								      }).done(function( data ) {
								      		toastr.success('Successfully to Change Password');
								        	$('#config').modal('hide');
								     });
								    });

		});
	</script>
	<script type="text/javascript">
		// Example starter JavaScript for disabling form submissions if there are invalid fields
		(function() {
		  'use strict';
		  window.addEventListener('load', function() {
		    // Fetch all the forms we want to apply custom Bootstrap validation styles to
		    var forms = document.getElementsByClassName('needs-validation');
		    // Loop over them and prevent submission
		    var validation = Array.prototype.filter.call(forms, function(form) {
		      form.addEventListener('submit', function(event) {
		        if (form.checkValidity() === false) {
		          event.preventDefault();
		          event.stopPropagation();
		        }
		        form.classList.add('was-validated');
		      }, false);
		    });
		  }, false);
		})();
	</script>
</body>
</html>