<?php
require_once '../../config/database.php';
require_once '../../utils/jwt_helper.php';
require_once '../../utils/response.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendError(405, 'Method not allowed');
}

$token = JWTHelper::getBearerToken();
if (!$token) sendError(401, 'No token provided');

$tokenData = JWTHelper::validateToken($token);
if (!$tokenData) sendError(401, 'Invalid token');

// Only admin can assign agents
if ($tokenData['role'] !== 'admin') {
    sendError(403, 'Admin access only');
}

$data = json_decode(file_get_contents("php://input"));
if (empty($data->ticket_id) || empty($data->agent_id)) {
    sendError(400, 'Ticket ID and Agent ID required');
}

$database = new Database();
$db = $database->getConnection();

// Check if ticket exists
$ticketCheck = "SELECT id, status FROM tickets WHERE id = :ticket_id";
$stmt = $db->prepare($ticketCheck);
$stmt->bindParam(":ticket_id", $data->ticket_id);
$stmt->execute();
if ($stmt->rowCount() == 0) sendError(404, 'Ticket not found');

// Check if agent exists
$agentCheck = "SELECT id, name FROM users WHERE id = :agent_id AND role = 'agent'";
$stmt2 = $db->prepare($agentCheck);
$stmt2->bindParam(":agent_id", $data->agent_id);
$stmt2->execute();
if ($stmt2->rowCount() == 0) sendError(404, 'Agent not found');

$agent = $stmt2->fetch(PDO::FETCH_ASSOC);

// Check if already assigned
$assignCheck = "SELECT id FROM assignments WHERE ticket_id = :ticket_id";
$stmt3 = $db->prepare($assignCheck);
$stmt3->bindParam(":ticket_id", $data->ticket_id);
$stmt3->execute();

if ($stmt3->rowCount() > 0) {
    // Update existing assignment
    $updateQuery = "UPDATE assignments SET agent_id = :agent_id, assigned_by = :admin_id, assigned_at = NOW() 
                    WHERE ticket_id = :ticket_id";
    $stmt4 = $db->prepare($updateQuery);
    $stmt4->bindParam(":agent_id", $data->agent_id);
    $stmt4->bindParam(":admin_id", $tokenData['user_id']);
    $stmt4->bindParam(":ticket_id", $data->ticket_id);
    $stmt4->execute();
    
    $message = 'Agent reassigned successfully';
} else {
    // New assignment
    $insertQuery = "INSERT INTO assignments (ticket_id, agent_id, assigned_by) 
                    VALUES (:ticket_id, :agent_id, :admin_id)";
    $stmt4 = $db->prepare($insertQuery);
    $stmt4->bindParam(":ticket_id", $data->ticket_id);
    $stmt4->bindParam(":agent_id", $data->agent_id);
    $stmt4->bindParam(":admin_id", $tokenData['user_id']);
    $stmt4->execute();
    
    $message = 'Agent assigned successfully';
}

// Update ticket status to 'assigned'
$updateTicket = "UPDATE tickets SET status = 'assigned' WHERE id = :ticket_id";
$stmt5 = $db->prepare($updateTicket);
$stmt5->bindParam(":ticket_id", $data->ticket_id);
$stmt5->execute();

sendResponse(200, $message, [
    'ticket_id' => $data->ticket_id,
    'agent_id' => $data->agent_id,
    'agent_name' => $agent['name']
]);
?>
