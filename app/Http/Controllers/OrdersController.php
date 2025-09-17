<?php

/**
 * 注文に関するコントローラークラス。
 *
 * 対応するAPIエンドポイント:
 * - GET    /api/orders         : 注文一覧の取得
 * - GET    /api/orders/{id}    : 単一注文の取得
 * - POST   /api/orders         : 新規注文の作成
 * - PUT    /api/orders/{id}/status    : 注文ステータスの更新
 */

namespace App\Http\Controllers;

// --- モデル ---
use App\Models\Order;

// --- リポジトリ ---
use App\Repositories\OrderRepository;

// --- サービス ---
use App\Services\OrderService;

// --- リクエスト ---
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Http\Requests\OrderIndexRequest;

// --- リソース ---
use App\Http\Resources\OrderResource;

// --- その他 ---
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class OrdersController extends Controller
{
    protected OrderService $service;
    protected OrderRepository $orderRepository;

    public function __construct(
        OrderService $service,
        OrderRepository $orderRepository
    ) {
        $this->service = $service;
        $this->orderRepository = $orderRepository;
    }

    /**
     * 注文一覧を取得（GET /api/orders）
     */
    public function index(OrderIndexRequest $request)
    {
        [$orders, $meta] = $this->orderRepository->paginateWithFilters($request->all());
        $orders->load(['customer', 'items.product', 'shipment']);

        return response()->json(
            OrderResource::collection($orders)->additional(['meta' => $meta])
        );
    }

    /**
     * 単一注文を取得（GET /api/orders/{id}）
     */
    public function show(Order $order): JsonResponse
    {
        return response()->json(new OrderResource($order));
    }

    /**
     * 新規注文を作成（POST /api/orders）
     */
    public function store(StoreOrderRequest $request)
    {
        $validated = $request->validated();
        Log::debug('バリデーション後のデータ:', $validated);

        $order = $this->service->createOrder($validated);
        return response()->json($order, 201);
    }

    /**
     * 注文ステータスを更新（PUT /api/orders/{id}/status）
     */
    public function updateStatus(UpdateOrderStatusRequest $request, Order $order)
    {
        $updatedOrder = $this->service->updateStatus($order, $request->validated());

        return response()->json([
            'message' => '注文ステータスを更新しました',
            'order'   => $updatedOrder,
        ]);
    }
}
