<?php
require_once '../../config/database.php';
require_once '../../config/email_config.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email)) {
    sendError(400, 'Email is required');
}

$database = new Database();
$db = $database->getConnection();

// Check if user exists and not verified
$query = "SELECT id, name, is_verified FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $data->email);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    sendError(404, 'User not found');
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['is_verified'] == 1) {
    sendError(400, 'Email already verified');
}

// Generate new OTP
$otp = rand(100000, 999999);
$otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// Update OTP
$updateQuery = "UPDATE users SET otp = :otp, otp_expiry = :otp_expiry WHERE id = :id";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bindParam(":otp", $otp);
$updateStmt->bindParam(":otp_expiry", $otp_expiry);
$updateStmt->bindParam(":id", $user['id']);

if ($updateStmt->execute()) {
    if (sendOTPEmail($data->email, $user['name'], $otp)) {
        sendResponse(200, 'OTP resent successfully', ['email' => $data->email]);
    } else {
        sendError(500, 'Failed to send OTP email');
    }
} else {
    sendError(500, 'Failed to generate new OTP');
}
?>
