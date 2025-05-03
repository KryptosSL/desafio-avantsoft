<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\StatisticsService;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    protected StatisticsService $statisticsService;
    
    public function __construct(StatisticsService $statisticsService)
    {
        $this->statisticsService = $statisticsService;
    }

    public function getCustomerMostVolume()
    {
        return $this->statisticsService->getCustomerMostVolume();
    }

    public function getCustomerMostAverageValue()
    {
        return $this->statisticsService->getCustomerAverageSale();
    }

    public function getCustomerFrequent()
    {
        return $this->statisticsService->getCustomerFrequent();
    }

    public function getTotalSalesPerDay()
    {
        return $this->statisticsService->getTotalSalesPerDay();
    }
    
}
