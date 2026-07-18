<x-app-layout>
    <div class="max-w-8xl mx-auto px-6 py-12">

        {{-- 見出し行・左にタイトル+New Action、右に他コレクション導線 --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Action List</h1>
                <p class="mt-2 text-gray-500">読書から生まれた行動を記録し、習慣につなげましょう</p>
                <a href="{{ route('actions.create') }}" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition"> + New Action</a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('books.index') }}" class="inline-block mt-4 px-5 py-2 bg-book rounded-lg text-white font-bold hover:opacity-90 transition">Book List</a>
                <a href="{{ route('knowledges.index') }}" class="inline-block mt-4 px-5 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition">Knowledge List</a>
            </div>
        </div>

        {{-- フラッシュメッセージ --}}
        @if (session('status'))
            <div class="mt-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if ($actions->isEmpty())
            {{-- 空状態 --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl px-6 py-16 text-center">
                <p class="text-gray-500">まだ行動が登録されていません</p>
                <a href="{{ route('actions.create')}}" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">最初の行動を登録する</a>
            </div>
        @else
            {{-- 件数行 + テーブル + ページネーションを１つのカードに --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">

                {{-- 件数表示 --}}
                <div class="px-5 py-4 text-sm text-gray-500 border-b border-gray-100">
                    全 {{ $actions->total() }} 件中 {{ $actions->firstItem() }} 〜 {{ $actions->lastItem() }} 件を表示
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <x-sort-header column="created_at" label="登録日" :sort="$sort" :direction="$direction" />
                            <x-sort-header column="title" label="タイトル" :sort="$sort" :direction="$direction" />
                            <x-sort-header column="book" label="参照元" :sort="$sort" :direction="$direction" />
                            <th class="px-5 py-3 text-left font-bold">該当箇所</th>
                            <th class="px-5 py-3 text-left font-bold">タグ</th>
                            <th class="px-5 py-3 text-left font-bold">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($actions as $action)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 whitespace-nowrap">{{ $action->created_at->format('Y-m-d') }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $action->title }}</td>
                                <td class="px-5 py-4  text-gray-900">{{ $action->book->title }}</td>
                                <td class="px-5 py-4 text-gray-900">{{ $action->book_page ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach (array_filter([$action->tag1, $action->tag2, $action->tag3]) as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full border border-orange-200 bg-orange-50 text-orange-600 text-xs">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('actions.show', $action) }}" class="text-gray-700 font-medium hover:text-action hover:underline">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ページネーション --}}
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $actions->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
