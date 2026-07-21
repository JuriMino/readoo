<x-app-layout theme="knowledge">
    <div class="max-w-8xl mx-auto px-6 py-12">

        {{-- 見出し行・左にタイトル＋New Knowledge、右に他コレクション導線 --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Knowledge List</h1>
                <p class="mt-2 text-gray-500">本から得た知識を記録し、行動につなげよう</p>
                <a href="{{ route('knowledges.create',['from' => 'index']) }}" class="inline-block mt-4 px-5 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition"> + New Knowledge</a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('books.index')}}" class="inline-block mt-4 px-5 py-2 bg-book rounded-lg text-white font-bold hover:opacity-90 transition">Book List</a>
                <a href="{{ route('actions.index')}}" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">Action List</a>
            </div>
        </div>

        {{-- フラッシュメッセージ --}}
        @if (session('status'))
            <div class="mt-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        @if ($knowledges->isEmpty())
            {{-- 空状態 --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl px-6 py-16 text-center">
                <p class="text-gray-500">まだ知識が登録されていません</p>
                <a href="{{ route('knowledges.create',['from' => 'index']) }}" class="inline-block mt-4 px-5 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition">最初の知識を登録する</a>
            </div>
        @else
            {{-- 件数行 + テーブル + ページネーションを１つのカードに --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">

                {{-- 件数表示 --}}
                <div class="px-5 py-4 text-sm text-gray-500 border-b border-gray-100">
                    全 {{$knowledges->total() }} 件中 {{ $knowledges->firstItem() }} 〜 {{ $knowledges->lastItem() }}件を表示
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-knowledge/10 text-knowledge">
                        <tr>
                            <x-sort-header column="created_at" label="登録日" :sort="$sort" :direction="$direction" color="knowledge" />
                            <x-sort-header column="title" label="タイトル" :sort="$sort" :direction="$direction" color="knowledge" />
                            <x-sort-header column="book" label="参照元" :sort="$sort" :direction="$direction" color="knowledge" />
                            <th class="px-5 py-3 text-left font-bold">該当箇所</th>
                            <th class="px-5 py-3 text-left font-bold">タグ</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($knowledges as $knowledge)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-bold text-gray-900 whitespace-nowrap">{{ $knowledge->created_at->format('Y-m-d') }}</td>
                                <td class="px-5 py-4 font-bold text-gray-900">
                                    <a href="{{ route('knowledges.show', $knowledge) }}" class="text-gray-700 font-medium hover:text-knowledge hover:underline">
                                        {{ $knowledge->title }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-gray-900">{{ $knowledge->book->title }}</td>
                                <td class="px-5 py-4 text-gray-900">{{ $knowledge->book_page ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach (array_filter([$knowledge->tag1, $knowledge->tag2, $knowledge->tag3]) as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ページネーション --}}
                @php
                    $current = $knowledges->currentPage();
                    $last = $knowledges->lastPage();
                    $window = 2;
                    $start = max(1, $current - $window);
                    $end = min($last, $current + $window);
                @endphp
                <div class="px-5 py-4 border-t border-gray-100 flex justify-center items-center gap-2">
                    @if ($knowledges->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">&lt; Prev</span>
                    @else
                        <a href="{{ $knowledges->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100">&lt; Prev</a>
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page === $current)
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold bg-knowledge text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $knowledges->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-gray-600 border border-gray-200 hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($knowledges->hasMorePages())
                        <a href="{{ $knowledges->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100"> Next &gt;</a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">Next &gt;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
