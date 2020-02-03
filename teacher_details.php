<?php
session_start();
$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");


$classId = isset($_GET['class']) ? $_GET['class'] : 0;

if ($classId > 0) {
    $result = pg_query($db_connection, "SELECT class_id, class_symbol, grade, teachers.first_name, teachers.last_name
                                    FROM classes
                                    INNER JOIN teachers ON lead_teacher = teachers.teacher_id
                                    WHERE class_id = $classId");
    $classData = pg_fetch_all($result);
    $classDetails = $classData[0];

    $studentsResource = pg_query($db_connection, "SELECT * FROM students WHERE class_alegiance = $classId");
    $students = pg_fetch_all($studentsResource);
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

    <title>School Network - <?php echo $classDetails['grade'] . $classDetails['class_symbol'] ?> - Class Details</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/students.css">
    <link rel="stylesheet" type="text/css" href="/css/classes.css">

</head>

<body id="page-top">

    <nav class="navbar navbar-expand navbar-dark bg-primary static-top">

        <a class="navbar-brand mr-1" href="home.php">Welcome <?php echo $_SESSION['username']; ?></a>

        <button class="btn btn-link btn-sm text-white order-1 order-sm-0" id="sidebarToggle" href="#">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Navbar Search -->
        <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>

        <!-- Navbar -->
        <ul class="navbar-nav ml-auto ml-md-0">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-user-circle fa-fw"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="#">Settings</a>
                    <a class="dropdown-item" href="#">Activity Log</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">Logout</a>
                </div>
            </li>
        </ul>

    </nav>

    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="sidebar navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="teachers.php">
                    <i class="fas fa-fw fa-address-card"></i>
                    <span>Teachers</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="students.php">
                    <i class="fas fa-fw fa-user"></i>
                    <span>Students</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="classes.php">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Classes</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="subjects.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Subjects</span></a>
            </li>
        </ul>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="home.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="classes.php">Classes</a>
                    </li>
                    <li class="breadcrumb-item active">Class <?php echo $classDetails['grade'] . $classDetails['class_symbol'] ?></li>
                </ol>

                <!-- Sticky Footer -->
                <footer class="sticky-footer">
                    <div class="container my-auto">
                        <div class="copyright text-center my-auto">
                            <span>Copyright © Michał Konieczka 2019</span>
                        </div>
                    </div>
                </footer>

            </div>
            <!-- /.content-wrapper -->
            <div style="margin-left:25px; margin-right:25px">
                <div style="height:70vh; margin-bottom:25px; overflow-y:scroll">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">First name</th>
                                <th scope="col">Last name</th>
                                <th scope="col">Date of birth</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < count($students); $i++) {
                            $student = $students[$i];
                            echo "<tr><td>$i</td><td>" . $student['first_name'] .
                                "</td><td>" . $student['last_name'] .
                                "</td><td>" . $student['date_of_birth']  .
                                "</td>";
                            echo '<td><button class="buttonEdit"
                        type="button" 
                        data-toggle="modal" 
                        data-target="#editStudentModal"
                        onclick="getStudent(\'' .
                                $student['student_id'] .
                                '\',\'' .
                                $student['first_name'] .
                                '\',\'' .
                                $student['last_name'] .
                                '\',\'' .
                                $student['date_of_birth'] . '\')">✎</button>
                        &nbsp;
                        <button class="buttonDelete" type="button" data-toggle="modal" data-target="#deleteModal"
                        onclick="getStudentId(\'' .
                                $student['student_id'] .
                                '\')">⛒</button>
                        </td></tr>';
                        }
                        ?>
                    </table>
                </div>

                <!-- Button triggering new student form modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newStudentModal">
                    New student
                </button>
                <!-- /#wrapper -->

                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>

                <!-- Logout Modal-->
                <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <a class="btn btn-primary" href="index.php?logout=1">Logout</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Confirm delete Modal-->
                <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteLabel">Are you sure?</h5>
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                <form action="deletestudent.php" method="post">
                                    <div class="form-group row" style="display: none">
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" id="delete-id" name="delete-id" required>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Confirm</a>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal containing new student form -->
                <div class="modal fade" id="newStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form action="newstudent.php" method="post">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Add new student</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="first-name" class="col-sm-2 col-form-label">First name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="first-name" name="first-name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="last-name" class="col-sm-2 col-form-label">Last name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="last-name" name="last-name" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="birth-date" class="col-sm-2 col-form-label">Date of birth</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="birth-date" name="birth-date" required>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal containing edit student form -->
            <div class="modal fade" id="editStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <form action="editstudent.php" method="post">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalCenterTitle">Edit student</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group row" style="display: none">
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="edit-id" name="edit-id" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit-first-name" class="col-sm-2 col-form-label">First name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="edit-first-name" name="edit-first-name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit-last-name" class="col-sm-2 col-form-label">Last name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="edit-last-name" name="edit-last-name" required>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="edit-birth-date" class="col-sm-2 col-form-label">Date of birth</label>
                                    <div class="col-sm-10">
                                        <input type="date" class="form-control" id="edit-birth-date" name="edit-birth-date" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>



        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugin JavaScript-->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="js/demo/datatables-demo.js"></script>
        <script src="js/demo/chart-area-demo.js"></script>


</body>

</html>