<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $course = $conn->real_escape_string($_POST['course']);
    $institute_name = $conn->real_escape_string($_POST['institute_name']);
    $board_university = $conn->real_escape_string($_POST['board_university']);
    $year_of_passing = (int)$_POST['year_of_passing'];
    $percentage_cgpa = $conn->real_escape_string($_POST['percentage_cgpa']);
    $certificate = $conn->real_escape_string($_POST['certificate']);

    $sql = "INSERT INTO academic_details (course, institute_name, board_university, year_of_passing, percentage_cgpa, certificate)
            VALUES ('$course', '$institute_name', '$board_university', $year_of_passing, '$percentage_cgpa', '$certificate')";

    if ($conn->query($sql) === TRUE) {
        // Use SweetAlert for success message
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Academic details saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'index.php#academic-main-tab'; // Redirect back to the academic tab
                });
              </script>";
    } else {
        // Use SweetAlert for error message
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error: " . $sql . "<br>" . $conn->error . "',
                    showConfirmButton: true
                }).then(function() {
                    window.history.back(); // Go back to the previous page
                });
              </script>";
    }

    $conn->close();
} else {
    header("Location: index.php"); // Redirect if accessed directly
    exit();
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>