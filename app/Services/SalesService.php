<?php

namespace App\Services;

use App\Repositories\SalesRepository;
use App\Repositories\SaleItemsRepository;
use App\Repositories\ProductRepository;

class SalesService
{   

    protected $salesRepository;
    protected $saleItemsRepository;
    protected $productRepository;

    public function __construct(
        SalesRepository $salesRepository, 
        SaleItemsRepository $saleItemsRepository,
        ProductRepository $productRepository
    )
    {
      
       $this->salesRepository = $salesRepository;
       $this->saleItemsRepository = $saleItemsRepository;
       $this->productRepository = $productRepository;
    }

    public function createOrder(array $data)
    {
     
        $order = $this->salesRepository->create([
                'customer_id' => $data['customer_id'],
                'sale_date' => date('Y-m-d H:i:s'), 
                'total' => 0 
        ]);

        $totalAmount = 0;
      
        foreach ($data['items'] as $item) {
              
            $product = $this->productRepository->find($item['product_id']);
            if (!$product) {
                throw new \Exception("Produto com ID {$item['product_id']} não encontrado.");
            }

            $totalPrice = $product->price * $item['quantity'];
            $this->saleItemsRepository->create([
                'sale_id' => $order->id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $totalPrice
            ]);
                 
            $totalAmount += $totalPrice;
        }

        return $this->salesRepository->update($order->id, [
            'total' => $totalAmount
        ]);
    }

    public function getOrder(int $id)
    {
        return $this->salesRepository->find($id);
    }

    public function deleteOrder(int $id)
    {
        return $this->salesRepository->delete($id);
    }

    public function listOrders()
    {
        return $this->salesRepository->all();
    }
}
