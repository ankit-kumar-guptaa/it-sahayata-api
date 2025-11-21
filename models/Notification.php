<?php
class Notification {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Create a new notification
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO notifications 
                    (title, message, type, recipient_type, recipient_id, related_ticket_id, metadata, is_read, created_at) 
                    VALUES (:title, :message, :type, :recipient_type, :recipient_id, :related_ticket_id, :metadata, :is_read, :created_at)";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':message' => $data['message'],
                ':type' => $data['type'],
                ':recipient_type' => $data['recipient_type'],
                ':recipient_id' => $data['recipient_id'],
                ':related_ticket_id' => $data['related_ticket_id'],
                ':metadata' => $data['metadata'],
                ':is_read' => $data['is_read'],
                ':created_at' => $data['created_at']
            ]);
            
            return $this->pdo->lastInsertId();
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Get notifications with filters
     */
    public function getNotifications($filters = []) {
        try {
            $sql = "SELECT * FROM notifications WHERE 1=1";
            $params = [];
            
            if (!empty($filters['recipient_type'])) {
                $sql .= " AND recipient_type = :recipient_type";
                $params[':recipient_type'] = $filters['recipient_type'];
            }
            
            if (!empty($filters['recipient_id'])) {
                $sql .= " AND recipient_id = :recipient_id";
                $params[':recipient_id'] = $filters['recipient_id'];
            }
            
            if (!empty($filters['type'])) {
                $sql .= " AND type = :type";
                $params[':type'] = $filters['type'];
            }
            
            if (isset($filters['is_read'])) {
                $sql .= " AND is_read = :is_read";
                $params[':is_read'] = $filters['is_read'] ? 1 : 0;
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT :limit";
                $params[':limit'] = (int)$filters['limit'];
            }
            
            if (!empty($filters['offset'])) {
                $sql .= " OFFSET :offset";
                $params[':offset'] = (int)$filters['offset'];
            }
            
            $stmt = $this->pdo->prepare($sql);
            
            foreach ($params as $key => $value) {
                if ($key === ':limit' || $key === ':offset') {
                    $stmt->bindValue($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $value);
                }
            }
            
            $stmt->execute();
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Parse metadata from JSON
            foreach ($notifications as &$notification) {
                if (!empty($notification['metadata'])) {
                    $notification['metadata'] = json_decode($notification['metadata'], true);
                } else {
                    $notification['metadata'] = [];
                }
            }
            
            return $notifications;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Mark a notification as read
     */
    public function markAsRead($notification_id) {
        try {
            $sql = "UPDATE notifications SET is_read = 1 WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $notification_id]);
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Mark all notifications as read for a recipient
     */
    public function markAllAsRead($recipient_type, $recipient_id) {
        try {
            $sql = "UPDATE notifications SET is_read = 1 
                    WHERE recipient_type = :recipient_type AND recipient_id = :recipient_id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':recipient_type' => $recipient_type,
                ':recipient_id' => $recipient_id
            ]);
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Get unread notification count for a recipient
     */
    public function getUnreadCount($recipient_type, $recipient_id) {
        try {
            $sql = "SELECT COUNT(*) as count FROM notifications 
                    WHERE recipient_type = :recipient_type AND recipient_id = :recipient_id AND is_read = 0";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':recipient_type' => $recipient_type,
                ':recipient_id' => $recipient_id
            ]);
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['count'] ?? 0;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Delete a notification
     */
    public function delete($notification_id) {
        try {
            $sql = "DELETE FROM notifications WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $notification_id]);
            
            return $stmt->rowCount() > 0;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
    
    /**
     * Get notification by ID
     */
    public function getById($notification_id) {
        try {
            $sql = "SELECT * FROM notifications WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $notification_id]);
            
            $notification = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($notification && !empty($notification['metadata'])) {
                $notification['metadata'] = json_decode($notification['metadata'], true);
            }
            
            return $notification;
            
        } catch (PDOException $e) {
            throw new Exception("Database error: " . $e->getMessage());
        }
    }
}
?>