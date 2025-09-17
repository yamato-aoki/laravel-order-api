@component('mail::message')
# 注文情報のステータスが変更されました。

{{ $order->customer->name }} 様

ご注文（注文番号: {{ $order->id }}）について

@component('mail::panel')
@switch($order->status)
@case('pending')
現在、お支払い待ちの状態です。
お支払いが確認され次第、出荷準備を開始いたします。
@break

@case('paid')
お支払いが確認されました。
現在、出荷準備中です。
@break

@case('shipped')
商品が出荷されました。
まもなくお手元に届く予定です。
@break

@case('canceled')
キャンセルされました。
またのご利用をお待ちしております。
@break

@default
注文ステータスが更新されました。
詳細はマイページなどでご確認ください。
@endswitch
@endcomponent

更新日時：{{ now()->format('Y年m月d日 H:i') }}

引き続きよろしくお願いいたします。

@include('emails._footer')
@endcomponent