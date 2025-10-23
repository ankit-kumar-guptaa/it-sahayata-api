<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

function sendResponse($status, $message, $data = null) {
    http_response_code($status);
    echo json_encode([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
    exit();
}

function sendError($status, $message) {
    http_response_code($status);
    echo json_encode([
        'status' => $status,
        'error' => $message
    ]);
    exit();
}
?>
