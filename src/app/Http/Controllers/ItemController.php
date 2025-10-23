<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Like;
use App\Models\Comment;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            $items = Item::whereHas('likes', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->with(['user', 'categories'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            $title = 'マイリスト';
        } else {
            // 全商品表示（自分が出品した商品は除外）
            $items = Item::with(['user', 'categories'])
                ->when(Auth::check(), function($query) {
                    $query->where('user_id', '!=', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('home', compact('items', 'title', 'tab'));
    }

    public function search(Request $request)
    {
        // 検索キーワードとタブの取得
        $keyword = $request->get('keyword');
        $tab = $request->get('tab');

        // 商品の検索クエリを構築
        $query = Item::with(['user', 'categories']);

        // 検索キーワードが指定されている場合、商品名で部分一致検索
        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        // マイリストタブが選択されている場合
        if ($tab === 'mylist') {
            if (!Auth::check()) {
                return redirect()->route('login');
            }
            // お気に入り商品のみを取得
            $query->whereHas('likes', function($q) {
                $q->where('user_id', Auth::id());
            });
        } else {
            // 認証済みユーザーの場合、自分が出品した商品を除外
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
        }

        // 作成日時の降順で並び替え
        $items = $query->orderBy('created_at', 'desc')->paginate(20);

        // $title変数を設定
        $title = $keyword ? "「{$keyword}」の検索結果" : '商品検索';
        if ($tab === 'mylist') {
            $title = $keyword ? "「{$keyword}」のお気に入り商品" : 'お気に入り商品';
        }

        return view('home', compact('items', 'keyword', 'title', 'tab'));
    }

    /**
     * 商品出品画面を表示
     */
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    /**
     * 商品出品処理
     */
    public function store(ExhibitionRequest $request)
    {
        // 画像のアップロード処理
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('images', 'public');
        }

        // 商品の作成
        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image_path' => $imagePath,
        ]);

        // カテゴリーの関連付け
        if ($request->categories) {
            $item->categories()->attach($request->categories);
        }

        return redirect()->route('home')->with('success', '商品を出品しました。');
    }

    /**
     * 商品詳細画面を表示
     */
    public function show(Item $item)
    {
        $item->load(['user', 'categories', 'comments.user']);
        
        // いいね数とコメント数を取得
        $likeCount = $item->likes()->count();
        $commentCount = $item->comments()->count();
        
        // 現在のユーザーがいいねしているかチェック
        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }
        
        return view('items.show', compact('item', 'likeCount', 'commentCount', 'isLiked'));
    }


    /**
     * いいね機能のトグル（フォーム送信版）
     */
    public function toggleLike(Request $request, Item $item)
    {
        // ログインチェック
        if (!Auth::check()) {
            return redirect()->route('login')->with('message', 'いいね機能を使用するにはログインが必要です。');
        }

        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
                   ->where('item_id', $item->id)
                   ->first();

        if ($like) {
            // いいねを解除
            $like->delete();
        } else {
            // いいねを追加
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        return redirect()->route('item.show', $item);
    }

    /**
     * コメント追加（フォーム送信版）
     */
    public function addComment(CommentRequest $request, Item $item)
    {
        Comment::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'comment' => $request->comment,
        ]);

        return redirect()->route('item.show', $item);
    }
}