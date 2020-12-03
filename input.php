<?php
include 'config.php';
session_start();
if(!isset($_SESSION['username'])) {
    header('location:login.php');die;
}

if (@$_REQUEST['i'] == 'logout') {
    session_destroy();
    header('location:login.php');die;
}

if(!isset($_SESSION['iddb'])) {
    header('location:index.php');die;
}

if (@$_REQUEST['i'] == 'unsetdb') {
    unset($_SESSION['iddb']);
    header('location:index.php');die;
}

$data = json_decode(file_get_contents('db.json'),1);
$db = '';
foreach ($data as $value) {
    if ($value['id'] == $_SESSION['iddb']) {
        $db .= $value['database'];
    }
}
$conn = new mysqli($config['server'].$config['port'],$config['usernameserver'],$config['passwordserver'],$db);
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
                        <div class="float-right">
                                    <a href="?i=unsetdb" ><i class="fa fa-arrow-left"></i> Back to List Database</a>
                                </div><br><br>
                        <div class="card">
                          <div class="card-header h5 text-white bg-primary">
                                Query Builder
                                <div class="float-right">
                                    Database : <?=$db?>
                                </div>
                          </div>
                          <div class="card-body">
                              <!--Accordion wrapper-->
                                <div class="accordion md-accordion" id="accordionEx" role="tablist" aria-multiselectable="true">

                                  <!-- Accordion card -->
                                  <div class="card">

                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="headingOne1">
                                      <a data-toggle="collapse" data-parent="#accordionEx" href="#selecttable" aria-expanded="true"
                                        aria-controls="selecttable">
                                        <h5 class="mb-0">
                                          Select Table <i class="fas fa-angle-down rotate-icon"></i>
                                        </h5>
                                      </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="selecttable" class="collapse show" role="tabpanel" aria-labelledby="headingOne1"
                                      data-parent="#accordionEx">
                                      <div class="card-body">
                                        <form id="gettable">
                                          <div class="row">
                                            <div class="col-md-8">
                                                    <select class="browser-default custom-select" name="table" id="table">
                                                        <?php 
                                                            $sql = $conn->query("SHOW TABLES FROM $db");
                                                            while ($row = $sql->fetch_array(MYSQLI_BOTH)) {?>
                                                               <option><?=$row[0]?></option>
                                                            <?php }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                     <a class="collapsed btn btn-sm btn-primary" onclick="settable();" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2"
                                        aria-expanded="false" aria-controls="collapseTwo2">Select Table</a>
                                                </div>
                                            </div>
                                        </form>
                                            
                                      </div>
                                    </div>

                                  </div>
                                  <!-- Accordion card -->

                                  <!-- Accordion card -->
                                  <div class="card">

                                    <!-- Card header -->
                                    <div class="card-header" role="tab" id="headingTwo2">
                                      <a class="collapsed" data-toggle="collapse" data-parent="#accordionEx" href="#collapseTwo2"
                                        aria-expanded="false" aria-controls="collapseTwo2">
                                        <h5 class="mb-0">
                                          Filter <i class="fas fa-angle-down rotate-icon"></i>
                                        </h5>
                                      </a>
                                    </div>

                                    <!-- Card body -->
                                    <div id="collapseTwo2" class="collapse" role="tabpanel" aria-labelledby="headingTwo2"
                                      data-parent="#accordionEx">
                                      <div class="card-body" style="padding-bottom: 50px;">
                                        <button type="button" class="btn btn-sm btn-primary" id="addrule">Add Rule</button>
                                        <form id="filtertable">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <div class="md-form">
                                                        <select class="browser-default custom-select" name="op[]">
                                                            <option value="" selected>-</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="md-form">
                                                        <input type="text" name="field[]" class="form-control" required>
                                                        <label>Field</label>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="md-form">
                                                        <input type="text" name="value[]" class="form-control" required>
                                                        <label>Value</label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="newrule">
                                                
                                            </div>
                                            <div class="float-right">
                                                <a class="collapsed btn btn-sm btn-primary" onclick="filter();">Filter</a>
                                            </div>
                                        </form>
                                      </div>
                                    </div>

                                  </div>
                                  <!-- Accordion card -->

                                  

                                </div>
                                <!-- Accordion wrapper -->
                                <br><br>
                                <div id="showtable"></div>
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
        function settable() {

                                      $.ajax({
                                         type: "GET",
                                         url: "table.php?table="+$('#table').val(),
                                      }).done(function( data ) {
                                        toastr.success('Table `'+$('#table').val()+'` Selected');
                                        $('#showtable').html(data);
                                     });
        }

        function filter() {
                                    $.ajax({
                                         type: "GET",
                                         url: "table.php?table="+$('#table').val()+"&"+$("#filtertable").serialize(),
                                      }).done(function( data ) {
                                        toastr.success('Filtering..');
                                        $('#showtable').html(data);
                                     });
        }

        $(document).ready(function() {
            // $('.mdb-select').material_select();

            var max_fields      = 5; //maximum input boxes allowed
            var wrapper         = $("#newrule"); //Fields wrapper
            var add_button      = $("#addrule"); //Add button ID
            
            var x = 1; //initlal text box count
            $(add_button).click(function(e){ //on add input button click
                e.preventDefault();
                if(x < max_fields){ //max input box allowed
                    x++; //text box increment
                    $(wrapper).append('<div class="row"><div class="col-md-2"> <div class="md-form"> <select class="browser-default custom-select" name="op[]" id="op" required> <option value="" disabled selected>Option</option> <option>AND</option> <option>OR</option> </select> </div> </div> <div class="col-md-4"> <div class="md-form"> <input type="text" name="field[]" class="form-control" required> <label>Field</label> </div> </div> <div class="col-md-4"> <div class="md-form"> <input type="text" name="value[]" class="form-control" required> <label>Value</label> </div></div> <a href="#" id="remove_field" style="padding-top:20px;color:red;"><i class="fa fa-times"></i> Remove</a></div>');
                }
            });
            
            $(wrapper).on("click","#remove_field", function(e){ //user click on remove text
                e.preventDefault(); $(this).parent('div').remove(); x--;
            })


            $('#editpass').on('submit', function (e) {

                                      e.preventDefault();

                                      var datas= $("#editpass").serialize();

                                      $.ajax({
                                         type: "POST",
                                         url: "index.php?i=changepass",
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
                                         url: "index.php?i=editconfig",
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
