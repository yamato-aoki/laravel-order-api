<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\OrderStatus;

/**
 * 注文情報を管理するモデルクラス。
 *
 * - 顧客、注文商品、出荷情報と関連
 * - total_amount（合計金額）を再計算する機能も保持
 */
class Order extends Model
{
    use HasFactory;

    /**
     * 属性のキャスト定義。
     * status は Enum（OrderStatus）として扱う。
     *
     * @var array<string, string>
     */
    protected $casts = [
        'status' => OrderStatus::class,
    ];

    /**
     * 一括代入可能な属性（Mass Assignment対象）。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',        // 登録ユーザーID（省略可）
        'customer_id',    // 紐づく顧客ID
        'status',         // 注文ステータス
        'total_amount',   // 合計金額（自動再計算）
    ];

    /**
     * 注文に紐づく注文詳細一覧（1対多）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 注文に紐づく出荷情報（1対1）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function shipment()
    {
        return $this->hasOne(OrderShipment::class);
    }

    /**
     * 注文に紐づく顧客情報（多対1）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * 注文に紐づく注文詳細一覧（itemsと重複。統一の検討余地あり）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * 合計金額（total_amount）を再計算し、保存する。
     *
     * @return void
     */
    public function recalculateTotalAmount(): void
    {
        $this->loadMissing('orderItems');
        $this->total_amount = $this->orderItems->sum('subtotal');
        $this->save();
    }
}
