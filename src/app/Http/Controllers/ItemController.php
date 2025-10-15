<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $items = collect();
        $title = '商品一覧';

        if ($tab === 'mylist') {
            // マイリスト表示（認証必須）
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            // いいねした商品を取得
            $items = Auth::user()->likes()
                ->with(['user', 'categories'])
                ->orderBy('created_at', 'desc')
                ->paginate(12);
            $title = 'マイリスト';
        } else {
            // 全商品表示（自分が出品した商品は除外）
            $items = Item::with(['user', 'categories'])
                ->when(Auth::check(), function($query) {
                    $query->where('user_id', '!=', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->paginate(12);
        }

        return view('home', compact('items', 'title', 'tab'));
    }

    public function search(Request $request)
    {
        // 検索キーワードの取得
        $keyword = $request->get('keyword');

        // 商品の検索クエリを構築
        $query = Item::with(['user', 'categories']);

        // 検索キーワードが指定されている場合、商品名で部分一致検索
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // 認証済みユーザーの場合、自分が出品した商品を除外
        if (Auth::check()) {
            $query->where('user_id', '!=', Auth::id());
        }

        // 作成日時の降順で並び替え
        $items = $query->orderBy('created_at', 'desc')->paginate(12);

        return view('home', compact('items', 'keyword'));
    }
}