<?php

/**
 * 注文一覧取得（GET /api/orders）時のバリデーションルール。
 *
 * 検索条件・ソート・ページネーションなどのクエリパラメータを検証する。
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderIndexRequest extends FormRequest
{
    /**
     * 認可ロジック（今回は常に許可）
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // デフォルトの false だと 403 が返るため、true にして全リクエストを許可
        return true;
    }

    /**
     * バリデーションルールの定義
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            // 注文ステータス（未指定も許容）
            'status'     => ['nullable', 'in:pending,confirmed,shipped,canceled'],

            // 顧客名・メールによる検索用キーワード（最大100文字）
            'q'          => ['nullable', 'string', 'max:100'],

            // 日付範囲（from / to）
            'date_from'  => ['nullable', 'date'],
            'date_to'    => ['nullable', 'date', 'after_or_equal:date_from'],

            // 並び順（created_at や total_amount の昇順・降順）
            'sort'       => ['nullable', 'in:created_at,-created_at,total_amount,-total_amount'],

            // ページネーション（最大100件まで取得可能）
            'per_page'   => ['nullable', 'integer', 'min:1', 'max:100'],
        ];
    }
}
