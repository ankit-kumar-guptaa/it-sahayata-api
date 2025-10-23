 
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
    sendError(401, 'Invalid or expired token');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->ticket_id) || empty($data->message)) {
    sendError(400, 'Ticket ID and message are required');
}

$database = new Database();
$db = $database->getConnection();

// Verify user has access to this ticket
$accessQuery = "SELECT t.id, t.customer_id, a.agent_id 
                FROM tickets t
                LEFT JOIN assignments a ON t.id = a.ticket_id
                WHERE t.id = :ticket_id";

$accessStmt = $db->prepare($accessQuery);
$accessStmt->bindParam(":ticket_id", $data->ticket_id);
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

$file_url = isset($data->file_url) ? $data->file_url : null;

$query = "INSERT INTO messages (ticket_id, sender_id, message, file_url) 
          VALUES (:ticket_id, :sender_id, :message, :file_url)";

$stmt = $db->prepare($query);
$stmt->bindParam(":ticket_id", $data->ticket_id);
$stmt->bindParam(":sender_id", $tokenData['user_id']);
$stmt->bindParam(":message", $data->message);
$stmt->bindParam(":file_url", $file_url);

if ($stmt->execute()) {
    $message_id = $db->lastInsertId();
    
    sendResponse(201, 'Message sent successfully', [
        'message_id' => $message_id,
        'ticket_id' => $data->ticket_id
    ]);
} else {
    sendError(500, 'Failed to send message');
}
?>
