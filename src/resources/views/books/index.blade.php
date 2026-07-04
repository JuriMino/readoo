<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-12">

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
                <a href="#" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">
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
                'reading'  => 'bg-blue-100 text-blue-700',
                'finished' => 'bg-green-100 text-green-700'
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
            {{-- テーブル --}}
            <div class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-bold">タイトル</th>
                            <th class="px-5 py-3 text-left font-bold">著者</th>
                            <th class="px-5 py-3 text-left font-bold">出版社</th>
                            <th class="px-5 py-3 text-left font-bold">ジャンル</th>
                            <th class="px-5 py-3 text-left font-bold">ステータス</th>
                            <th class="px-5 py-3 text-left font-bold">読書開始日</th>
                            <th class="px-5 py-3 text-left font-bold">読了日</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($books as $book)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4">
                                    <a href="{{ route('books.show', $book)}}" class="font-bold text-gray-900 hover:text-book hover:underline">
                                        {{ $book->title}}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $book->author }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $book->publisher ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ \App\Models\Book::GENRES[$book->genre] }}</td>
                                <td class="px-5 py-4 text-gray-600">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$book->status] }}">
                                        {{ \App\Models\Book::STATUSES[$book->status] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $book->started_at?->format('Y-m-d') ?? '-' }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $book->finished_at?->format('Y-m-d') ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- ページネーション --}}
            <div class="mt-6">
                {{ $books->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
