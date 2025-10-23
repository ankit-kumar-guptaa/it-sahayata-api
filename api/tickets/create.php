 
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

// Only customers can create tickets
if ($tokenData['role'] !== 'customer') {
    sendError(403, 'Only customers can create tickets');
}

$data = json_decode(file_get_contents("php://input"));

if (empty($data->category) || empty($data->description)) {
    sendError(400, 'Category and description are required');
}

$database = new Database();
$db = $database->getConnection();

$priority = isset($data->priority) ? $data->priority : 'medium';
$file_url = isset($data->file_url) ? $data->file_url : null;

$query = "INSERT INTO tickets (customer_id, category, description, priority, file_url) 
          VALUES (:customer_id, :category, :description, :priority, :file_url)";

$stmt = $db->prepare($query);
$stmt->bindParam(":customer_id", $tokenData['user_id']);
$stmt->bindParam(":category", $data->category);
$stmt->bindParam(":description", $data->description);
$stmt->bindParam(":priority", $priority);
$stmt->bindParam(":file_url", $file_url);

if ($stmt->execute()) {
    $ticket_id = $db->lastInsertId();
    
    sendResponse(201, 'Ticket created successfully', [
        'ticket_id' => $ticket_id,
        'status' => 'pending'
    ]);
} else {
    sendError(500, 'Failed to create ticket');
}
?>
