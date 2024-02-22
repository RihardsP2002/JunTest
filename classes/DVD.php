<?php
require_once 'Product.php';

class DVD extends Product {
    private $size;
    
    public static array $attributesMap = ['size'];

    public function __construct($sku, $name, $price, $size) {
        parent::__construct($sku, $name, $price);
        $this->size = $size;
    }
    
    public function getSize() {
        return $this->size;
    }

    public function getProductSpecificAttribute() {
        return "Size: {$this->size} MB";
    }

    public function insertIntoDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, size, type) VALUES (?, ?, ?, ?, ?)");
        $type = "DVD";
        $stmt->bind_param("ssdds", $this->getSKU(), $this->getName(), $this->getPrice(), $this->getSize(), $type);
        $stmt->execute();
        $stmt->close();
    }
}
?>