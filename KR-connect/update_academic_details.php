<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $course = $_POST['course'];
    $institute_name = $_POST['institute_name'];
    $board_university = $_POST['board_university'];
    $year_of_passing = $_POST['year_of_passing'];
    $percentage_cgpa = $_POST['percentage_cgpa'];
    $certificate = $_POST['certificate'];

    $sql = "UPDATE academic_details SET 
            course=?, 
            institute_name=?, 
            board_university=?, 
            year_of_passing=?, 
            percentage_cgpa=?, 
            certificate=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissi", $course, $institute_name, $board_university, $year_of_passing, $percentage_cgpa, $certificate, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>