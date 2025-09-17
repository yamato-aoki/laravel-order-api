<?php

/**
 * API認証用のコントローラ。
 * 
 * ユーザーの登録・ログイン・ログアウト処理を担い、
 * Sanctum を用いたトークンベース認証を実現する。
 */

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

// --- モデル ---
use App\Models\User;                         // app/Models/User.php

// --- Laravel組み込み ---
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * ユーザー登録（新規アカウント作成）
     *
     * @param Request $request 登録情報を含むHTTPリクエスト
     * @return \Illuminate\Http\JsonResponse アクセストークン付きレスポンス（201）
     */
    public function register(Request $request)
    {
        // バリデーション（名前、メール、パスワードをチェック）
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email', // メールは重複不可
            'password' => 'required|string|min:8|confirmed', // password_confirmation と一致必須
        ]);

        // ユーザー作成（パスワードはハッシュ化して保存）
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // アクセストークン発行（Sanctum）
        $token = $user->createToken('api_token')->plainTextToken;

        // トークンを含めてレスポンス（201 Created）
        return response()->json(['token' => $token], 201);
    }

    /**
     * ログイン処理（トークン発行）
     *
     * @param Request $request ログイン用メールアドレス・パスワードを含むリクエスト
     * @return \Illuminate\Http\JsonResponse アクセストークン or エラー
     */
    public function login(Request $request)
    {
        // 指定されたメールアドレスのユーザーを検索
        $user = User::where('email', $request->email)->first();

        // ユーザーが存在しない、またはパスワードが一致しない場合は認証失敗
        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // ✅ すでに発行されているトークンを全削除（シングルログイン）
        $user->tokens()->delete(); // トークン漏洩対策にも有効

        // 新たにトークンを発行
        $token = $user->createToken('api_token')->plainTextToken;

        // トークンを返却
        return response()->json(['token' => $token]);
    }

    /**
     * ログアウト（現在のアクセストークンを削除）
     *
     * @param Request $request 現在の認証済ユーザー
     * @return \Illuminate\Http\JsonResponse ログアウト完了メッセージ
     */
    public function logout(Request $request)
    {
        // 今のアクセストークンを削除 → ログアウト扱い
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
