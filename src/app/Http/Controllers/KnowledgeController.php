<?php

namespace App\Http\Controllers;

use App\Models\Knowledge;
use App\Models\Book;
use App\Http\Requests\KnowledgeRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class KnowledgeController extends Controller
{
    use AuthorizesRequests;

    // 独立した知識一覧：本をまたいで「自分の知識」だけを表示
    public function index(Request $request)
    {
        $this->authorize('viewAny', Knowledge::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sortMap =[
            'created_at' => 'knowledges.created_at',
            'title' => 'knowledges.title',
            'book' => 'books.title', // 参照元
        ];

        $sortKey = array_key_exists($request->query('sort'), $sortMap) ? $request->query('sort') : 'created_at';
        $sortColumn = $sortMap[$sortKey];
        $direction = $request->query('direction') === 'asc' ? 'asc' : 'desc';

        $knowledges = Knowledge::query()
            ->join('books', 'knowledges.book_id', '=', 'books.id')
            ->where('books.user_id', $user->id)
            ->whereNull('books.deleted_at') // 論理削除された書籍を除外
            ->with('book')
            ->select('knowledges.*')
            ->orderBy($sortColumn, $direction)
            ->paginate(10)
            ->withQueryString();

        return view('knowledges.index',[
            'knowledges' => $knowledges,
            'sort'       => $sortKey,
            'direction'  => $direction,
            ]);
    }

    // 作成フォーム：本のプルダウン用に自分の本を渡す
    public function create()
    {
        $this->authorize('viewAny', Knowledge::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        // 他ユーザーの本は選択肢には出さない
        $books = $user->books()->latest()->get();

        return view('knowledges.create',[
            'books'          => $books,
            'selectedBookId' => request('book_id'),  // 本の詳細からきたときは固定
        ]);

    }

    // 登録
    public function store(KnowledgeRequest $request)
    {
        $validated = $request->validated();

        // フォームで選ばれた本（exists ルールで自分の本に限定済み）
        $book = Book::findOrFail($validated['book_id']);
        $this->authorize('create', [Knowledge::class, $book]);

        // book_idはFillableに入れず、リレーション経由で安全にセット
        $book->knowledges()->create($request->safe()->except('book_id'));

        // 登録ボタンを押した画面へ戻す
        $redirect = $request->input('from') === 'index' ? redirect()->route('knowledges.index') : redirect()->route('books.show', $book);

        return $redirect->with('status', '知識を登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Knowledge $knowledge)
    {
        $this->authorize('view', $knowledge);

        // 紐づく行動を新しい順で先読み（ビューでのN+1を防ぐ）
        $knowledge->load(['actions' => fn($query)=> $query->latest()]);

        return view('knowledges.show',['knowledge' => $knowledge]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Knowledge $knowledge)
    {
        $this->authorize('update', $knowledge);

        return view('knowledges.edit', ['knowledge' => $knowledge]);
    }

    // 更新：本の付け替えはしない想定（book_idは固定）
    public function update(KnowledgeRequest $request, Knowledge $knowledge)
    {
        $this->authorize('update', $knowledge);

        $knowledge->update($request->safe()->except('book_id'));

        return redirect()->route('knowledges.show', $knowledge)->with('status','知識を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Knowledge $knowledge)
    {
        $this->authorize('delete', $knowledge);

        $book = $knowledge->book; //削除前にを控える
        $knowledge->delete();

        return redirect()->route('books.show', $book)->with('status', '知識を削除しました');
    }
}
