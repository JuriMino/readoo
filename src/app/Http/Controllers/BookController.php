<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\BookRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Http\Request;
class BookController extends Controller
{
    use AuthorizesRequests; // $this->authorize()を使えるようにする
    
    public function index(Request $request)
    {
        // viewAnyポリシーで認可チェック
        $this->authorize('viewAny', Book::class);

        // ホワイトリスト照合（インジェクション対策）
        $sortable = ['title','author','publisher','genre','status','started_at','created_at'];
        $sort = in_array($request->query('sort'), $sortable, true) ? $request->query('sort') : 'created_at';
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $books = $user->books()
            ->orderBy($sort, $direction)
            ->paginate(10)
            ->withQueryString(); // ページ送りでソート条件を維持

        return view('books.index', [
            'books' => $books,
            'sort' => $sort,
            'direction' => $direction,
            ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Book::class);

        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(BookRequest $request)
    {
        $this->authorize('create', Book::class);

        // BookRequestで検証済み
        $validated = $request->validated();

        // user_idは入力値ではなく、リレーション経由でサーバー側がセットする
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->books()->create($validated);

        return redirect()->route('books.index')->with('status', '書籍を登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $this->authorize('view', $book);

        // 紐づく知識・行動を新しい順で先読み（ビューでのN+1を防ぐ）
        $book->load([
            'knowledges' => fn($query) => $query->latest(),
            'actions' => fn($query) => $query->latest()
            ]);

        return view('books.show',['book' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $this->authorize('update', $book);

        return view('books.edit',['book' => $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(BookRequest $request, Book $book)
    {
        $this->authorize('update', $book);

        // BookRequestで検証済み
        $validated = $request->validated();

        $book->update($validated);

        return redirect()->route('books.show', $book)->with('status', '書籍を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $this->authorize('delete', $book);

        $book->delete(); // softDeletesなのでdeleted_atが入るだけ（物理削除ではない）

        return redirect()->route('books.index')->with('status', '書籍を削除しました');
    }
}
