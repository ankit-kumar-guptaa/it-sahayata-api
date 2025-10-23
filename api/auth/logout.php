<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$token = JWTHelper::getBearerToken();

if (!$token) {
    sendError(401, 'No token provided');
}

$tokenData = JWTHelper::validateToken($token);

if (!$tokenData) {
    sendError(401, 'Invalid token');
}

$database = new Database();
$db = $database->getConnection();

// Delete token from sessions table
$query = "DELETE FROM sessions WHERE user_id = :user_id AND token = :token";
$stmt = $db->prepare($query);
$stmt->bindParam(":user_id", $tokenData['user_id']);
$stmt->bindParam(":token", $token);

if ($stmt->execute()) {
    sendResponse(200, 'Logged out successfully');
} else {
    sendError(500, 'Logout failed');
}
?>
