<?php
require_once 'Product.php';

class Book extends Product {
    private $weight;
    
    public static array $attributesMap = ['weight'];

    public function __construct($sku, $name, $price, $weight) {
        parent::__construct($sku, $name, $price);
        $this->weight = $weight;
    }
    
    public function getWeight() {
        return $this->weight;
    }

    public function getProductSpecificAttribute() {
        return "Weight: {$this->weight} Kg";
    }

    public function insertIntoDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, weight, type) VALUES (?, ?, ?, ?, ?)");
        $type = "Book";
        $stmt->bind_param("ssdds", $this->getSKU(), $this->getName(), $this->getPrice(), $this->getWeight(), $type);
        $stmt->execute();
        $stmt->close();
    }
}
?>