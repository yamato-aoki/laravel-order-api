<?php

/**
 * 出荷キャリア（配送業者）を定義するEnumクラス。
 *
 * @category Enum
 * @package  App\Enums
 */

namespace App\Enums;

/**
 * 出荷時に使用される配送業者を列挙し、コード値（英字）と表示用ラベルを対応付ける。
 *
 * - フロントや管理画面でキャリアを選択する際に使用
 * - DBに保存する値（例：'yamato'）と表示名（例：'ヤマト運輸'）を分離することで保守性を高める
 *
 * 使用例:
 * - 出荷登録時に `ShippingCarrier::Yamato->value` を保存
 * - 表示時に `ShippingCarrier::Yamato->label()` を使用
 */
enum ShippingCarrier: string
{
    case Yamato     = 'yamato';      // ヤマト運輸
    case Sagawa     = 'sagawa';      // 佐川急便
    case JapanPost  = 'japan_post';  // 日本郵便
    case Other      = 'other';       // その他

    /**
     * キャリア名の日本語ラベルを返す。
     *
     * @return string 表示用のキャリア名称
     */
    public function label(): string
    {
        return match ($this) {
            self::Yamato    => 'ヤマト運輸',
            self::Sagawa    => '佐川急便',
            self::JapanPost => '日本郵便',
            self::Other     => 'その他',
        };
    }
}
