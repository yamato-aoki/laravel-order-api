<?php

/**
 * 出荷完了時に送信される通知メールクラス。
 *
 * 注文に対する出荷が完了した際に、顧客へメール通知を送信する。
 * - Laravelの Mailable を用いて、Markdownテンプレートで本文を整形
 * - 注文情報（Order）と出荷情報（OrderShipment）をビューに渡す
 */

namespace App\Mail;

// --- モデル ---
use App\Models\Order;
use App\Models\OrderShipment;
use Illuminate\Bus\Queueable;

// --- メーラ関連 ---
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderShipmentMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Order $order 注文情報
     * @param OrderShipment $shipment 出荷情報
     */
    public function __construct(
        public Order $order,
        public OrderShipment $shipment
    ) {}

    /**
     * メールの件名などのメタ情報を定義
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ご注文の出荷が完了しました',
        );
    }

    /**
     * メール本文に使用するテンプレートとデータを指定
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_shipment',
            with: [
                'order' => $this->order,
                'shipment' => $this->shipment,
            ],
        );
    }

    /**
     * 添付ファイル（今回はなし）
     */
    public function attachments(): array
    {
        return [];
    }
}
