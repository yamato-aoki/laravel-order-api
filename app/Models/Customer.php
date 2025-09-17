<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * 顧客情報を管理するモデルクラス。
 *
 * 注文管理システムにおける顧客の基本属性を保持し、
 * 注文（orders）との1対多のリレーションを定義する。
 */
class Customer extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可する属性。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',     // 顧客名
        'email',    // メールアドレス
        'phone',    // 電話番号
        'address',  // 住所
    ];

    /**
     * 顧客が持つ注文一覧のリレーション定義。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
