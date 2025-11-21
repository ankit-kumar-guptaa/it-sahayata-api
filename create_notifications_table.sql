-- Create notifications table migration
-- Run this SQL script in your database to create the notifications table

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'general', -- 'assignment', 'resolution', 'broadcast', 'promotional', 'general'
    recipient_type VARCHAR(20) NOT NULL, -- 'customer', 'agent', 'admin', 'all'
    recipient_id VARCHAR(100) NOT NULL,
    related_ticket_id INT NULL,
    metadata JSON NULL,
    is_read TINYINT(1) NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    -- Indexes for better performance
    INDEX idx_recipient (recipient_type, recipient_id),
    INDEX idx_type (type),
    INDEX idx_related_ticket (related_ticket_id),
    INDEX idx_is_read (is_read),
    INDEX idx_created_at (created_at),
    
    -- Foreign key constraint (if you want to enforce referential integrity)
    -- FOREIGN KEY (related_ticket_id) REFERENCES tickets(id) ON DELETE SET NULL
);

-- Insert some sample data for testing
INSERT INTO notifications (title, message, type, recipient_type, recipient_id, related_ticket_id, metadata, is_read) VALUES
('Welcome to IT Sahayata', 'Thank you for registering with IT Sahayata support system', 'promotional', 'customer', 'customer1', NULL, '{"welcome": true}', 0),
('New Ticket Assignment', 'You have been assigned to ticket #123: Login issue', 'assignment', 'agent', 'agent1', 123, '{"ticket_id": 123, "priority": "high"}', 0),
('Ticket Resolved', 'Your ticket #123 has been resolved successfully', 'resolution', 'customer', 'customer1', 123, '{"ticket_id": 123, "resolved_by": "agent1"}', 0),
('System Maintenance', 'Scheduled maintenance will occur tonight from 2-4 AM', 'broadcast', 'all', 'all', NULL, '{"maintenance": true, "duration": "2 hours"}', 0);

-- Create a view for unread notifications count
CREATE OR REPLACE VIEW notification_counts AS
SELECT 
    recipient_type,
    recipient_id,
    COUNT(*) as total_notifications,
    SUM(CASE WHEN is_read = 0 THEN 1 ELSE 0 END) as unread_count
FROM notifications
GROUP BY recipient_type, recipient_id;