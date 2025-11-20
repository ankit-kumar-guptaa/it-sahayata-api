 
<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendError(405, 'Method not allowed');
}

$token = JWTHelper::getBearerToken();

if (!$token) {
    sendError(401, 'No token provided');
}

$tokenData = JWTHelper::validateToken($token);

if (!$tokenData) {
    sendError(401, 'Invalid or expired token');
}

$database = new Database();
$db = $database->getConnection();

if ($tokenData['role'] === 'customer') {
    // Customer sees only their tickets
    $query = "SELECT t.*, u.name as customer_name, u.phone as customer_phone, t.file_url
              FROM tickets t
              JOIN users u ON t.customer_id = u.id
              WHERE t.customer_id = :user_id
              ORDER BY t.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":user_id", $tokenData['user_id']);
    
} elseif ($tokenData['role'] === 'agent') {
    // Agent sees only assigned tickets
    $query = "SELECT t.*, u.name as customer_name, u.phone as customer_phone, t.file_url,
              a.assigned_at
              FROM tickets t
              JOIN users u ON t.customer_id = u.id
              JOIN assignments a ON t.id = a.ticket_id
              WHERE a.agent_id = :agent_id
              ORDER BY t.created_at DESC";
    
    $stmt = $db->prepare($query);
    $stmt->bindParam(":agent_id", $tokenData['user_id']);
    
} elseif ($tokenData['role'] === 'admin') {
    // Admin sees all tickets
    $query = "SELECT t.*, u.name as customer_name, u.phone as customer_phone, t.file_url
              FROM tickets t
              JOIN users u ON t.customer_id = u.id
              ORDER BY t.created_at DESC";
    
    $stmt = $db->prepare($query);
} else {
    sendError(403, 'Unauthorized role');
}

$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(200, 'Tickets retrieved successfully', [
    'count' => count($tickets),
    'tickets' => $tickets
]);
?>
