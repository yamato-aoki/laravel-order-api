<?php

/**
 * 注文ステータス更新リクエスト（PUT /api/orders/{id}）用のバリデーション定義。
 *
 * - 許可されたステータス値への更新のみを許容。
 * - 予期しない値をはじくことで、データの整合性を担保。
 * - 422エラー時にはカスタムメッセージで原因を明示。
 */

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    /**
     * 認可ロジック（今回は誰でも通す）
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Sanctumログイン済み前提。必要に応じてGate/Policyなどで制御可。
        return true;
    }

    /**
     * バリデーションルール定義
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in(['pending', 'paid', 'shipped', 'canceled']), // 必要に応じて追加
            ],
        ];
    }

    /**
     * バリデーションエラー時のカスタムメッセージ
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'status.required' => 'ステータスは必須です。',
            'status.in'       => 'ステータスの値が不正です（pending, paid, shipped, canceled のいずれかを指定してください）。',
        ];
    }
}
