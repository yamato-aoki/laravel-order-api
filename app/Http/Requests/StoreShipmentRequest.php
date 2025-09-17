<?php

/**
 * 出荷情報登録リクエスト（POST /api/orders/{id}/shipment）用のバリデーションクラス。
 *
 * - 配送業者名や追跡番号など、出荷に関する任意入力を扱う。
 * - 未入力でも通るが、入力されていれば型・長さの制約をかける。
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreShipmentRequest extends FormRequest
{
    /**
     * 認可ロジック
     * 
     * Sanctumなどでログイン済みを前提とし、ここでは常に true を返す。
     * 認可が必要な場合は Gate/Policy または Controller 側で制御する。
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * 出荷登録におけるバリデーションルールの定義
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // 配送業者名（任意・文字列・最大255文字）
            'carrier'     => 'nullable|string|max:255',

            // 追跡番号（任意・文字列・最大255文字）
            'tracking_no' => 'nullable|string|max:255',
        ];
    }
}
