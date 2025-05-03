<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SalesService;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function __construct(private SalesService $salesService)
    {
        //
    }

    public function newOrder(Request $request) {
        $this->salesService->createOrder($request->all());
    }

    public function showOrder($id) {
        $order = $this->salesService->getOrder($id);
        return response()->json($order);
    }

    public function listOrders() {
        $orders = $this->salesService->listOrders();
        return response()->json($orders);
    }
}
