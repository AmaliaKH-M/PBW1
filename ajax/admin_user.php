<?php
require_once '../config/kosmarket_db.php';
require_once '../classes/User.php';

header('Content-Type: application/json');

// Check if user is admin
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

// Simple admin check
function isAdmin() {
    return (isset($_SESSION['user_id']) && ($_SESSION['user_id'] == 1 || 
            (isset($_SESSION['email']) && strpos($_SESSION['email'], 'admin') !== false)));
}

if (!isAdmin()) {
    echo json_encode(['success' => false, 'message' => 'Admin access required']);
    exit;
}

$database = new Database();
$db = $database->getConnection();
$user = new User($db);

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'toggle_status':
        $user_id = $_POST['user_id'] ?? '';
        
        if (empty($user_id)) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            exit;
        }
        
        if ($user->toggleStatus($user_id)) {
            echo json_encode(['success' => true, 'message' => 'User status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
        }
        break;
        
    case 'delete':
        $user_id = $_POST['user_id'] ?? '';
        
        if (empty($user_id)) {
            echo json_encode(['success' => false, 'message' => 'User ID required']);
            exit;
        }
        
        if ($user->delete($user_id)) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>