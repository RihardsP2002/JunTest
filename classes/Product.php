<?php

abstract class Product {
    private $sku;
    private $name;
    private $price;

    public function __construct($sku, $name, $price) {
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
    }

    public function getSKU() {
        return $this->sku;
    }

    public function getName() {
        return $this->name;
    }

    public function getPrice() {
        return $this->price;
    }
    
    abstract public function getProductSpecificAttribute();

    public function displayProduct() {
        $specificAttribute = $this->getProductSpecificAttribute();
        return "{$this->sku}<br>{$this->name}<br>{$this->price} $<br>{$specificAttribute}";
    }

    abstract public function insertIntoDatabase($conn);

    public static function massDeleteProducts($conn, $skus) {
        $placeholders = implode(',', array_fill(0, count($skus), '?'));

        $stmt = $conn->prepare("DELETE FROM products WHERE sku IN ($placeholders)");

        if (!$stmt) {
            throw new Exception("Error preparing statement: " . $conn->error);
        }

        $types = str_repeat('s', count($skus));
        $stmt->bind_param($types, ...$skus);

        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }

        $stmt->close();

        return true;
    }

}
?>