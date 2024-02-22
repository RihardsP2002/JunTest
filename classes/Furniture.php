<?php
require_once 'Product.php';

class Furniture extends Product {
    private $height;
    private $width;
    private $length;
    
    public static array $attributesMap = ['height', 'width', 'length'];

    public function __construct($sku, $name, $price, $height, $width, $length) {
        parent::__construct($sku, $name, $price);
        $this->height = $height;
        $this->width = $width;
        $this->length = $length;
    }
    
    public function getHeight() {
        return $this->height;
    }

    public function getWidth() {
        return $this->width;
    }

    public function getLength() {
        return $this->length;
    }

    public function getProductSpecificAttribute() {
        return "Dimensions: {$this->height}x{$this->width}x{$this->length}";
    }

    public function insertIntoDatabase($conn) {
        $stmt = $conn->prepare("INSERT INTO products (sku, name, price, height, width, length, type) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $type = "Furniture";
        $stmt->bind_param("ssdddss", $this->getSKU(), $this->getName(), $this->getPrice(), $this->height, $this->width, $this->length, $type);
        $stmt->execute();
        $stmt->close();
    }
}
?>
