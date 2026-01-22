<?php
include 'asset/db.php';
header('Content-Type: application/json');

if (isset($_POST['agent_code'])) {
    $code = $_POST['agent_code'];

    $sql = "SELECT discount FROM agents WHERE agent_code = '$code'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo json_encode(['status' => 'success', 'discount' => $row['discount']]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid Code']);
    }
}
$conn->close();
?>