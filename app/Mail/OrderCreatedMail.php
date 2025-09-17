<?php

/**
 * 注文完了時に送信される通知メールクラス。
 *
 * Laravelの Mailable を継承し、注文内容を含むMarkdownメールを送信する。
 * - 件名や本文テンプレートを定義（envelope / content）
 * - 引数として渡された Order モデルの情報をメールに展開
 */

namespace App\Mail;

// --- モデル ---
use App\Models\Order;

// --- メーラ関連 ---
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * 注文情報（Orderモデル）
     */
    public function __construct(public Order $order) {}

    /**
     * メールのヘッダー情報（件名など）を定義
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'ご注文を受け付けました'
        );
    }

    /**
     * メール本文のテンプレートとデータを定義
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.order_created',
            with: [
                'order' => $this->order,
            ]
        );
    }

    /**
     * 添付ファイル（今回は無し）
     */
    public function attachments(): array
    {
        return [];
    }
}
