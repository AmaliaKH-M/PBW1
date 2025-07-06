<?php
require_once '../config/kosmarket_db.php';
require_once '../classes/Product.php';

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
$product = new Product($db);

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'delete':
        $product_id = $_POST['product_id'] ?? '';
        
        if (empty($product_id)) {
            echo json_encode(['success' => false, 'message' => 'Product ID required']);
            exit;
        }
        
        if ($product->delete($product_id)) {
            echo json_encode(['success' => true, 'message' => 'Product deleted successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete product']);
        }
        break;
        
    case 'toggle_status':
        $product_id = $_POST['product_id'] ?? '';
        $status = $_POST['status'] ?? '';
        
        if (empty($product_id) || empty($status)) {
            echo json_encode(['success' => false, 'message' => 'Product ID and status required']);
            exit;
        }
        
        if ($product->updateStatus($product_id, $status)) {
            echo json_encode(['success' => true, 'message' => 'Product status updated']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update status']);
        }
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}
?>