<?php

namespace App\Mail;

// --- メーラ関連 ---
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

// --- モデル ---
use App\Models\Order;

// --- メール内容定義 ---
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

/**
 * 注文ステータス変更通知メール。
 *
 * 注文の状態が更新された際に送信される通知メールで、
 * 購入者に対して新しいステータスと出荷情報（存在する場合）を伝える。
 */
class OrderStatusUpdatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @param Order $order ステータス変更対象の注文
     */
    public function __construct(public Order $order) {}

    /**
     * メールヘッダ情報（件名など）を定義
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '注文状況が変更されました',
        );
    }

    /**
     * メール本文のテンプレート設定（Markdown使用）
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_status_updated',
            with: [
                'order' => $this->order,
                'shipment' => $this->order->shipment, // 出荷情報が存在すれば渡す
            ],
        );
    }

    /**
     * 添付ファイルなし
     */
    public function attachments(): array
    {
        return [];
    }
}
