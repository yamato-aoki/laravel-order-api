<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Enums\ShippingCarrier;

/**
 * 出荷情報を管理するモデルクラス。
 *
 * - 注文(Order)に対して1対1の関係で紐づく
 * - 発送業者（carrier）・追跡番号（tracking_no）などを保持
 */
class OrderShipment extends Model
{
    use HasFactory;

    /**
     * 属性の型変換設定。
     * - carrier を Enum（ShippingCarrier）として扱う
     *
     * @var array<string, string>
     */
    protected $casts = [
        'carrier' => ShippingCarrier::class,
    ];

    /**
     * 一括代入を許可する属性（Mass Assignment対象）。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',      // 紐づく注文ID
        'carrier',       // 発送業者（Enum）
        'tracking_no',   // 追跡番号（任意）
        'shipped_at',    // 出荷日時
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
}
