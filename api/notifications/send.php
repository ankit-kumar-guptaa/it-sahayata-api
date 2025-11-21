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
        // Get JSON input
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['title']) || !isset($data['message']) || !isset($data['recipient_type']) || !isset($data['recipient_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            exit();
        }
        
        $title = $data['title'];
        $message = $data['message'];
        $type = $data['type'] ?? 'general';
        $recipient_type = $data['recipient_type'];
        $recipient_id = $data['recipient_id'];
        $related_ticket_id = $data['related_ticket_id'] ?? null;
        $metadata = $data['metadata'] ?? [];
        
        // Create notification in database
        $notification = new Notification($pdo);
        $notification_id = $notification->create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'recipient_type' => $recipient_type,
            'recipient_id' => $recipient_id,
            'related_ticket_id' => $related_ticket_id,
            'metadata' => json_encode($metadata),
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Here you would integrate with your push notification service
        // For now, we'll just return success
        
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Notification sent successfully',
            'notification_id' => $notification_id
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