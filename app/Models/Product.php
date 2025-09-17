<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 商品情報を管理するモデルクラス。
 *
 * - SKU、商品名、価格、販売可否などを保持
 * - 在庫情報（product_stock）との1対1リレーション
 * - 注文明細（order_items）との1対多リレーション
 */
class Product extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性（Mass Assignment対象）。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'sku',        // 商品コード（SKU）
        'name',       // 商品名
        'price',      // 単価
        'is_active',  // 販売中フラグ（true = 販売中）
    ];

    /**
     * 商品が持つ在庫情報（1対1リレーション）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function productStock()
    {
        return $this->hasOne(ProductStock::class);
    }

    /**
     * 商品に紐づく注文明細（OrderItem）一覧（1対多）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
