<?php

namespace App\Services;

use App\Services\CustomerService; 
use App\Repositories\SaleItemsRepository;
 
use App\Repositories\SalesRepository;
use App\Models\SaleItem; 

class StatisticsService
{
    protected CustomerService $customerService;
    protected SaleItemsRepository $saleItemsRepository;
    protected SalesRepository $salesRepository;

    public function __construct(SalesRepository $salesRepository, CustomerService $customerService, SaleItemsRepository $saleItemsRepository)
    {
        $this->customerService = $customerService;
        $this->saleItemsRepository = $saleItemsRepository;
        $this->salesRepository =  $salesRepository;
    }
    
    public function getCustomerMostVolume()
    {
        $volume =  $this->saleItemsRepository->getHighestVolume();

        if ($volume) {
            $customer = $this->customerService->getCustomer($volume->customer_id);
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

    public function getCustomerAverageSale() {
        $average = $this->saleItemsRepository->getAverageSale();

        if ($average) {
            $customer = $this->customerService->getCustomer($average->customer_id);
            return response()->json([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'average_sale_value' => $average->average_sale_value,
            ]);
        }

        return response()->json([
            'message' => 'Nenhuma venda encontrada.',
        ]);
    }

    public function getCustomerFrequent() {
        
        $frequent = $this->salesRepository->getMostFrequent();
        if ($frequent) {
            $customer = $this->customerService->getCustomer($frequent->customer_id);
            return response()->json([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'days' => $frequent->days,
            ]);
        }
        
        return response()->json([
            'message' => 'Nenhuma venda encontrada.',
        ]);
    }

    public function getTotalSalesPerDay() {
        $sales = $this->salesRepository->getTotalSalesPerDay();
        if ($sales) {
            return response()->json($sales);
        }
        
        return response()->json([
            'message' => 'Nenhuma venda encontrada.',
        ]);
    }

}
