 
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

if (!isset($_FILES['file'])) {
    sendError(400, 'No file uploaded');
}

$file = $_FILES['file'];

// File validation
$allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf', 'video/mp4'];
$maxSize = 10 * 1024 * 1024; // 10MB

if (!in_array($file['type'], $allowedTypes)) {
    sendError(400, 'Invalid file type. Only JPG, PNG, PDF, MP4 allowed');
}

if ($file['size'] > $maxSize) {
    sendError(400, 'File size exceeds 10MB limit');
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '_' . time() . '.' . $extension;

// Determine upload directory
$uploadDir = isset($_POST['type']) && $_POST['type'] === 'ticket' ? '../../uploads/tickets/' : '../../uploads/chat/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$uploadPath = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    $fileUrl = str_replace('../../', '', $uploadPath);
    
    sendResponse(200, 'File uploaded successfully', [
        'file_url' => $fileUrl,
        'file_name' => $filename
    ]);
} else {
    sendError(500, 'Failed to upload file');
}
?>
