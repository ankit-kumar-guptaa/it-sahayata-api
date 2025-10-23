 
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

// Only agents can update resolution
if ($tokenData['role'] !== 'agent') {
    sendError(403, 'Access denied. Agents only');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->ticket_id) || empty($data->resolution)) {
    sendError(400, 'Ticket ID and resolution notes are required');
}

$database = new Database();
$db = $database->getConnection();

// Verify agent is assigned to this ticket
$checkQuery = "SELECT id FROM assignments WHERE ticket_id = :ticket_id AND agent_id = :agent_id";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(":ticket_id", $data->ticket_id);
$checkStmt->bindParam(":agent_id", $tokenData['user_id']);
$checkStmt->execute();

if ($checkStmt->rowCount() == 0) {
    sendError(403, 'You are not assigned to this ticket');
}

// Add resolution as a message
$msgQuery = "INSERT INTO messages (ticket_id, sender_id, message) 
             VALUES (:ticket_id, :sender_id, :message)";

$msgStmt = $db->prepare($msgQuery);
$msgStmt->bindParam(":ticket_id", $data->ticket_id);
$msgStmt->bindParam(":sender_id", $tokenData['user_id']);

$resolutionMessage = "🔧 RESOLUTION NOTES:\n\n" . $data->resolution;
$msgStmt->bindParam(":message", $resolutionMessage);
$msgStmt->execute();

// Update ticket status to resolved
$updateQuery = "UPDATE tickets SET status = 'resolved' WHERE id = :ticket_id";
$updateStmt = $db->prepare($updateQuery);
$updateStmt->bindParam(":ticket_id", $data->ticket_id);

if ($updateStmt->execute()) {
    sendResponse(200, 'Resolution updated and ticket marked as resolved', [
        'ticket_id' => $data->ticket_id,
        'status' => 'resolved'
    ]);
} else {
    sendError(500, 'Failed to update resolution');
}
?>
