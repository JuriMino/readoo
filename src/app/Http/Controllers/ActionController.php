<?php

namespace App\Http\Controllers;

use App\Models\Action;
use App\Models\Book;
use App\Models\Knowledge;
use App\Http\Requests\ActionRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class ActionController extends Controller
{
    use AuthorizesRequests;

    // 独立した行動一覧：自分の行動だけを表示
    public function index()
    {
        $this->authorize('viewAny', Action::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $actions = Action::whereHas('book', function($query) use($user){
            $query->where('user_id', $user->id);
        })->with(['book','knowledge'])->latest()->paginate(15);

        return view('actions.index',['actions' => $actions]);
    }

    // 作成フォーム：本・知識のプルダウン用データ＋入り口ごとの初期選択
    public function create()
    {
        $this->authorize('viewAny', Action::class);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $books = $user->books()->latest()->get();

        // Alpine連動用：自分の全知識（本ごとに絞り込むため book_id 付き）
        $knowledges = Knowledge::whereHas('book', function($query) use($user){
            $query->where('user_id',$user->id);
        })->get(['id','book_id','title']);

        return view('actions.create',[
            'books'               => $books,
            'knowledges'          => $knowledges,
            'selectedBookId'      => request('book_id'),    // 本詳細→知識登録からきた時固定
            'selectedKnowledgeId' => request('knowledge_id'), // 知識詳細からきた時固定
        ]);
    }

    // 登録
    public function store(ActionRequest $request)
    {
        $validated = $request->validated();

        // 参照元の本（exists ルールで自分の本に限定済み）
        $book = Book::findOrFail($validated['book_id']);
        $this->authorize('create',[Action::class, $book]);

        // book_idはリレーション経由で安全にセット。book_id/knowledge_idはFillable外なので除外
        $action = $book->actions()->create($request->safe()->except(['book_id','knowledge_id']));

        // 任意のknowledge_idは、検証済み（その本の知識）の時だけ明示的にセット
        if(! empty($validated['knowledge_id'])){
            $action->knowledge_id = $validated['knowledge_id'];
            $action->save();
        }

        // 「登録ボタンを押した画面」へ戻す
        $redirect = match($request->input('from')){
            'index'     => redirect()->route('actions.index'),
            'knowledge' => redirect()->route('knowledges.show', $action->knowledge_id),
            default     => redirect()->route('books.show', $book),
        };

        return $redirect->with('status','行動を登録しました');
    }

    /**
     * Display the specified resource.
     */
    public function show(Action $action)
    {
        $this->authorize('view', $action);

        return view('actions.show',['action' => $action]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Action $action)
    {
        $this->authorize('update', $action);

        return view('actions.edit', ['action' => $action]);
    }

    // 更新：参照元（本・知識）の付け替えはしない想定
    public function update(ActionRequest $request, Action $action)
    {
        $this->authorize('update', $action);

        $action->update($request->safe()->except(['book_id','knowledge_id']));

        return redirect()->route('actions.show', $action)->with('status','行動を更新しました');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Action $action)
    {
        $this->authorize('delete', $action);

        $book = $action->book; // 削除前に控える
        $action->delete();

        return redirect()->route('books.show', $book)->with('status','行動を削除しました');
    }
}
