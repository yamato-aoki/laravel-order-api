<?php

/**
 * 注文作成リクエスト（POST /api/orders）用のバリデーションクラス。
 *
 * - 顧客ID、商品ID、数量などの入力チェックを行う。
 * - バリデーション失敗時は自動的に 422 Unprocessable Entity を返す。
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * 認可ロジック（Sanctum等で認証済みなら true にして通す）
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // 認証はグローバルミドルウェア側で制御する前提
        return true;
    }

    /**
     * 注文作成に必要な入力項目のバリデーションルール
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {

        return [
            // 顧客ID（必須・整数）
            'customer_id'          => 'required|integer',

            // 注文アイテム配列（必須・1件以上）
            'items'                => 'required|array|min:1',

            // 商品ID（各アイテムに対して必須・重複不可）
            'items.*.product_id'   => 'required|integer|distinct',

            // 数量（各アイテムに対して必須・1以上）
            'items.*.quantity'     => 'required|integer|min:1',
        ];
    }
}
