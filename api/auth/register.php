<?php
require_once '../../config/database.php';
require_once '../../config/email_config.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->name) || empty($data->email) || empty($data->phone) || empty($data->password) || empty($data->role)) {
    sendError(400, 'All fields are required');
}

$database = new Database();
$db = $database->getConnection();

// Check if email exists
$checkQuery = "SELECT id FROM users WHERE email = :email";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(":email", $data->email);
$checkStmt->execute();

if ($checkStmt->rowCount() > 0) {
    sendError(409, 'Email already exists');
}

// Generate OTP
$otp = rand(100000, 999999);
$otp_expiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));
$password_hash = password_hash($data->password, PASSWORD_BCRYPT);

// Insert user
$query = "INSERT INTO users (name, email, phone, password_hash, role, otp, otp_expiry) 
          VALUES (:name, :email, :phone, :password_hash, :role, :otp, :otp_expiry)";
$stmt = $db->prepare($query);

$stmt->bindParam(":name", $data->name);
$stmt->bindParam(":email", $data->email);
$stmt->bindParam(":phone", $data->phone);
$stmt->bindParam(":password_hash", $password_hash);
$stmt->bindParam(":role", $data->role);
$stmt->bindParam(":otp", $otp);
$stmt->bindParam(":otp_expiry", $otp_expiry);

if ($stmt->execute()) {
    if (sendOTPEmail($data->email, $data->name, $otp)) {
        sendResponse(201, 'Registration successful. OTP sent to email.', ['email' => $data->email]);
    } else {
        sendError(500, 'Failed to send OTP email');
    }
} else {
    sendError(500, 'Registration failed');
}
?>
