<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * API専用のルートを定義するサービスプロバイダ。
 *
 * 本システムはフロントエンドを持たない「業務APIサーバー」として設計しているため、
 * Laravel標準の web.php は使用せず、api.php のみをロード対象とする。
 *
 * - すべてのルートに `api` ミドルウェアを適用
 * - プレフィックスとして `/api` を自動付与
 *
 * この構成により、APIエンドポイントの集約と、業務ロジックの明確化を図る。
 */
class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));
        });
    }
}
