 
<?php
class Validator {
    
    public static function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    public static function validatePhone($phone) {
        return preg_match('/^[0-9]{10}$/', $phone);
    }
    
    public static function sanitizeString($string) {
        return htmlspecialchars(strip_tags(trim($string)));
    }
    
    public static function validatePassword($password) {
        // Minimum 6 characters
        return strlen($password) >= 6;
    }
    
    public static function validateTicketCategory($category) {
        $validCategories = ['hardware', 'software', 'internet', 'other'];
        return in_array($category, $validCategories);
    }
    
    public static function validatePriority($priority) {
        $validPriorities = ['low', 'medium', 'high'];
        return in_array($priority, $validPriorities);
    }
    
    public static function validateStatus($status) {
        $validStatuses = ['pending', 'assigned', 'in_progress', 'resolved', 'closed'];
        return in_array($status, $validStatuses);
    }
    
    public static function validateRole($role) {
        $validRoles = ['customer', 'agent', 'admin'];
        return in_array($role, $validRoles);
    }
    
    public static function isEmptyString($value) {
        return empty(trim($value));
    }
}
?>
