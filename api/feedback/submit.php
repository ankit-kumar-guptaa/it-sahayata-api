 
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

// Only customers can submit feedback
if ($tokenData['role'] !== 'customer') {
    sendError(403, 'Only customers can submit feedback');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->ticket_id) || empty($data->rating)) {
    sendError(400, 'Ticket ID and rating are required');
}

if ($data->rating < 1 || $data->rating > 5) {
    sendError(400, 'Rating must be between 1 and 5');
}

$database = new Database();
$db = $database->getConnection();

// Verify ticket belongs to customer and is resolved
$ticketQuery = "SELECT id, customer_id, status FROM tickets WHERE id = :ticket_id";
$ticketStmt = $db->prepare($ticketQuery);
$ticketStmt->bindParam(":ticket_id", $data->ticket_id);
$ticketStmt->execute();

if ($ticketStmt->rowCount() == 0) {
    sendError(404, 'Ticket not found');
}

$ticket = $ticketStmt->fetch(PDO::FETCH_ASSOC);

if ($ticket['customer_id'] != $tokenData['user_id']) {
    sendError(403, 'You can only provide feedback for your own tickets');
}

if ($ticket['status'] !== 'resolved') {
    sendError(400, 'Can only provide feedback for resolved tickets');
}

// Check if feedback already exists
$checkQuery = "SELECT id FROM feedback WHERE ticket_id = :ticket_id";
$checkStmt = $db->prepare($checkQuery);
$checkStmt->bindParam(":ticket_id", $data->ticket_id);
$checkStmt->execute();

if ($checkStmt->rowCount() > 0) {
    sendError(409, 'Feedback already submitted for this ticket');
}

$comment = isset($data->comment) ? $data->comment : null;

$query = "INSERT INTO feedback (ticket_id, rating, comment) 
          VALUES (:ticket_id, :rating, :comment)";

$stmt = $db->prepare($query);
$stmt->bindParam(":ticket_id", $data->ticket_id);
$stmt->bindParam(":rating", $data->rating);
$stmt->bindParam(":comment", $comment);

if ($stmt->execute()) {
    // Update ticket status to closed
    $updateQuery = "UPDATE tickets SET status = 'closed' WHERE id = :ticket_id";
    $updateStmt = $db->prepare($updateQuery);
    $updateStmt->bindParam(":ticket_id", $data->ticket_id);
    $updateStmt->execute();
    
    sendResponse(201, 'Feedback submitted successfully', [
        'feedback_id' => $db->lastInsertId()
    ]);
} else {
    sendError(500, 'Failed to submit feedback');
}
?>
