<?php
// Include database connection
include('connection.php');

// Start the session
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('You need to log in first.'); window.location.href='login.php';</script>";
    exit;
}

try {
    // Query to fetch cars and their driver's password via car_id relationship
    $stmt = $conn->prepare("SELECT 
                                cars.car_id, 
                                cars.model, 
                                cars.plate_number, 
                                cars.driver, 
                                cars.contact_number, 
                                cars.date, 
                                users.plain_password,
                                users.username 
                            FROM cars 
                            LEFT JOIN users ON users.car_id = cars.car_id");

    $stmt->execute();
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Grab-Manolo</title>

    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Grab-NBSC <sup></sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="dashboard.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>



            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="true" aria-controls="collapseTwo">
                    <i class="fas fa-fw fa-cog"></i>
                    <span>Components</span>
                </a>
                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Components:</h6>
                        <a class="collapse-item" href="buttons.html">Buttons</a>
                        <a class="collapse-item" href="cards.html">Cards</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Utilities Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilities"
                    aria-expanded="true" aria-controls="collapseUtilities">
                    <i class="fas fa-fw fa-wrench"></i>
                    <span>Utilities</span>
                </a>
                <div id="collapseUtilities" class="collapse" aria-labelledby="headingUtilities"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Custom Utilities:</h6>
                        <a class="collapse-item" href="utilities-color.html">Colors</a>
                        <a class="collapse-item" href="utilities-border.html">Borders</a>
                        <a class="collapse-item" href="utilities-animation.html">Animations</a>
                        <a class="collapse-item" href="utilities-other.html">Other</a>
                    </div>
                </div>
            </li> -->



            <!-- Heading -->
            <!-- <div class="sidebar-heading">
                Addons
            </div> -->

            <!-- Nav Item - Pages Collapse Menu -->
            <!-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Pages</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Login Screens:</h6>
                        <a class="collapse-item" href="login.html">Login</a>
                        <a class="collapse-item" href="register.html">Register</a>
                        <a class="collapse-item" href="forgot-password.html">Forgot Password</a>
                        <div class="collapse-divider"></div>
                        <h6 class="collapse-header">Other Pages:</h6>
                        <a class="collapse-item" href="404.html">404 Page</a>
                        <a class="collapse-item" href="blank.html">Blank Page</a>
                    </div>
                </div>
            </li> -->

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="register-car.php">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Register Car</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <!-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Admin</span>
                                <img class="img-profile rounded-circle"
                                    src="assets/img/user-profile.png">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Content Column -->
                        <div class="col-lg-12 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Register New Car Profile</h6>
                                </div>
                                <div class="card-body">
                                    <form id="editForm" action="registerCarProcess.php" method="POST">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="model">Model</label>
                                                <input type="text" name="model" id="model" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="plate_number">Plate No.</label>
                                                <input type="text" name="plate_number" id="plate_number" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="driver">Driver</label>
                                                <input type="text" name="driver" id="driver" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="contact_number">Contact No.</label>
                                                <input type="text" name="contact_number" id="contact_number" class="form-control" required>
                                            </div>
                                            <!-- Hidden fields to track car_id and user_id -->
                                            <input type="hidden" name="car_id" id="car_id">
                                            <input type="hidden" value="<?php echo $_SESSION['user_id']; ?>" name="user_id" id="user_id">

                                            <div class="col-md-12 mt-3">
                                                <button class="btn btn-primary" type="submit">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12 mb-4">
                            <!-- Project Card Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Registered Cars</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table table-responsive">
                                        <!-- <table class="table table-bordered table-striped table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Model</th>
                                                    <th>Plate No.</th>
                                                    <th>Driver</th>
                                                    <th>Contact No.</th>
                                                    <th>Username</th>
                                                    <th>Password</th>
                                                    <th>Registered Date</th>
                                                    <th>Option</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Check if any cars were found for the user
                                                if ($stmt->rowCount() > 0) {
                                                    // Loop through the fetched data and populate the table rows
                                                    foreach ($cars as $car) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($car['model']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['plate_number']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['driver']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['contact_number']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['date']) . "</td>";
                                                        echo "<td><button class='btn btn-warning btn-sm editBtn' data-car-id='" . $car['car_id'] . "' data-model='" . htmlspecialchars($car['model']) . "' data-plate='" . htmlspecialchars($car['plate_number']) . "' data-driver='" . htmlspecialchars($car['driver']) . "' data-contact='" . htmlspecialchars($car['contact_number']) . "'>Edit</button></td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    // If no cars were found for the user
                                                    echo "<tr><td colspan='6' class='text-center'>No cars registered.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table> -->
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Model</th>
                                                    <th>Plate Number</th>
                                                    <th>Driver</th>
                                                    <th>Contact Number</th>
                                                    <th>Date</th>
                                                    <th>Username</th>
                                                    <th>Password</th> <!-- Add a new column for Password -->
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Check if any cars were found for the user
                                                if ($stmt->rowCount() > 0) {
                                                    // Loop through the fetched data and populate the table rows
                                                    foreach ($cars as $car) {
                                                        echo "<tr>";
                                                        echo "<td>" . htmlspecialchars($car['model']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['plate_number']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['driver']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['contact_number']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['date']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['username']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($car['plain_password']) . "</td>";
                                                        echo "<td>
            <button class='btn btn-warning btn-sm editBtn' 
                data-car-id='" . $car['car_id'] . "' 
                data-model='" . htmlspecialchars($car['model']) . "' 
                data-plate='" . htmlspecialchars($car['plate_number']) . "' 
                data-driver='" . htmlspecialchars($car['driver']) . "' 
                data-contact='" . htmlspecialchars($car['contact_number']) . "'>
                Edit
            </button>
          </td>";
                                                        echo "</tr>";
                                                    }
                                                } else {
                                                    // If no cars were found for the user
                                                    echo "<tr><td colspan='7' class='text-center'>No cars registered.</td></tr>";
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
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

    <!-- Page level plugins -->
    <script src="assets/vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="assets/js/demo/chart-area-demo.js"></script>
    <script src="assets/js/demo/chart-pie-demo.js"></script>
    <script>
        // JavaScript to handle Edit button click
        const editButtons = document.querySelectorAll('.editBtn');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const carId = this.getAttribute('data-car-id');
                const model = this.getAttribute('data-model');
                const plateNumber = this.getAttribute('data-plate');
                const driver = this.getAttribute('data-driver');
                const contactNumber = this.getAttribute('data-contact');

                // Populate the form with the selected car details
                document.getElementById('car_id').value = carId;
                document.getElementById('model').value = model;
                document.getElementById('plate_number').value = plateNumber;
                document.getElementById('driver').value = driver;
                document.getElementById('contact_number').value = contactNumber;
            });
        });
    </script>
</body>

</html>