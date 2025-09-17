<?php

namespace Tests\Feature;

use App\Mail\OrderShipmentMail;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductStock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

//  [テスト対象の定数定義]：可読性＆再利用性のため定数を使っている
const INITIAL_STOCK = 10;
const ORDER_QUANTITY = 2;
const EXPECTED_STOCK_AFTER_ORDER = INITIAL_STOCK - ORDER_QUANTITY;
const DUMMY_CARRIER = 'yamato';
const DUMMY_TRACK_NO = 'TRK-12345678';

class OrderFlowTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  注文フローの統合テスト
     * - 注文作成 → キャンセル → 出荷登録
     * - 在庫増減の確認
     * - 出荷時のメール送信確認
     */
    public function test_order_flow_create_cancel_ship()
    {
        Mail::fake(); // メール送信をフェイクに切り替え（実送信せず検証のみ）

        //  Sanctum認証ユーザーを作成し、API認証状態にする
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        // 注文に必要なデータを準備（顧客・商品・在庫）
        $customer = Customer::factory()->create();
        $product = Product::factory()->create();

        // 初期在庫 = 10 として登録
        ProductStock::factory()->create([
            'product_id' => $product->id,
            'quantity' => INITIAL_STOCK,
        ]);

        // === 1. 注文作成処理 =======================
        $res = $this->postJson('/api/orders', [
            'customer_id' => $customer->id,
            'items' => [[
                'product_id' => $product->id,
                'quantity' => ORDER_QUANTITY,
            ]]
        ]);

        $res->assertStatus(201); // 成功ステータス確認
        $orderId = $res->json('id'); // 作成された注文IDを取得

        // 🧾 在庫が減っているかを確認（10 → 8）
        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'quantity' => EXPECTED_STOCK_AFTER_ORDER,
        ]);

        // === 2. 注文キャンセル処理 =================
        $res = $this->putJson("/api/orders/{$orderId}/status", [
            'status' => 'canceled',
        ]);
        $res->assertStatus(200); // キャンセル成功
        $this->assertEquals('canceled', $res->json('order.status')); // ステータスが更新されているか

        //  在庫が元に戻っているかを確認（8 → 10）
        $this->assertDatabaseHas('product_stocks', [
            'product_id' => $product->id,
            'quantity' => INITIAL_STOCK,
        ]);

        // === 3. 出荷登録処理 =====================
        // 事前にステータスを「paid」に変更（出荷可能状態）
        Order::find($orderId)->update(['status' => 'paid']);

        $res = $this->postJson("/api/orders/{$orderId}/shipments", [
            'carrier' => DUMMY_CARRIER,
            'tracking_no' => DUMMY_TRACK_NO,
        ]);
        $res->assertStatus(201); // 出荷登録成功

        // 出荷通知メールが送信されたか確認
        Mail::assertQueued(OrderShipmentMail::class);
    }
}
