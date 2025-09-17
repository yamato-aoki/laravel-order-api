<?php

/**
 * 注文のステータスを定義するEnumクラス。
 *
 * @category Enum
 * @package  App\Enums
 */

namespace App\Enums;

/**
 * 注文のライフサイクルにおける状態（Pending → Confirmed → Paid → Shipped → Delivered）の
 * 各段階を表すEnum。
 *
 * DBの `orders.status` カラムと連動し、Controller・Service層での
 * 状態管理や条件分岐処理において明示的かつ安全に使用する。
 *
 * 使用例:
 * - `Order::where('status', OrderStatus::Confirmed)` のような判定
 * - 出荷可能かどうかの判定に `OrderStatus::Shipped` を用いる
 */
enum OrderStatus: string
{
    case Pending   = 'pending';    // 注文受付直後（未確定）
    case Confirmed = 'confirmed';  // 在庫確保後の確定状態
    case Paid      = 'paid';       // 支払い完了
    case Shipped   = 'shipped';    // 出荷済み
    case Delivered = 'delivered';  // 配送完了
    case Canceled  = 'canceled';   // キャンセル済み
}
