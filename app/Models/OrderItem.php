<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 注文商品（注文の明細）を表すモデルクラス。
 *
 * - 注文(Order)と商品(Product)に紐づく
 * - 単価・数量・小計（subtotal）などを保持
 */
class OrderItem extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性（Mass Assignment対象）。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',     // 紐づく注文ID
        'product_id',   // 紐づく商品ID
        'quantity',     // 注文数量
        'unit_price',   // 単価（商品ごとの価格）
        'subtotal',     // 小計（unit_price × quantity）
    ];

    /**
     * 親の注文へのリレーション（多対1）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 紐づく商品へのリレーション（多対1）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
