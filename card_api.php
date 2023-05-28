<?php
session_start();
include 'db.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');


if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
} else {
    $user_id = $_SESSION['user_id'];
}

$sql = "SELECT credit_card FROM users WHERE id='$user_id'";
$result = mysqli_query($conn, $sql);
$response = array();

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $response['credit_card'] = $row['credit_card'];
} else {
    $response['error'] = 'No credit card found for this user';
}

echo json_encode($response);
?>
