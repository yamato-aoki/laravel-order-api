@component('mail::message')
# ご注文ありがとうございます！

{{ $order->customer->name }} 様

ご注文（注文番号: {{ $order->id }}）を受け付けました。

@component('mail::panel')
**合計金額:** ¥{{ number_format($order->total_amount) }}
**ご注文日:** {{ $order->created_at->format('Y-m-d') }}
@endcomponent

今後の配送情報などは、改めてメールにてご連絡いたします。

それでは商品到着まで今しばらくお待ちくださいませ。

@include('emails._footer')
@endcomponent