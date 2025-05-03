<?php

namespace App\Repositories;

use App\Models\Sales;
use Illuminate\Support\Facades\DB;

class SalesRepository
{
    
    public function find(int $id)
    {
        return Sales::find($id);
    }

    public function create(array $data)
    {
        return Sales::create($data);
    }

    public function update(int $id, array $data)
    {
        $sales = Sales::find($id);
        return $sales ? $sales->update($data) : false;
    }

    public function delete(int $id)
    {
        return Sales::destroy($id);
    }
    
    public function all()
    {
        return Sales::all();
    }

    public function getMostFrequent()
    {
        return Sales::select('customer_id', DB::raw('COUNT(DISTINCT DATE(sale_date)) as days'))
            ->groupBy('customer_id')
            ->orderByDesc('days')
            ->limit(1)
            ->first();
    }

    public function getTotalSalesPerDay()
    {
        return Sales::select(DB::raw('DATE(sale_date) as date'), DB::raw('SUM(total) as total_sales'))
            ->groupBy(DB::raw('DATE(sale_date)'))
            ->orderBy('date', 'asc')
            ->get();
    }
}
