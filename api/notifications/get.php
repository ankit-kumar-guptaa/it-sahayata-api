<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/database.php';
require_once '../../models/Notification.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    try {
        $recipient_type = $_GET['recipient_type'] ?? null;
        $recipient_id = $_GET['recipient_id'] ?? null;
        $type = $_GET['type'] ?? null;
        $is_read = $_GET['is_read'] ?? null;
        $limit = $_GET['limit'] ?? 50;
        $offset = $_GET['offset'] ?? 0;
        
        if (!$recipient_type || !$recipient_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'recipient_type and recipient_id are required']);
            exit();
        }
        
        $notification = new Notification($pdo);
        $notifications = $notification->getNotifications([
            'recipient_type' => $recipient_type,
            'recipient_id' => $recipient_id,
            'type' => $type,
            'is_read' => $is_read,
            'limit' => $limit,
            'offset' => $offset
        ]);
        
        $unread_count = $notification->getUnreadCount($recipient_type, $recipient_id);
        
        http_response_code(200);
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread_count' => $unread_count,
            'total' => count($notifications)
        ]);
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>