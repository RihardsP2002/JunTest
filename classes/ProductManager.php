<?php
require_once __DIR__ . '/../config.php';
require_once 'Product.php';
require_once 'DVD.php';
require_once 'Book.php';
require_once 'Furniture.php';

class ProductManager {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
        
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public function handleFormSubmission() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $this->addProductFromForm($_POST);
            header('Content-Type: application/json');
            echo json_encode($result);
            exit();
        }
    }

    public function addProductFromForm($formData) {
        $sku = $formData['sku'] ?? null;
        $name = $formData['name'] ?? null;
        $price = $formData['price'] ?? null;
        $productType = $formData['type'] ?? null;
    
        if (empty($productType)) {
            return ['success' => false, 'message' => 'Product type is required.'];
        }
    
        $this->conn->begin_transaction();
    
        try {
            $isDuplicate = $this->isDuplicateSKU($sku);
            if ($isDuplicate) {
                return ['success' => false, 'message' => 'Duplicate SKU.'];
            }
    
            $productClass = ucfirst($productType);
    
            if (!class_exists($productClass)) {
                return ['success' => false, 'message' => 'Invalid product type.'];
            }
    
            $specificAttributesMap = $productClass::$attributesMap ?? [];
    
            $constructorArgs = array_map(function($attribute) use ($formData) {
                return $formData[$attribute] ?? null;
            }, $specificAttributesMap);
    
            $constructorArgs = array_merge([$sku, $name, $price], $constructorArgs);
    
            $product = new $productClass(...$constructorArgs);
    
            $product->insertIntoDatabase($this->conn);
    
            $this->conn->commit();
    
            return ['success' => true];
        } catch (Exception $e) {
            $this->conn->rollback();
            return ['success' => false, 'message' => 'An error occurred while adding the product: ' . $e->getMessage()];
        }
    }
    
    
    public function isDuplicateSKU($sku) {
        $stmt = $this->conn->prepare("SELECT COUNT(*) as count FROM products WHERE sku = ?");
        $stmt->bind_param("s", $sku);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $row['count'];
        $stmt->close();

        return $count > 0;
    }

    public function handleDuplicateSKURequest() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid request method']);
            exit();
        }
    
        if (isset($_POST['sku'])) {
            $sku = $_POST['sku'];
            $isDuplicate = $this->isDuplicateSKU($sku);
            header('Content-Type: application/json');
            echo json_encode(['duplicate' => $isDuplicate]);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'SKU not provided']);
        }
    }
    

public function getProductsHTML() {
    $productsHTML = '';

    $result = $this->conn->query("SELECT * FROM products");

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $productType = $row['type'];

            $className = ucfirst($productType);

            if (class_exists($className)) {
                $reflectionClass = new ReflectionClass($className);
                $constructorParams = $reflectionClass->getConstructor()->getParameters();
                $args = [];
                foreach ($constructorParams as $param) {
                    $paramName = $param->getName();
                    if (array_key_exists($paramName, $row)) {
                        $args[] = $row[$paramName];
                    }
                }

                $product = $reflectionClass->newInstanceArgs($args);

                $productsHTML .= '<div class="product-container">';
                $productsHTML .= '<div class="product-info">';
                $productsHTML .= $product->displayProduct();
                $productsHTML .= '</div>';
                $productsHTML .= '<div class="product-actions">';
                $productsHTML .= '<input type="checkbox" class="delete-checkbox" value="' . $product->getSKU() . '">';
                $productsHTML .= '</div>';
                $productsHTML .= '</div>';
            }
        }
    }

    return $productsHTML;
}

}
?>
