<?php
require_once 'config.php';
require_once 'classes/Product.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['skus'])) {
        $skus = $_POST['skus'];

        $result = Product::massDeleteProducts($conn, $skus); 

        header('Content-Type: application/json');
        echo json_encode(['success' => $result, 'message' => $result ? 'Products deleted successfully.' : 'Failed to delete products.']);
        exit();
    } else {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Invalid request. Please provide SKUs for deletion.']);
        exit();
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method. Only POST requests are allowed.']);
    exit();
}
?>
