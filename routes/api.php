<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrderShipmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| API 専用のルーティング定義。
| Sanctum によるトークンベース認証を用いて、ログイン後のAPIのみ制限している。
|
| API の主な構成：
| - 認証（ログイン・ログアウト）
| - 注文作成・一覧・詳細・ステータス変更
| - 出荷登録（1注文 = 1出荷制約あり）
*/

/**
 * 🔓 認証不要ルート
 * ユーザーがアクセストークンを取得するためのエンドポイント。
 */
Route::post('/auth/login', [AuthController::class, 'login']);

/**
 * 🔐 認証保護ルート（auth:sanctum）
 * ログイン後のユーザーのみがアクセス可能な API 群。
 */
Route::middleware('auth:sanctum')->group(function () {

    /**
     * ログアウトAPI（トークン無効化）
     */
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    /**
     * 注文登録API
     * 注文情報と注文商品（items）を含むJSONでPOSTする。
     */
    Route::post('/orders', [OrdersController::class, 'store']);

    /**
     * 出荷登録API
     * 指定された注文IDに対して、出荷業者・追跡番号・出荷日時を登録。
     * - 各注文につき1つの出荷データのみ（DB制約あり）
     */
    Route::post('/orders/{order}/shipments', [OrderShipmentController::class, 'store']);

    /**
     * 注文ステータス変更API
     * 例: "canceled", "shipped" などをPUTリクエストで更新。
     */
    Route::put('/orders/{order}/status', [OrdersController::class, 'updateStatus']);

    /**
     * 注文一覧 / 詳細 / 更新 / 削除API（REST形式）
     * GET /orders         → 一覧取得（フィルタ可能）
     * GET /orders/{id}    → 詳細取得
     * PUT /orders/{id}    → 更新（未使用）
     * DELETE /orders/{id} → 削除（未使用）
     */
    Route::apiResource('orders', OrdersController::class);

    /**
     * 注文詳細取得API（apiResource に含まれるが、補足のため明示）
     */
    Route::get('/orders/{order}', [OrdersController::class, 'show']);
});
