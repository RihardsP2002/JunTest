<?php
require_once 'config.php';
require_once 'classes/ProductManager.php';

$productManager = new ProductManager($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productManager->handleDuplicateSKURequest();
    exit(); 
}

$productsHTML = $productManager->getProductsHTML();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product List</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script src="js/script.js"></script>
</head>
<body>
    <div class="header">
        <h1>Product List</h1>
        <div class="buttons-container">
            <a href="add-product.php" id="addBtn">ADD</a>
            <button id="massDeleteBtn">MASS DELETE</button>
        </div>
    </div>

    <div class="products-container">
        <?= $productsHTML ?>
    </div>

    <div class="footer">
        <div class="line"></div>
        <p class="footer-text">Scandiweb Test Assignment</p>
    </div>
</body>
</html>
