<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$host = 'localhost';
$dbname = 'mic_database';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed: ' . $conn->connect_error]);
    exit();
}
$conn->set_charset('utf8');
$action = $_POST['action'] ?? $_GET['action'] ?? '';
$table = $_POST['table'] ?? $_GET['table'] ?? '';

switch ($action) {
    case 'create':
        createRecord($conn, $table);
        break;
    case 'read':
        readRecords($conn, $table);
        break;
    case 'update':
        updateRecord($conn, $table);
        break;
    case 'delete':
        deleteRecord($conn, $table);
        break;
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action specified']);
        break;
}

function createRecord($conn, $table) {
    if ($table == 'academic_details') {
        $course = $_POST['course'] ?? '';
        $institute_name = $_POST['institute_name'] ?? '';
        $board_university = $_POST['board_university'] ?? '';
        $year_of_passing = $_POST['year_of_passing'] ?? '';
        $percentage_cgpa = $_POST['percentage_cgpa'] ?? '';
        $certificate = $_POST['certificate'] ?? '';
        $sql = "INSERT INTO academic_details (course, institute_name, board_university, year_of_passing, percentage_cgpa, certificate) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssiss", $course, $institute_name, $board_university, $year_of_passing, $percentage_cgpa, $certificate);
    } elseif ($table == 'family_details') {
        $name = $_POST['name'] ?? '';
        $relationship = $_POST['relationship'] ?? '';
        $occupation = $_POST['occupation'] ?? '';
        $organization = $_POST['organization'] ?? '';
        $mobile_number = $_POST['mobile_number'] ?? '';
        $sql = "INSERT INTO family_details (name, relationship, occupation, organization, mobile_number) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $relationship, $occupation, $organization, $mobile_number);
    } elseif ($table == 'parents_meeting') {
        $meeting_date = $_POST['meeting_date'] ?? '';
        $purpose_of_meeting = $_POST['purpose_of_meeting'] ?? '';
        $points_discussed = $_POST['points_discussed'] ?? '';
        $action = $_POST['action'] ?? '';
        $status = $_POST['status'] ?? '';
        $sql = "INSERT INTO parents_meeting (meeting_date, purpose_of_meeting, points_discussed, action, status) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $meeting_date, $purpose_of_meeting, $points_discussed, $action, $status);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid table']);
        return;
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record created successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to create record']);
    }
    $stmt->close();
}

function readRecords($conn, $table) {
    if (!in_array($table, ['academic_details', 'family_details', 'parents_meeting'])) {
        error_log('Invalid table requested: ' . $table);
        echo json_encode(['success' => false, 'message' => 'Invalid table']);
        return;
    }
    $sql = "SELECT * FROM $table ORDER BY id DESC";
    error_log('SQL Query: ' . $sql);
    $result = $conn->query($sql);
    $records = [];
    if ($result && $result->num_rows > 0) {
        error_log('Records found: ' . $result->num_rows);
        while ($row = $result->fetch_assoc()) {
            $records[] = $row;
        }
    } else {
        error_log('No records found for table: ' . $table);
    }
    echo json_encode(['success' => true, 'data' => $records]);
}

function updateRecord($conn, $table) {
    $id = $_POST['id'] ?? '';
    if ($table == 'academic_details') {
        $course = $_POST['course'] ?? '';
        $institute_name = $_POST['institute_name'] ?? '';
        $board_university = $_POST['board_university'] ?? '';
        $year_of_passing = $_POST['year_of_passing'] ?? '';
        $percentage_cgpa = $_POST['percentage_cgpa'] ?? '';
        $certificate = $_POST['certificate'] ?? '';
        $sql = "UPDATE academic_details SET course=?, institute_name=?, board_university=?, year_of_passing=?, percentage_cgpa=?, certificate=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssissi", $course, $institute_name, $board_university, $year_of_passing, $percentage_cgpa, $certificate, $id);
    } elseif ($table == 'family_details') {
        $name = $_POST['name'] ?? '';
        $relationship = $_POST['relationship'] ?? '';
        $occupation = $_POST['occupation'] ?? '';
        $organization = $_POST['organization'] ?? '';
        $mobile_number = $_POST['mobile_number'] ?? '';
        $sql = "UPDATE family_details SET name=?, relationship=?, occupation=?, organization=?, mobile_number=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $relationship, $occupation, $organization, $mobile_number, $id);
    } elseif ($table == 'parents_meeting') {
        $meeting_date = $_POST['meeting_date'] ?? '';
        $purpose_of_meeting = $_POST['purpose_of_meeting'] ?? '';
        $points_discussed = $_POST['points_discussed'] ?? '';
        $action = $_POST['action'] ?? '';
        $status = $_POST['status'] ?? '';
        $sql = "UPDATE parents_meeting SET meeting_date=?, purpose_of_meeting=?, points_discussed=?, action=?, status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $meeting_date, $purpose_of_meeting, $points_discussed, $action, $status, $id);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid table']);
        return;
    }
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record updated successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to update record']);
    }
    $stmt->close();
}

function deleteRecord($conn, $table) {
    $id = $_POST['id'] ?? '';
    if (!in_array($table, ['academic_details', 'family_details', 'parents_meeting'])) {
        echo json_encode(['success' => false, 'message' => 'Invalid table']);
        return;
    }
    $sql = "DELETE FROM $table WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Record deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to delete record']);
    }
    $stmt->close();
}

$conn->close();
?>
