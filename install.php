<?php
if (@$_GET['i'] == "install" && isset($_POST['username'])) {
    $file_config = fopen('config.php', 'w+');
    $port = ($_POST['port'] === "3306" ? "" : ":$_POST[port]");
    $isi = '<?php
    $config = array(
        "fullname" => "'.$_POST['fullname'].'",
        "username" => "'.$_POST['username'].'",
        "password" => "'.md5($_POST['password']).'",
        "server" => "'.$_POST['server'].'",
        "usernameserver" => "'.$_POST['usernameserver'].'",
        "passwordserver" => "'.$_POST['passwordserver'].'",
        "port" => "'.$port.'",
    );

    ?>
    ';

    fwrite($file_config, $isi);
    fclose($file_config);
    // header("location: login.php?i=installed");
    die;
} 
?>
<!DOCTYPE html>
<html>
<head>
	<title>Install</title>
	<link rel="stylesheet" type="text/css" href="assets/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="assets/css/mdb.min.css">
	<link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
<style>

        .intro-2 {
        	background-color: blue;
            background-size: cover;
        }
        .top-nav-collapse {
            background-color: #3f51b5 !important;
        }
        .navbar:not(.top-nav-collapse) {
            background: transparent !important;
        }
        @media (max-width: 768px) {
            .navbar:not(.top-nav-collapse) {
                background: #3f51b5 !important;
            }
        }

        .card {
            background-color: rgba(229, 228, 255, 0.2);
        }
        .md-form label {
            color: #ffffff;
        }
        h6 {
            line-height: 1.7;
        }
        
        html,
        body,
        header,
        .view {
          height: 100%;
        }

        @media (min-width: 560px) and (max-width: 740px) {
          html,
          body,
          header,
          .view {
            height: 650px;
          }
        }

        @media (min-width: 800px) and (max-width: 850px) {
          html,
          body,
          header,
          .view  {
            height: 650px;
          }
        }

        .card {
            margin-top: 30px;
            /*margin-bottom: -45px;*/

        }

        .md-form input[type=text]:focus:not([readonly]),
        .md-form input[type=password]:focus:not([readonly]) {
            border-bottom: 1px solid #8EDEF8;
            box-shadow: 0 1px 0 0 #8EDEF8;
        }
        .md-form input[type=text]:focus:not([readonly])+label,
        .md-form input[type=password]:focus:not([readonly])+label {
            color: #8EDEF8;
        }

        .md-form .form-control {
            color: #fff;
        }

        .navbar.navbar-dark form .md-form input:focus:not([readonly]) {
            border-color: #8EDEF8;
        }

        @media (min-width: 800px) and (max-width: 850px) {
            .navbar:not(.top-nav-collapse) {
                background: #3f51b5!important;
            }
        }

    </style>
</head>
<body>
    <header>
        <section class="view intro-2">
          <div class="mask rgba-stylish-strong h-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-xl-5 col-lg-6 col-md-10 col-sm-12 mx-auto mt-5">
                        <div class="card wow fadeIn" data-wow-delay="0.3s">
                            <div class="card-body">
                                <div class="form-header primary-color">
                                    <h3>Install</h3>
                                </div>
								<form method="post" class="needs-validation" style="color: #757575;" id="forminput" novalidate>
                                <b class="white-text">User Configuration</b>
								<div class="md-form">
                                    <i class="fa fa-list prefix white-text"></i>
                                    <input type="text" name="fullname" class="form-control" autofocus required>
                                    <label for="orangeForm-email">Full Name</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-user prefix white-text"></i>
                                    <input type="text" name="username" class="form-control" required>
                                    <label for="orangeForm-email">Username</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-lock prefix white-text"></i>
                                    <input type="password" name="password" class="form-control" required>
                                    <label for="orangeForm-pass">Password</label>
                                </div>

                                <b class="white-text">Server Configuration</b>
                                <div class="md-form">
                                    <i class="fa fa-server prefix white-text"></i>
                                    <input type="text" name="server" class="form-control" placeholder="localhost" required>
                                    <label for="orangeForm-email">Server</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-user prefix white-text"></i>
                                    <input type="text" name="usernameserver" class="form-control" placeholder="root" required>
                                    <label for="orangeForm-email">Username</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-lock prefix white-text"></i>
                                    <input type="password" name="passwordserver" class="form-control">
                                    <label for="orangeForm-pass">Password</label>
                                </div>

                                <div class="md-form">
                                    <i class="fa fa-lock prefix white-text"></i>
                                    <input type="number" name="port" class="form-control" placeholder="3306" required>
                                    <label for="orangeForm-email">Port</label>
                                    <small class="white-text">Default Port 3306</small>
                                </div>

                                <div class="text-center">
                                    <button class="btn primary-color btn-lg" name="submit" type="submit">Install</button>
                                </div>
                            </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
          </div>
        </section>

    </header>

	<script type="text/javascript" src="assets/js/jquery-2.1.0.js"></script>
	<script type="text/javascript" src="assets/js/compiled.min.js"></script>
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

	$(document).ready(function(){

	  $('#forminput').on('submit', function (e) {

					          e.preventDefault();

						      var datas= $("#forminput").serialize();

						      $.ajax({
						         type: "POST",
						         url: "?i=install",
						         data: datas
						      }).done(function( data ) {
						        toastr.success('Successfully Installation');
						        window.location = 'login.php';
						     });
						    });
	  });
	</script>
</body>
</html>