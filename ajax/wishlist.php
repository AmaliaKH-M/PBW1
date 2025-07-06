<?php
require_once '../config/kosmarket_db.php';

header('Content-Type: application/json');

// Wishlist feature has been removed
echo json_encode([
    'success' => false, 
    'message' => 'Wishlist feature has been removed'
]);
?>