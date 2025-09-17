@component('mail::message')
# ご注文商品を出荷しました

{{ $order->customer->name }} 様

ご注文の商品を出荷いたしました。

@component('mail::panel')
**注文番号：** {{ $order->id }}
**配送業者：** {{ $shipment->carrier }}
**追跡番号：** {{ $shipment->tracking_no }}
**出荷日時：** {{ $shipment->shipped_at->format('Y年m月d日 H:i') }}
@endcomponent

配送状況は、各配送業者のWebサイトよりご確認いただけます。

@component('mail::button', ['url' => $shipment->tracking_url ?? '#' ])
配送状況を確認する
@endcomponent

ご利用ありがとうございました。

@include('emails._footer')
@endcomponent