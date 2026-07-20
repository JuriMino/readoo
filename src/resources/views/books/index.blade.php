<x-app-layout theme="book">
    <div class="max-w-8xl mx-auto px-6 py-12">
        {{-- 見出し行・左にタイトル＋New Book、右に他コレクション導線 --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Book List</h1>
                <p class="mt-2 text-gray-500">読んだ本・読みたい本を管理し、知識と行動につなげよう</p>
                <a href="{{ route('books.create') }}" class="inline-block mt-4 px-5 py-2 bg-book rounded-lg text-white font-bold hover:opacity-90 transition">
                    + New Book
                </a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('knowledges.index')}}" class="inline-block mt-4 px-5 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition">
                    Knowledge List
                </a>
                <a href="{{ route('actions.index')}}" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">
                    Action List
                </a>
            </div>
        </div>

        {{-- フラッシュメッセージ --}}
        @if(session('status'))
            <div class="mt-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('status') }}
            </div>
        @endif

        {{-- ステータスのバッジ色 --}}
        @php
            $statusStyles = [
                'unread'   => 'bg-gray-100 text-gray-600',
                'reading'  => 'bg-orange-100 text-orange-700',
                'finished' => 'bg-blue-100 text-blue-700'
            ];
        @endphp

        @if($books->isEmpty())
            {{-- 空状態 --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl px-6 py-16 text-center">
                <p class="text-gray-500">まだ書籍が登録されていません</p>
                <a href="{{ route('books.create')}}" class="inline-block mt-4 px-5 py-2 bg-book rounded-lg text-white font-bold hover:opacity-90 transition">
                    最初の１冊を登録する
                </a>
            </div>
        @else
            {{-- 件数表示 + テーブル + ページネーションを１つのカードに --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                {{-- 件数表示 --}}
                <div class="px-5 py-4 text-sm text-gray-500 border-b border-gray-100">
                    全 {{ $books->total() }} 件中 {{ $books->firstItem() }} 〜 {{ $books->lastItem()}} 件を表示
                </div>
                <table class="w-full text-sm">
                    <thead class="bg-book/10 text-book">
                        <tr>
                            <x-sort-header column="created_at" label="登録日" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="title" label="タイトル" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="author" label="著者" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="publisher" label="出版社" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="genre" label="ジャンル" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="status" label="ステータス" :sort="$sort" :direction="$direction" color="book" />
                            <x-sort-header column="started_at" label="読書開始日" :sort="$sort" :direction="$direction" color="book" />
                            <th class="px-5 py-3 text-left font-bold">読了日</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($books as $book)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $book->created_at->format('Y-m-d')}}</td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('books.show', $book)}}" class="font-bold text-gray-900 hover:text-book hover:underline">
                                        {{ $book->title}}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $book->author }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $book->publisher ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ \App\Models\Book::GENRES[$book->genre] }}</td>
                                <td class="px-5 py-4 text-gray-600">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$book->status] }}">
                                        {{ \App\Models\Book::STATUSES[$book->status] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $book->started_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600 whitespace-nowrap">{{ $book->finished_at?->format('Y-m-d') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {{-- ページネーション --}}
                @php
                    $current = $books->currentPage();
                    $last = $books->lastPage();
                    $window = 2;
                    $start = max(1, $current - $window);
                    $end = min($last, $current + $window);
                @endphp
                <div class="px-5 py-4 border-t border-gray-100 flex justify-center items-center gap-2">
                    @if ($books->onFirstPage())
                        <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">&lt; Prev</span>
                    @else
                        <a href="{{ $books->previousPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100">&lt; Prev</a>
                    @endif

                    @for ($page = $start; $page <= $end; $page++)
                        @if ($page === $current)
                            <span class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-bold bg-book text-white">{{ $page }}</span>
                        @else
                            <a href="{{ $books->url($page) }}" class="w-8 h-8 flex items-center justify-center rounded-lg text-sm text-gray-600 border border-gray-200 hover:bg-gray-100">{{ $page }}</a>
                        @endif
                    @endfor

                    @if ($books->hasMorePages())
                        <a href="{{ $books->nextPageUrl() }}" class="px-3 py-1.5 rounded-lg text-sm text-gray-600 hover:bg-gray-100"> Next &gt;</a>
                    @else
                        <span class="px-3 py-1.5 rounded-lg text-sm text-gray-300 cursor-not-allowed">Next &gt;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
