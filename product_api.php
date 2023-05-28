<?php
include 'db.php';

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$req_method = $_SERVER['REQUEST_METHOD'];

if ($req_method == 'POST') {
    $post_data = file_get_contents("php://input");
    $data = json_decode($post_data);

    $user_id = $data->user_id;
    $credit_card = $data->credit_card;

    $sql = "UPDATE users SET credit_card='$credit_card' WHERE id='$user_id'";
    mysqli_query($conn, $sql);

    $response['success'] = 'Credit card updated successfully';

    echo json_encode($response);
} else {
    $response['error'] = 'Invalid request method';
    echo json_encode($response);
}
