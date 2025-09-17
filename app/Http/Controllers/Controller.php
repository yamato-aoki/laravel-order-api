<?php

/**
 * アプリケーション全体のControllerのベースクラス。
 * 
 * Laravelの組み込み機能である以下のトレイトを継承し、
 * 各コントローラで共通的に利用可能とする。
 *
 * - AuthorizesRequests: 認可（Gate/Policy）機能を利用可能にする
 * - ValidatesRequests: リクエストバリデーション機能を簡易に呼び出せるようにする
 *
 * このクラスは直接使うことは少なく、全てのコントローラの親として機能する。
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
