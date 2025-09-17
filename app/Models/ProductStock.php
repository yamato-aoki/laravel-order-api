<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 商品の在庫情報を管理するモデルクラス。
 *
 * - 商品ごとの在庫数量（quantity）を保持
 * - Product モデルとの1対1のリレーションを持つ
 */
class ProductStock extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',  // 対応する商品のID
        'quantity',    // 在庫数
    ];

    /**
     * 在庫が紐づく商品情報（1対1リレーションの逆側）。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
