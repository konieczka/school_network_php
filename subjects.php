<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('location:index.php');
}
$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$resultSubjects = pg_query($db_connection, 'SELECT * FROM subjects');
$resultTeachers = pg_query($db_connection, 'SELECT * FROM teachers');

$allSubjects = pg_fetch_all($resultSubjects);
$allTeachers = pg_fetch_all($resultTeachers);
$messagePrompt = isset($_GET['success']) ? $_GET['success'] : 3;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>School Network - Subjects</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="/css/students.css">

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
            <li class="nav-item active">
                <a class="nav-link" href="subjects.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Subjects</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="marks.php">
                    <i class="fas fa-fw fa-check-square"></i>
                    <span>Marks</span></a>
            </li>
        </ul>

        <div id="content-wrapper">

            <div class="container-fluid">

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="home.php">Dashboard</a>
                    </li>
                    <li class="breadcrumb-item active">Subjects</li>
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

                <?php
                if ($messagePrompt == 0) {
                    echo '<div class="alert alert-danger" role="alert">
                                This subject already exists in the database.
                            </div>';
                } else if ($messagePrompt == 1) {
                    echo
                        '<div class="alert alert-success" role="alert">
                            Subject added to the database.
                        </div>';
                } else if ($messagePrompt == 2) {
                    echo '<div class="alert alert-info" role="alert">
                            Subject data edited successfully.
                        </div>';
                } else if ($messagePrompt == 4) {
                    echo '<div class="alert alert-info" role="alert">
                            Subject deleted from the database.
                         </div>';
                };

                unset($_GET['success']);
                ?>

                <div style="height:70vh; margin-bottom:25px; overflow-y:scroll">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Subject name</th>
                                <th scope="col">Advanced course</th>
                                <th scope="col">Teachers</th>
                                <!-- <th scope="col"></th> -->
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < count($allSubjects); $i++) {
                            $subject = $allSubjects[$i];
                            $subject_id = $subject['subject_id'];
                            echo "<tr><td>$i</td><td>" . $subject['subject_name'] .
                                "</td><td>" . ($subject['is_advanced'] == 't' ? "✓" : "✗") .
                                "</td>";

                            $teachersToSubject = pg_query(
                                $db_connection,
                                "SELECT teachers.teacher_id, teachers.first_name, teachers.last_name
                        FROM teachers_subjects_relation 
                        INNER JOIN teachers ON teachers_subjects_relation.teacher_id = teachers.teacher_id
                        WHERE subject_id=$subject_id"
                            );

                            $teachers = pg_fetch_all($teachersToSubject);

                            if (pg_num_rows($teachersToSubject) > 0) {
                                echo '<td><table class="table table-striped">';
                                for ($j = 0; $j < count($teachers); $j++) {
                                    $teacher = $teachers[$j];
                                    echo "<tr><td>$j</td><td>" . $teacher['first_name'] .
                                        "</td><td>" . $teacher['last_name'] .
                                        "</td></tr>";
                                };
                                echo '</table>';
                            } else {
                                echo "<td></td>";
                            }
                        }
                        ?>
                    </table>
                </div>

                <!-- Scroll to Top Button-->
                <a class="scroll-to-top rounded" href="#page-top">
                    <i class="fas fa-angle-up"></i>
                </a>

                <!-- Button triggering new subject form modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newClassModal">
                    New class
                </button>

                <!-- Modal containing new subject form -->
                <div class="modal fade" id="newClassModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form action="newsubject.php" method="post">
                            <div class="modal-content" style="width: 50vw">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Add new subject</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group row">
                                        <label for="subject_name" class="col-sm-2 col-form-label">Subject name</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="subject_name" name="subject_name" required>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_advanced" name="is_advanced">
                                        <label class="form-check-label" for="is_advanced">
                                            Advanced course
                                        </label>
                                    </div>
                                    <div class="form-group">
                                        <label for="teachers_select">Teachers</label>
                                        <select multiple class="form-control" id="teachers_select" name="teachers_select[]">
                                            <?php
                                            for ($i = 0; $i < count($allTeachers); $i++) {
                                                $teacher = $allTeachers[$i];
                                                echo '<option value="' . $teacher['teacher_id'] . '">' .
                                                    $teacher['first_name'] .
                                                    '&nbsp;' .
                                                    $teacher['last_name'] . ' </option>';
                                            };
                                            ?>
                                        </select>
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