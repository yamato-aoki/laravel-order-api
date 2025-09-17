<?php

/**
 * 出荷処理に関するコントローラー。
 *
 * エンドポイント一覧:
 * - POST /api/orders/{orderId}/shipments : 出荷登録
 */

namespace App\Http\Controllers;

// --- リクエスト ---
use App\Http\Requests\StoreShipmentRequest;

// --- サービス ---
use App\Services\OrderService;

class OrderShipmentController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * 出荷登録を行う（POST /api/orders/{orderId}/shipments）
     *
     * 指定された注文IDに対して出荷情報を登録する。
     */
    public function store(StoreShipmentRequest $request, $orderId)
    {
        $shipment = $this->orderService->ship($orderId, $request->validated());

        return response()->json([
            'message' => '出荷情報を登録しました。',
            'shipment' => $shipment,
        ], 201);
    }
}
