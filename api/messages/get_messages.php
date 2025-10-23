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

if (!isset($_GET['ticket_id'])) {
    sendError(400, 'Ticket ID is required');
}

$ticket_id = $_GET['ticket_id'];

$database = new Database();
$db = $database->getConnection();

// Verify access to ticket
$accessQuery = "SELECT t.id, t.customer_id, a.agent_id 
                FROM tickets t
                LEFT JOIN assignments a ON t.id = a.ticket_id
                WHERE t.id = :ticket_id";

$accessStmt = $db->prepare($accessQuery);
$accessStmt->bindParam(":ticket_id", $ticket_id);
$accessStmt->execute();

if ($accessStmt->rowCount() == 0) {
    sendError(404, 'Ticket not found');
}

$ticketData = $accessStmt->fetch(PDO::FETCH_ASSOC);

// Check if user has access
$hasAccess = false;
if ($tokenData['role'] === 'customer' && $ticketData['customer_id'] == $tokenData['user_id']) {
    $hasAccess = true;
} elseif ($tokenData['role'] === 'agent' && $ticketData['agent_id'] == $tokenData['user_id']) {
    $hasAccess = true;
} elseif ($tokenData['role'] === 'admin') {
    $hasAccess = true;
}

if (!$hasAccess) {
    sendError(403, 'Access denied');
}

// Get messages
$query = "SELECT m.*, u.name as sender_name, u.role as sender_role
          FROM messages m
          JOIN users u ON m.sender_id = u.id
          WHERE m.ticket_id = :ticket_id
          ORDER BY m.timestamp ASC";

$stmt = $db->prepare($query);
$stmt->bindParam(":ticket_id", $ticket_id);
$stmt->execute();

$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

sendResponse(200, 'Messages retrieved successfully', [
    'count' => count($messages),
    'messages' => $messages
]);
?>
