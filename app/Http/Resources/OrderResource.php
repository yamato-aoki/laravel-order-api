<?php

/**
 * 注文データのレスポンス整形用 Resource クラス。
 *
 * モデルから取得したデータを、APIレスポンス形式に整形する。
 * - ネストされたリレーション（customer, items, shipment）も含めて出力。
 * - 金額は float でキャストし、API出力としての精度と可読性を確保。
 */

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * APIレスポンス用データ整形ロジック
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        return [
            'id'           => $this->id,
            'status'       => $this->status,
            'total_amount' => (float) $this->total_amount,

            // 顧客情報
            'customer'     => [
                'id'    => $this->customer->id,
                'name'  => $this->customer->name,
                'email' => $this->customer->email,
            ],

            // 商品情報（mapで個別変換）
            'items'        => $this->items->map(function ($item) {
                return [
                    'product_id'   => $item->product_id,
                    'product_name' => optional($item->product)->name, // productがnullでもエラー回避
                    'quantity'     => $item->quantity,
                    'unit_price'   => (float) $item->unit_price,
                    'subtotal'     => (float) $item->subtotal,
                ];
            }),

            // 出荷情報（存在する場合のみ）
            'shipment' => $this->shipment ? [
                'carrier'     => $this->shipment->carrier,
                'tracking_no' => $this->shipment->tracking_no,
            ] : null,

            // 作成日時（ISO 8601形式で返却）
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
