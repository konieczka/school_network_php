<?php
session_start();
$db_connection = pg_connect("host=localhost dbname=school_network user=postgres");

$result = pg_query($db_connection, 'SELECT * FROM marks');
$allMarks = pg_fetch_all($result);
$messagePrompt = isset($_GET['success']) ? $_GET['success'] : 3;

if (!isset($_SESSION['username'])) {
    header('location:index.php');
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

    <title>School Network - Students</title>

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
            <li class="nav-item">
                <a class="nav-link" href="subjects.php">
                    <i class="fas fa-fw fa-book"></i>
                    <span>Subjects</span></a>
            </li>
            <li class="nav-item active">
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
                    <li class="breadcrumb-item active">Students</li>
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
                                This mark already exists in the database.
                            </div>';
                } else if ($messagePrompt == 1) {
                    echo
                        '<div class="alert alert-success" role="alert">
                            Mark added to the database.
                        </div>';
                } else if ($messagePrompt == 2) {
                    echo '<div class="alert alert-info" role="alert">
                            Mark data edited successfully.
                        </div>';
                } else if ($messagePrompt == 4) {
                    echo '<div class="alert alert-info" role="alert">
                            Mark deleted from the database.
                         </div>';
                };

                unset($_GET['success']);
                ?>
                <div style="height:70vh; margin-bottom:25px; overflow-y:scroll">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Student</th>
                                <th scope="col">Grade</th>
                                <th scope="col">Subject</th>
                                <th scope="col">Rated by</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <?php
                        for ($i = 0; $i < count($allMarks); $i++) {
                            $mark = $allMarks[$i];
                            $teacherId = $mark['rating_teacher'];
                            $studentId = $mark['appointed_student'];
                            $subjectId = $mark['alleged_subject'];

                            $teacherQuery = pg_query($db_connection, "SELECT teacher_id, first_name, last_name FROM teachers WHERE teacher_id=$teacherId");
                            $rating_teacher = pg_fetch_all($teacherQuery)[0];

                            $studentQuery = pg_query($db_connection, "SELECT student_id, first_name, last_name FROM students WHERE student_id=$studentId");
                            $appointed_student = pg_fetch_all($studentQuery)[0];

                            $subjectQuery = pg_query($db_connection, "SELECT subject_id, subject_name, subject_name, is_advanced FROM subjects WHERE subject_id=$subjectId");
                            $alleged_subject = pg_fetch_all($subjectQuery)[0];


                            echo "<tr><td>$i</td><td>" . $appointed_student['first_name'] . " " .  $appointed_student['last_name'] .
                                "</td><td>" . $mark['mark_value'] .
                                "</td><td>" . ($alleged_subject['is_advanced'] == 't' ? "Advanced" : "Basic") . " " .  $alleged_subject['subject_name'] .
                                "</td><td>" . $rating_teacher['first_name'] . " " .  $rating_teacher['last_name'] .
                                "</td>";
                            echo '<td><button class="buttonEdit"
                            type="button" 
                            data-toggle="modal" 
                            data-target="#editMarkModal"
                            onclick="getMarkData(\'' .
                                $mark['mark_id'] .
                                '\',\'' .
                                $mark['mark_value'] .
                                '\',\'' .
                                $appointed_student['student_id'] .
                                '\',\'' .
                                $rating_teacher['teacher_id'] .
                                '\',\'' .
                                $alleged_subject['subject_id'] . '\')">✎</button>
                            &nbsp;
                            <button class="buttonDelete" type="button" data-toggle="modal" data-target="#deleteModal"
                            onclick="getMarkId(\'' .
                                $mark['mark_id'] .
                                '\')">⛒</button>
                        </td></tr>';
                        }
                        ?>
                    </table>
                </div>
                <!-- Button triggering new student form modal -->
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#newStudentModal">
                    New mark
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
                                <form action="deletemark.php" method="post">
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

                <!-- Modal containing new mark form -->
                <div class="modal fade" id="newStudentModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <form action="newmark.php" method="post">
                            <div class="modal-content" style="width: 50vw">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalCenterTitle">Add new mark</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="new-grade">Grade</label>
                                        <select class="form-control" name="new-grade" id="new-grade">
                                            <option value='1'>1</option>
                                            <option value='2'>2</option>
                                            <option value='3'>3</option>
                                            <option value='4'>4</option>
                                            <option value='5'>5</option>
                                        </select>
                                    </div>
                                    <div class="form-group row">
                                        <label for="student-select" class="col-sm-2 col-form-label">Student</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="new-student-select" name="new-student-select">
                                                <?php
                                                $studentsResource = pg_query($db_connection, "SELECT * FROM students");
                                                $allStudents = pg_fetch_all($studentsResource);

                                                for ($i = 0; $i < count($allStudents); $i++) {
                                                    $student = $allStudents[$i];
                                                    echo '<option value="' . $student['student_id'] . '">' .
                                                        $student['first_name'] .
                                                        '&nbsp;' .
                                                        $student['last_name'] . ' </option>';
                                                };

                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="new-teacher-select" class="col-sm-2 col-form-label">Teacher</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="new-teacher-select" name="new-teacher-select">
                                                <?php
                                                $teachersResource = pg_query($db_connection, "SELECT * FROM teachers");
                                                $allTeachers = pg_fetch_all($teachersResource);

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
                                    </div>
                                    <div class="form-group row">
                                        <label for="new-subject-select" class="col-sm-2 col-form-label">Subjects</label>
                                        <div class="col-sm-10">
                                            <select class="form-control" id="new-subject-select" name="new-subject-select">
                                                <?php
                                                $subjectsResource = pg_query($db_connection, "SELECT * FROM subjects");
                                                $allSubjects = pg_fetch_all($subjectsResource);

                                                for ($i = 0; $i < count($allSubjects); $i++) {
                                                    $subject = $allSubjects[$i];
                                                    echo '<option value="' . $subject['subject_id'] . '">' .
                                                        ($subject['is_advanced'] == 't' ? "Advanced" : "Basic") .
                                                        '&nbsp;' .
                                                        $subject['subject_name'] . ' </option>';
                                                };

                                                ?>
                                            </select>
                                        </div>
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

        <!-- Modal containing edit mark form -->
        <div class="modal fade" id="editMarkModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <form action="editmark.php" method="post">
                    <div class="modal-content" style="width: 50vw">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle">Edit mark</h5>
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
                            <div class="form-group">
                                <label for="edit-grade">Grade</label>
                                <select class="form-control" name="grade_select" id="edit-grade">
                                    <option value=1>1</option>
                                    <option value=2>2</option>
                                    <option value=3>3</option>
                                    <option value=4>4</option>
                                    <option value=5>5</option>
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="edit-student" class="col-sm-2 col-form-label">Student</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="edit-student" name="student-select">
                                        <?php
                                        $studentsResource = pg_query($db_connection, "SELECT * FROM students");
                                        $allStudents = pg_fetch_all($studentsResource);

                                        for ($i = 0; $i < count($allStudents); $i++) {
                                            $student = $allStudents[$i];
                                            echo '<option value="' . $student['student_id'] . '">' .
                                                $student['first_name'] .
                                                '&nbsp;' .
                                                $student['last_name'] . ' </option>';
                                        };

                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="edit-teacher" class="col-sm-2 col-form-label">Teacher</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="edit-teacher" name="teacher-select">
                                        <?php
                                        $teachersResource = pg_query($db_connection, "SELECT * FROM teachers");
                                        $allTeachers = pg_fetch_all($teachersResource);

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
                            </div>
                            <div class="form-group row">
                                <label for="edit-subject" class="col-sm-2 col-form-label">Subjects</label>
                                <div class="col-sm-10">
                                    <select class="form-control" id="edit-subject" name="subject-select">
                                        <?php
                                        $subjectsResource = pg_query($db_connection, "SELECT * FROM subjects");
                                        $allSubjects = pg_fetch_all($subjectsResource);

                                        for ($i = 0; $i < count($allSubjects); $i++) {
                                            $subject = $allSubjects[$i];
                                            echo '<option value="' . $subject['subject_id'] . '">' .
                                                ($subject['is_advanced'] == 't' ? "Advanced" : "Basic") .
                                                '&nbsp;' .
                                                $subject['subject_name'] . ' </option>';
                                        };

                                        ?>
                                    </select>
                                </div>
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

    <script>
        function getMarkData(id, rating_value, student_id, teacher_id, subject_id) {
            console.log("CLICK! ", id, rating_value, student_id, teacher_id, subject_id);

            document.getElementById('edit-id').value = id;
            document.getElementById('edit-grade').value = rating_value;
            document.getElementById('edit-student').value = student_id;
            document.getElementById('edit-teacher').value = teacher_id;
            document.getElementById('edit-subject').value = subject_id;
        }

        function getMarkId(id) {
            document.getElementById('delete-id').value = id;

            console.log("CLICK! ", document.getElementById('delete-id').value);
        }
    </script>

</body>

</html>