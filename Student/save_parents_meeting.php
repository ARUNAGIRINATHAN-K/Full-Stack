<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $meeting_date = $conn->real_escape_string($_POST['meeting_date']);
    $purpose_of_meeting = $conn->real_escape_string($_POST['purpose_of_meeting']);
    $points_discussed = $conn->real_escape_string($_POST['points_discussed']);
    $action = $conn->real_escape_string($_POST['action']);
    $status = $conn->real_escape_string($_POST['status']);

    $sql = "INSERT INTO parents_meeting (meeting_date, purpose_of_meeting, points_discussed, action, status)
            VALUES ('$meeting_date', '$purpose_of_meeting', '$points_discussed', '$action', '$status')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Parents meeting details saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'index.php#parents-main-tab'; // Redirect back to the parents tab
                });
              </script>";
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'Error: " . $sql . "<br>" . $conn->error . "',
                    showConfirmButton: true
                }).then(function() {
                    window.history.back();
                });
              </script>";
    }

    $conn->close();
} else {
    header("Location: index.php");
    exit();
}
?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>