<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $relationship = $conn->real_escape_string($_POST['relationship']);
    $occupation = $conn->real_escape_string($_POST['occupation']);
    $organization = $conn->real_escape_string($_POST['organization']);
    $mobile_number = $conn->real_escape_string($_POST['mobile_number']);

    $sql = "INSERT INTO family_details (name, relationship, occupation, organization, mobile_number)
            VALUES ('$name', '$relationship', '$occupation', '$organization', '$mobile_number')";

    if ($conn->query($sql) === TRUE) {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: 'Family details saved successfully!',
                    showConfirmButton: false,
                    timer: 1500
                }).then(function() {
                    window.location.href = 'index.php#family-main-tab'; // Redirect back to the family tab
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