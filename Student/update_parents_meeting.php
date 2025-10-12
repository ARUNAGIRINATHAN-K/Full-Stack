<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $meeting_date = $_POST['meeting_date'];
    $purpose_of_meeting = $_POST['purpose_of_meeting'];
    $points_discussed = $_POST['points_discussed'];
    $action = $_POST['action'];
    $status = $_POST['status'];

    $sql = "UPDATE parents_meeting SET 
            meeting_date=?, 
            purpose_of_meeting=?, 
            points_discussed=?, 
            action=?, 
            status=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $meeting_date, $purpose_of_meeting, $points_discussed, $action, $status, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>