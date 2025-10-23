<?php
require_once '../../config/database.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->otp)) {
    sendError(400, 'Email and OTP are required');
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, otp, otp_expiry FROM users WHERE email = :email AND is_verified = 0";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $data->email);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    sendError(404, 'User not found or already verified');
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['otp'] != $data->otp) {
    sendError(400, 'Invalid OTP');
}

if (strtotime($user['otp_expiry']) < time()) {
    sendError(400, 'OTP expired');
}

// Verify user
$updateQuery = "UPDATE users SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE id = :id";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bindParam(":id", $user['id']);

if ($updateStmt->execute()) {
    sendResponse(200, 'Email verified successfully');
} else {
    sendError(500, 'Verification failed');
}
?>
