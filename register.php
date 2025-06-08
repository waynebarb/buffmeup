<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Register</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <form class="user" method="post" action="registerProcess.php">
                                <!-- User Type -->
                                <div class="form-group">
                                    <select name="userType" class="form-control" required>
                                        <option value="user">Passenger</option>
                                        <option value="driver">Driver</option>
                                    </select>
                                </div>

                                <!-- Name fields -->
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="firstName" class="form-control" placeholder="First Name" required>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="lastName" class="form-control" placeholder="Last Name" required>
                                    </div>
                                </div>

                                <!-- Contact -->
                                <div class="form-group">
                                    <input type="text" name="contact_number" class="form-control" placeholder="Contact Number" required>
                                </div>

                                <!-- Username -->
                                <div class="form-group">
                                    <input type="text" name="userName" class="form-control" placeholder="Username" required>
                                </div>

                                <!-- Password / Repeat -->
                                <div class="form-row">
                                    <div class="form-group col-md-6 position-relative">
                                        <div class="input-group">
                                            <input type="password" name="password" id="password" class="form-control" placeholder="Password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-white" role="button" onclick="togglePassword('password', this)">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6 position-relative">
                                        <div class="input-group">
                                            <input type="password" id="repeatPassword" class="form-control" placeholder="Repeat Password" required>
                                            <div class="input-group-append">
                                                <span class="input-group-text bg-white" role="button" onclick="togglePassword('repeatPassword', this)">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Driver-specific fields (hidden by default) -->
                                <div class="form-row driver-fields">
                                    <div class="form-group col-md-6">
                                        <input type="text" name="model" id="model" class="form-control" placeholder="Car Model">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <input type="text" name="plate_number" id="plate_number" class="form-control" placeholder="Plate Number">
                                    </div>
                                  
                                </div>

                                <input type="hidden" name="car_id" id="car_id">
                                <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary btn-user btn-block">Register Account</button>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="login.php">Already have an account?</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>


    <script>
        $(document).ready(function() {
            // hide driver fields by default
            $('.driver-fields').hide();

            $('select[name="userType"]').change(function() {
                if ($(this).val() === 'driver') {
                    $('.driver-fields').slideDown(); // show with animation
                    // add required
                    $('.driver-fields').find('input').attr('required', true);
                } else {
                    $('.driver-fields').slideUp();
                    // remove required when hidden
                    $('.driver-fields').find('input').removeAttr('required');
                }
            });
        });

        function togglePassword(inputId, el) {
            const input = document.getElementById(inputId);
            const icon = el.querySelector('i');

            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        document.querySelector('.user').addEventListener('submit', function(e) {
            const pwd = document.getElementById('exampleInputPassword').value;
            const repeat = document.getElementById('exampleRepeatPassword').value;

            if (pwd !== repeat) {
                e.preventDefault(); // Prevent form submission
                alert("Passwords do not match!"); // Show alert
            }
        });
    </script>

</body>

</html>