<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError(405, 'Method not allowed');
}

$token = JWTHelper::getBearerToken();
if (!$token) sendError(401, 'No token provided');

$tokenData = JWTHelper::validateToken($token);
if (!$tokenData) sendError(401, 'Invalid token');

if ($tokenData['role'] !== 'admin') {
    sendError(403, 'Admin access only');
}

$database = new Database();
$db = $database->getConnection();

// Get filter from query params
$role = isset($_GET['role']) ? $_GET['role'] : null;

if ($role && in_array($role, ['customer', 'agent', 'admin'])) {
    $query = "SELECT id, name, email, phone, role, is_verified, created_at 
              FROM users WHERE role = :role ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":role", $role);
} else {
    $query = "SELECT id, name, email, phone, role, is_verified, created_at 
              FROM users ORDER BY created_at DESC";
    $stmt = $db->prepare($query);
}

$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsQuery = "SELECT 
                COUNT(*) as total_users,
                SUM(CASE WHEN role = 'customer' THEN 1 ELSE 0 END) as customers,
                SUM(CASE WHEN role = 'agent' THEN 1 ELSE 0 END) as agents,
                SUM(CASE WHEN role = 'admin' THEN 1 ELSE 0 END) as admins,
                SUM(CASE WHEN is_verified = 1 THEN 1 ELSE 0 END) as verified_users
                FROM users";
$statsStmt = $db->prepare($statsQuery);
$statsStmt->execute();
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

sendResponse(200, 'Users retrieved successfully', [
    'statistics' => $stats,
    'users' => $users
]);
?>
