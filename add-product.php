<?php

require_once 'config.php';
require_once 'classes/ProductManager.php';

$productManager = new ProductManager($conn);
$productManager->handleFormSubmission();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1>Add Product</h1>
        <div class="buttons-container">
            <button type="submit" id="saveBtn">Save</button>
            <a href="index.php" id="cancelBtn">Cancel</a>
        </div>
    </div>

    <div class="container">
    <form id="product_form" method="post" action="add-product.php">
            <div class="form-group">
                <label for="sku">SKU</label>
                <input type="text" id="sku" name="sku" required>
                <div class="notification" id="skuNotification"></div>
            </div>

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
                <div class="notification" id="nameNotification"></div>
            </div>

            <div class="form-group">
                <label for="price">Price ($)</label>
                <input type="number" step="0.01" id="price" name="price" required>
                <div class="notification" id="priceNotification"></div>
            </div>

            <div class="form-group">
                <label for="productType">Type Switcher</label>
                <select id="productType" name="type">
                    <option value="" disabled selected>Select Type</option>
                    <option value="Book">Book</option>
                    <option value="DVD">DVD</option>
                    <option value="Furniture">Furniture</option>
                </select>
                <span id="typeNotification" class="notification"></span>
            </div>

            <div id="specificAttributes" class="form-group">
    
            </div>

            <input type="hidden" name="form_submitted" value="1">
        </form>
    </div>

    <div class="footer">
        <div class="line"></div>
        <p class="footer-text">Scandiweb Test Assignment</p>
    </div>

    <script src="js/script.js"></script>
</body>
</html>