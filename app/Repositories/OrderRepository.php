<?php

namespace App\Repositories;

use App\Models\Order;

/**
 * 注文データの取得処理をカプセル化したリポジトリクラス。
 *
 * 主に検索・ソート・ページネーション付きの一覧取得に対応し、
 * コントローラーからのDB依存を排除して疎結合を実現する。
 */
class OrderRepository
{
    /**
     * フィルタ条件に基づいた注文一覧をページネーション形式で取得する。
     *
     * @param array $filters 検索・絞り込み・ソート・ページサイズの指定
     * @return array [注文データ(Paginator), ページネーション情報(array)]
     */
    public function paginateWithFilters(array $filters): array
    {
        // ベースクエリ生成（Eloquentのスコープがあれば適用可能）
        $query = Order::query();

        // ステータスによる絞り込み
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 顧客名 or メールアドレスによる曖昧検索
        if (!empty($filters['q'])) {
            $query->whereHas('customer', function ($q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['q'] . '%')
                    ->orWhere('email', 'like', '%' . $filters['q'] . '%');
            });
        }

        // 日付範囲（開始日）による絞り込み
        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        // 日付範囲（終了日）による絞り込み
        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        // 並び替え条件の定義（match式で読みやすく）
        $sort = match ($filters['sort'] ?? null) {
            'created_at'        => ['created_at', 'asc'],
            '-created_at'       => ['created_at', 'desc'],
            'total_amount'      => ['total_amount', 'asc'],
            '-total_amount'     => ['total_amount', 'desc'],
            default             => ['id', 'desc'], // デフォルトは新しい順
        };

        $query->orderBy(...$sort);

        // ページネーション（デフォルトは10件）
        $perPage = $filters['per_page'] ?? 10;
        $orders = $query->paginate($perPage);

        // ページネーション情報も返却（APIで便利）
        return [$orders, [
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
            'total'        => $orders->total(),
        ]];
    }
}
