# lgl_clearerio_test
clearer.io Tech screen 

## Project Structure
├── order_test.php # Task 1 - OOP Order system

├── database.php # Task 2 - MySQL + PHP

├── api.php # Task 3 - REST APIs

---

## Requirements

- PHP >= 7.4
- MySQL / MariaDB
- Terminal / CLI
- curl (optional for API testing)

---

## How to Run

### 1. Task 1 – Order Management (OOP)

Run the script:

```bash
php order_test.php
```
What it does:

- Creates a normal Order
- Adds items and updates status
- Creates an ExpressOrder
- Applies express fee
- Prints order details

### Task 2 – MySQL & PHP
   Step 1: Configure Database

Open database.php and update credentials if needed:

$host = '127.0.0.1';

$dbName = 'clearerio_test_db';

$username = 'root';

$password = '';

Step 2: Run Script
```bash
php database.php
```
What it does:

- Creates database assignment_db (if not exists)
- Creates customers table
- Inserts 3 sample customers
- Fetches and prints all customers
- getCustomerByEmail() function

### Task 3 – REST API
   Step 1: Start PHP Server
`php -S localhost:8000`

   Step 2: Test API Endpoints

   Get all products

   `curl -X GET http://localhost:8000/api.php/products`

   Get product by ID

   `curl -X GET http://localhost:8000/api.php/products/1`

   Create new product

   `curl -X POST \
   -H "Content-Type: application/json" \
   -d '{"name": "Laptop", "price": 1200}' \
   http://localhost:8000/api.php/products`
