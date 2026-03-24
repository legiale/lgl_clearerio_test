<?php

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

// Example product list
$products = [
    ['id' => 1, 'name' => 'Phone', 'price' => 699.99],
    ['id' => 2, 'name' => 'Tablet', 'price' => 499.50],
    ['id' => 3, 'name' => 'Headphones', 'price' => 89.90],
];

// Parse path
$requestUri = $_SERVER['REQUEST_URI'];
$scriptName = $_SERVER['SCRIPT_NAME'];

$path = str_replace(dirname($scriptName), '', $requestUri);
$path = parse_url($path, PHP_URL_PATH);
$segments = array_values(array_filter(explode('/', trim($path, '/'))));

function sendJson(int $statusCode, array $data): void
{
    http_response_code($statusCode);
    echo json_encode($data, JSON_PRETTY_PRINT);
    exit;
}

// GET  /api.php/products
// POST /api.php/products
// GET  /api.php/products/{id}
if (!isset($segments[1]) || $segments[0] !== 'api.php' || $segments[1] !== 'products') {
    sendJson(404, ['error' => 'Endpoint not found']);
}

// GET /products
if ($method === 'GET' && count($segments) === 2) {
    sendJson(200, $products);
}

// GET /products/{id}
if ($method === 'GET' && count($segments) === 3) {
    $id = (int) $segments[2];

    foreach ($products as $product) {
        if ($product['id'] === $id) {
            sendJson(200, $product);
        }
    }
    sendJson(404, ['error' => 'Product not found']);
}

// POST /products
if ($method === 'POST' && count($segments) === 2) {
    $input = json_decode(file_get_contents('php://input'), true);

    if (!is_array($input)) {
        sendJson(400, ['error' => 'Invalid payload']);
    }

    $name = trim($input['name'] ?? '');
    $price = $input['price'] ?? null;

    if ($name === '' || !is_numeric($price) || $price < 0) {
        sendJson(400, ['error' => 'Invalid product data. Name and valid price are required.']);
    }

    $newId = max(array_column($products, 'id')) + 1;

    $newProduct = [
        'id' => $newId,
        'name' => $name,
        'price' => (float) $price,
    ];

    $products[] = $newProduct;

    sendJson(201, [
        'message' => 'Product added successfully',
        'product' => $newProduct,
    ]);
}

sendJson(405, ['error' => 'Method not allowed']);