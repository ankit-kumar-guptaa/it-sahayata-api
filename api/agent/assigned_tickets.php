 
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

// Only agents can access this endpoint
if ($tokenData['role'] !== 'agent') {
    sendError(403, 'Access denied. Agents only');
}

$database = new Database();
$db = $database->getConnection();

$query = "SELECT t.*, 
          u.name as customer_name, u.email as customer_email, u.phone as customer_phone,
          a.assigned_at,
          (SELECT COUNT(*) FROM messages WHERE ticket_id = t.id) as message_count
          FROM tickets t
          JOIN assignments a ON t.id = a.ticket_id
          JOIN users u ON t.customer_id = u.id
          WHERE a.agent_id = :agent_id
          ORDER BY 
            CASE 
              WHEN t.status = 'in_progress' THEN 1
              WHEN t.status = 'assigned' THEN 2
              WHEN t.status = 'resolved' THEN 3
              ELSE 4
            END,
            t.priority DESC,
            t.created_at DESC";

$stmt = $db->prepare($query);
$stmt->bindParam(":agent_id", $tokenData['user_id']);
$stmt->execute();

$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get statistics
$statsQuery = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN t.status = 'assigned' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN t.status = 'in_progress' THEN 1 ELSE 0 END) as in_progress,
                SUM(CASE WHEN t.status = 'resolved' THEN 1 ELSE 0 END) as resolved
                FROM tickets t
                JOIN assignments a ON t.id = a.ticket_id
                WHERE a.agent_id = :agent_id";

$statsStmt = $db->prepare($statsQuery);
$statsStmt->bindParam(":agent_id", $tokenData['user_id']);
$statsStmt->execute();
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);

sendResponse(200, 'Assigned tickets retrieved successfully', [
    'statistics' => $stats,
    'tickets' => $tickets
]);
?>
