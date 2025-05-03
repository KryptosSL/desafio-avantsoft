<?php

namespace App\Services;

use App\Repositories\CustomerRepository;

class CustomerService
{
    protected CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function saveCustomer(array $data)
    {
        return $this->customerRepository->create($data);
    }

    public function getCustomer(int $id)
    {
        return $this->customerRepository->find($id);
    }

    public function updateCustomer(int $id, array $data)
    {
        return $this->customerRepository->update($id, $data);
    }

    public function deleteCustomer(int $id)
    {
        return $this->customerRepository->delete($id);
    }

    public function listCustomers(array $filters = [])
    {
        return $this->customerRepository->filter($filters);
    }
    
    public function getCustomerMostVolume()
    {
        $volume = SaleItem::select('sale_items.sale_id', DB::raw('SUM(sale_items.quantity * sale_items.price) as total_sales_value'))
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->groupBy('sales.customer_id')
            ->orderByDesc('total_sales_value')
            ->first();

        if ($volume) {
            $customer = Customer::find($volume->customer_id);
            return response()->json([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'total_sales_value' => $volume->total_sales_value,
            ]);
        }

        return response()->json([
            'message' => 'Nenhuma venda encontrada.',
        ]);
    }
}
