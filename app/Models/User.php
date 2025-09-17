<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;

/**
 * アプリケーションユーザー（ログインアカウント）を管理するモデルクラス。
 *
 * - Laravel標準のAuthenticatableを継承し、ログイン認証の対象となる
 * - SanctumによるAPIトークン認証に対応
 * - ユーザーは複数の注文（orders）を持つ
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * 一括代入を許可する属性。
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',      // ユーザー名
        'email',     // メールアドレス
        'password',  // パスワード（ハッシュ化前でも受け取る）
    ];

    /**
     * レスポンスや配列変換時に隠す属性。
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',  // セキュリティ上非公開
    ];

    /**
     * 自動キャスト設定。
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * ユーザーが持つ注文一覧のリレーション定義。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * パスワードをセットするときに自動でハッシュ化するミューテータ。
     *
     * @param string $value プレーンパスワードまたはハッシュ済みパスワード
     * @return void
     */
    public function setPasswordAttribute($value)
    {
        if ($value === null) return;

        // すでにハッシュされている場合は再ハッシュしない
        if (Hash::needsRehash($value)) {
            $this->attributes['password'] = Hash::make($value);
        } else {
            $this->attributes['password'] = $value;
        }
    }
}
