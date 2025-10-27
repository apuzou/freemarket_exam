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
    // 商品一覧表示（通常表示・マイリスト表示）
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $items = collect();
        $title = '商品一覧';

        if ($tab === 'mylist') {
            if (Auth::guest()) {
                return redirect()->route('login');
            }
            $items = Item::whereHas('likes', function($query) {
                $query->where('user_id', Auth::id());
            })
            ->join('likes', 'items.id', '=', 'likes.item_id')
            ->where('likes.user_id', Auth::id())
            ->with(['user', 'categories'])
            ->orderBy('likes.created_at', 'desc')
            ->select('items.*')
            ->paginate(20);
            $title = 'マイリスト';
        } else {
            $items = Item::with(['user', 'categories'])
                ->when(Auth::check(), function($query) {
                    $query->where('user_id', '!=', Auth::id());
                })
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('home', compact('items', 'title', 'tab'));
    }

    // 商品検索（キーワード検索・マイリスト検索）
    public function search(Request $request)
    {
        $keyword = $request->get('keyword');
        $tab = $request->get('tab');

        $query = Item::with(['user', 'categories']);

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        }

        if ($tab === 'mylist') {
            if (Auth::guest()) {
                return redirect()->route('login');
            }
            $query->whereHas('likes', function($q) {
                $q->where('user_id', Auth::id());
            })
                ->join('likes', 'items.id', '=', 'likes.item_id')
                ->where('likes.user_id', Auth::id())
                ->orderBy('likes.created_at', 'desc')
                ->select('items.*');
        } else {
            if (Auth::check()) {
                $query->where('user_id', '!=', Auth::id());
            }
            $query->orderBy('created_at', 'desc');
        }

        $items = $query->paginate(20);

        $title = $keyword ? "「{$keyword}」の検索結果" : '商品検索';
        if ($tab === 'mylist') {
            $title = $keyword ? "「{$keyword}」のお気に入り商品" : 'お気に入り商品';
        }

        return view('home', compact('items', 'keyword', 'title', 'tab'));
    }

    // 商品出品画面表示
    public function create()
    {
        $categories = Category::all();
        return view('items.sell', compact('categories'));
    }

    // 商品出品処理
    public function store(ExhibitionRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('product_image')) {
            $imagePath = $request->file('product_image')->store('images', 'public');
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'brand' => $request->brand,
            'description' => $request->description,
            'price' => $request->price,
            'condition' => $request->condition,
            'image_path' => $imagePath,
        ]);

        if ($request->categories) {
            $item->categories()->attach($request->categories);
        }

        return redirect()->route('home')->with('success', '商品を出品しました。');
    }

    // 商品詳細表示
    public function show(Item $item)
    {
        $item->load(['user', 'categories', 'comments.user']);

        $likeCount = $item->likes()->count();
        $commentCount = $item->comments()->count();

        $isLiked = false;
        if (Auth::check()) {
            $isLiked = $item->likes()->where('user_id', Auth::id())->exists();
        }

        return view('items.show', compact('item', 'likeCount', 'commentCount', 'isLiked'));
    }

    // いいね登録/解除
    public function toggleLike(Request $request, Item $item)
    {
        if (Auth::guest()) {
            return redirect()->route('login')->with('message', 'いいね機能を使用するにはログインが必要です。');
        }

        $user = Auth::user();
        $like = Like::where('user_id', $user->id)
                    ->where('item_id', $item->id)
                    ->first();

        if ($like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
            ]);
        }

        return redirect()->route('item.show', $item);
    }

    // コメント投稿
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