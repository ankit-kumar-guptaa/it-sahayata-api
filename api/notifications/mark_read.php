<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

require_once '../../config/database.php';
require_once '../../models/Notification.php';

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['notification_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'notification_id is required']);
            exit();
        }
        
        $notification_id = $data['notification_id'];
        $mark_all = $data['mark_all'] ?? false;
        $recipient_type = $data['recipient_type'] ?? null;
        $recipient_id = $data['recipient_id'] ?? null;
        
        $notification = new Notification($pdo);
        
        if ($mark_all && $recipient_type && $recipient_id) {
            // Mark all notifications as read for this recipient
            $success = $notification->markAllAsRead($recipient_type, $recipient_id);
            $message = 'All notifications marked as read';
        } else {
            // Mark single notification as read
            $success = $notification->markAsRead($notification_id);
            $message = 'Notification marked as read';
        }
        
        if ($success) {
            http_response_code(200);
            echo json_encode(['success' => true, 'message' => $message]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Notification not found']);
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Server error: ' . $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}
?>