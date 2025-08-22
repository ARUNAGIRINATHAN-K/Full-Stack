<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $relationship = $_POST['relationship'];
    $occupation = $_POST['occupation'];
    $organization = $_POST['organization'];
    $mobile_number = $_POST['mobile_number'];

    $sql = "UPDATE family_details SET 
            name=?, 
            relationship=?, 
            occupation=?, 
            organization=?, 
            mobile_number=? 
            WHERE id=?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $name, $relationship, $occupation, $organization, $mobile_number, $id);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>