<?php

class Order
{
    private static int $nextId = 1;

    protected int $id;
    protected string $customerName;
    protected array $items = [];
    protected float $totalPrice = 0.0;
    protected string $status = 'pending';

    public function __construct(string $customerName)
    {
        $this->id = self::$nextId++;
        $this->customerName = $customerName;
    }

    public function addItem(string $item, float $price): void
    {
        if ($price < 0) {
            throw new InvalidArgumentException('Price must not be negative.');
        }
        $this->items[] = $item;
        $this->totalPrice += $price;
    }

    public function changeStatus(string $status): void
    {
        $allowedStatuses = ['pending', 'shipped', 'delivered'];
        if (!in_array($status, $allowedStatuses, true)) {
            throw new InvalidArgumentException('Invalid status.');
        }
        $this->status = $status;
    }

    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'customerName' => $this->customerName,
            'items' => $this->items,
            'totalPrice' => $this->totalPrice,
            'status' => $this->status,
        ];
    }

    public function printDetails(): void
    {
        echo "Order ID: {$this->id}\n";
        echo "Customer Name: {$this->customerName}\n";
        echo "Items: " . (empty($this->items) ? 'No items' : implode(', ', $this->items)) . "\n";
        echo "Total Price: $" . number_format($this->totalPrice, 2) . "\n";
        echo "Status: {$this->status}\n";
    }
}

class ExpressOrder extends Order
{
    private float $expressFee = 0.0;

    public function applyExpressFee(float $fee): void
    {
        if ($fee < 0) {
            throw new InvalidArgumentException('Express fee must not be negative.');
        }

        $this->expressFee = $fee;
        $this->totalPrice += $fee;
    }

    public function getDetails(): array
    {
        $details = parent::getDetails();
        $details['expressFee'] = $this->expressFee;

        return $details;
    }

    public function printDetails(): void
    {
        echo "Express Order ID: {$this->id}\n";
        echo "Customer Name: {$this->customerName}\n";
        echo "Items: " . (empty($this->items) ? 'No items' : implode(', ', $this->items)) . "\n";
        echo "Express Fee: $" . number_format($this->expressFee, 2) . "\n";
        echo "Total Price: $" . number_format($this->totalPrice, 2) . "\n";
        echo "Status: {$this->status}\n";
    }
}

//Test
try {
    //Normal
    $order = new Order('Le Gia Le');
    $order->addItem('keyboard', 55.00);
    $order->addItem('mouse', 30.00);
    $order->changeStatus('shipped');
    $order->printDetails();

    //Express
    $expressOrder = new ExpressOrder('nguyen van minh');
    $expressOrder->addItem('Laptop', 1000.00);
    $expressOrder->addItem('Laptop case', 40.50);
    $expressOrder->applyExpressFee(15.99);
    $expressOrder->changeStatus('delivered');
    $expressOrder->printDetails();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}