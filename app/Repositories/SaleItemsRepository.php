<?php

namespace App\Repositories;

use App\Models\SaleItem;
use Illuminate\Support\Facades\DB;

class SaleItemsRepository
{
    
    public function find(int $id)
    {
        return SaleItem::find($id);
    }

    public function create(array $data)
    {
        return SaleItem::create($data);
    }

    public function update(int $id, array $data)
    {
        $salesItem = SaleItem::find($id);
        return $salesItem ? $salesItem->update($data) : false;
    }

    public function delete(int $id)
    {
        return SaleItem::destroy($id);
    }

    public function getHighestVolume()
    {
        return SaleItem::select('sales.customer_id', DB::raw('SUM(sale_items.quantity * sale_items.price) as total_sales_value'))
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->groupBy('sales.customer_id')
            ->orderByDesc('total_sales_value')
            ->limit(1)
            ->first();
    }
   
    public function getAverageSale()
    {
        return SaleItem::select('sales.customer_id', DB::raw('AVG(sale_items.quantity * sale_items.price) as average_sale_value'))
            ->join('sales', 'sales.id', '=', 'sale_items.sale_id')
            ->groupBy('sales.customer_id')
            ->orderByDesc('average_sale_value')
            ->limit(1)
            ->first();
    }
}
