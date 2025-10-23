<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->email) || empty($data->password)) {
    sendError(400, 'Email and password are required');
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT id, name, email, phone, password_hash, role, avatar_url, is_verified 
          FROM users WHERE email = :email";
$stmt = $db->prepare($query);
$stmt->bindParam(":email", $data->email);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    sendError(401, 'Invalid credentials');
}

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['is_verified'] == 0) {
    sendError(403, 'Email not verified');
}

if (!password_verify($data->password, $user['password_hash'])) {
    sendError(401, 'Invalid credentials');
}

// Generate JWT token
$token = JWTHelper::generateToken($user['id'], $user['role']);

// Store session
$sessionQuery = "INSERT INTO sessions (user_id, token, expires_at) 
                 VALUES (:user_id, :token, :expires_at)";
$sessionStmt = $db->prepare($sessionQuery);
$expires_at = date('Y-m-d H:i:s', time() + (30 * 24 * 60 * 60));
$sessionStmt->bindParam(":user_id", $user['id']);
$sessionStmt->bindParam(":token", $token);
$sessionStmt->bindParam(":expires_at", $expires_at);
$sessionStmt->execute();

unset($user['password_hash']);

sendResponse(200, 'Login successful', [
    'token' => $token,
    'user' => $user
]);
?>
