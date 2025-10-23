 
<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
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

// Only agents and admins can update status
if ($tokenData['role'] === 'customer') {
    sendError(403, 'Customers cannot update ticket status');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->ticket_id) || empty($data->status)) {
    sendError(400, 'Ticket ID and status are required');
}

$validStatuses = ['pending', 'assigned', 'in_progress', 'resolved', 'closed'];

if (!in_array($data->status, $validStatuses)) {
    sendError(400, 'Invalid status value');
}

$database = new Database();
$db = $database->getConnection();

// For agents, verify they are assigned to this ticket
if ($tokenData['role'] === 'agent') {
    $checkQuery = "SELECT id FROM assignments WHERE ticket_id = :ticket_id AND agent_id = :agent_id";
    $checkStmt = $db->prepare($checkQuery);
    $checkStmt->bindParam(":ticket_id", $data->ticket_id);
    $checkStmt->bindParam(":agent_id", $tokenData['user_id']);
    $checkStmt->execute();
    
    if ($checkStmt->rowCount() == 0) {
        sendError(403, 'You are not assigned to this ticket');
    }
}

$query = "UPDATE tickets SET status = :status WHERE id = :ticket_id";
$stmt = $db->prepare($query);
$stmt->bindParam(":status", $data->status);
$stmt->bindParam(":ticket_id", $data->ticket_id);

if ($stmt->execute()) {
    sendResponse(200, 'Ticket status updated successfully', [
        'ticket_id' => $data->ticket_id,
        'new_status' => $data->status
    ]);
} else {
    sendError(500, 'Failed to update ticket status');
}
?>
