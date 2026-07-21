<x-app-layout theme="knowledge">
    <div class="max-w-5xl mx-auto px-6 py-12">

        {{-- パンくず --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('knowledges.index') }}" class="hover:underline">Knowledge List</a>
            <span class="mx-1">&gt;</span> Knowledge Detail
        </nav>

        {{-- 見出し行：タイトル＋編集・削除アイコン --}}
        <div class="mt-2 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Knowledge Detail</h1>
            <div class="flex items-center gap-2">
                {{-- 編集（編集画面）へ ※editビュー作成後に接続 --}}
                <a href="{{ route('knowledges.edit', $knowledge) }}" title="編集" class="p-2 text-gray-600 hover:text-gray-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                        <path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                    </svg>
                </a>
                {{-- 削除（モーダルを開く） --}}
                <button type="button" title="削除" x-data="" x-on:click.prevent="$dispatch('open-modal','confirm-knowledge-deletion')" class="p-2 text-red-600 hover:text-red-400 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- 基本情報 --}}
        <section class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">基本情報</h2>
            <dl class="px-6 py-6 grid grid-cols-6 gap-x-6 gap-y-5 text-sm">
                <div class="col-span-6">
                    <dt class="text-gray-400">タイトル</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $knowledge->title }}</dd>
                </div>
                <div class="col-span-4">
                    <dt class="text-gray-400">参照元（本）</dt>
                    <dd class="mt-1">
                        <a href="{{ route('books.show', $knowledge->book) }}" class="text-secondary font-bold hover:underline">
                            {{ $knowledge->book->title }}
                        </a>
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">該当箇所</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $knowledge->book_page ?? '-' }}</dd>
                </div>
                <div class="col-span-6">
                    <dt class="text-gray-400">登録日</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $knowledge->created_at->format('Y-m-d') }}</dd>
                </div>
            </dl>
        </section>

        {{-- 本文情報 --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">本文情報</h2>
            <dl class="px-6 py-6 space-y-5 text-sm">
                <div>
                    <dt class="text-gray-400">内容</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $knowledge->content }}</dd>
                </div>
            </dl>
        </section>

        {{-- タグ --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">タグ</h2>
            <div class="px-6 py-6">
                @php $tags = array_filter([$knowledge->tag1, $knowledge->tag2, $knowledge->tag3]); @endphp
                @if (empty($tags))
                    <p class="text-sm text-gray-400">タグは登録されていません</p>
                @else
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($tags as $tag)
                            <span class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- 紐づく行動 --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-3 bg-action flex items-center justify-between">
                <h2 class="text-white font-bold">この知識から生まれた行動</h2>
            </div>
            @if ($knowledge->actions->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-500">まだ行動が登録されていません</p>
            @else
                <table class="w-full text-sm table-fixed">
                    <colgroup>
                        <col class="w-1/2">
                        <col class="w-27">
                    </colgroup>
                    <thead class="bg-orange-50 text-orange-700">
                        <tr>
                            <th class="px-6 py-3 text-left font-bold">タイトル</th>
                            <th class="px-6 py-3 text-left font-bold">該当箇所</th>
                            <th class="px-6 py-3 text-left font-bold">タグ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($knowledge->actions as $action)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">
                                    <a href="{{ route('actions.show', $action) }}" class="text-gray-700 font-medium hover:text-action hover:underline">
                                        {{ $action->title}}
                                    </a>
                                </td>
                                <td class="px-6 py-4 text-gray-600 whitespace-nowrap">{{ $action->book_page ? $action->book_page : '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach (array_filter([$action->tag1, $action->tag2, $action->tag3]) as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full border border-orange-200 bg-orange-50 text-orange-600 text-xs">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <div class="px-6 py-6 flex justify-center border-t border-gray-100">
                <a href="{{ route('actions.create',['from' => 'knowledge', 'book_id' => $knowledge->book_id, 'knowledge_id' => $knowledge->id]) }}" class="px-4 py-1.5 bg-action rounded-lg text-white text-sm font-bold hover:opacity-90 transition"> + New Action</a>
            </div>
        </section>

        {{-- 一覧へ戻る --}}
        <div class="mt-8">
            <a href="{{ route('knowledges.index') }}" class="text-sm text-secondary hover:underline">&larr; Knowledge Listに戻る</a>
        </div>

        {{-- 削除確認モーダル --}}
        <x-modal name="confirm-knowledge-deletion" focusable maxWidth="lg">
            <form action="{{ route('knowledges.destroy', $knowledge) }}" method="post" class="p-6">
                @csrf
                @method('delete')

                {{-- ヘッダー --}}
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span aria-hidden="true">🗑️</span> 削除していいですか？
                </h2>
                <p class="mt-2 text-sm text-gray-500">この操作は取り消せません。</p>

                {{-- 削除される内容 --}}
                <div class="mt-6  bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="font-bold text-red-600 flex items-center gap-2">
                        <span aria-hidden="true">⚠️</span> 削除される内容
                    </p>
                    <p class="mt-2 text-sm text-red-600">
                        タイトル：{{ $knowledge->title }}<br>
                        参照元　：{{ $knowledge->book->title }} / {{ $knowledge->book_page ?? ' - ' }}
                    </p>
                </div>
                {{-- 注意書き --}}
                <div class="mt-4 flex items-start gap-2 text-sm text-red-600">
                    <span aria-hidden="true">⚠️</span>
                    <p>この知識に紐づく行動は削除されません <br>（本に紐づく行動として残ります）</p>
                </div>

                {{-- フッター --}}
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700 transition">
                        Delete
                    </button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
