 
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

// Get ticket details
$query = "SELECT t.*, 
          u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
          a.agent_id, ag.name as agent_name
          FROM tickets t
          JOIN users u ON t.customer_id = u.id
          LEFT JOIN assignments a ON t.id = a.ticket_id
          LEFT JOIN users ag ON a.agent_id = ag.id
          WHERE t.id = :ticket_id";

$stmt = $db->prepare($query);
$stmt->bindParam(":ticket_id", $ticket_id);
$stmt->execute();

if ($stmt->rowCount() == 0) {
    sendError(404, 'Ticket not found');
}

$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

// Check access rights
if ($tokenData['role'] === 'customer' && $ticket['customer_id'] != $tokenData['user_id']) {
    sendError(403, 'Access denied');
}

if ($tokenData['role'] === 'agent' && $ticket['agent_id'] != $tokenData['user_id']) {
    sendError(403, 'Access denied');
}

// Get messages count
$msgQuery = "SELECT COUNT(*) as msg_count FROM messages WHERE ticket_id = :ticket_id";
$msgStmt = $db->prepare($msgQuery);
$msgStmt->bindParam(":ticket_id", $ticket_id);
$msgStmt->execute();
$msgData = $msgStmt->fetch(PDO::FETCH_ASSOC);
$ticket['messages_count'] = $msgData['msg_count'];

sendResponse(200, 'Ticket details retrieved', $ticket);
?>
