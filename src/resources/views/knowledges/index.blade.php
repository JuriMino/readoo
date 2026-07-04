<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-12">

        {{-- 見出し行・左にタイトル＋New Knowledge、右に他コレクション導線 --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">Knowledge List</h1>
                <p class="mt-2 text-gray-500">本から得た知識を記録し、行動につなげよう</p>
                <a href="{{ route('knowledges.create',['from' => 'index']) }}" class="inline-block mt-4 px-5 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition"> + New Knowledge</a>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('books.index')}}" class="inline-block mt-4 px-5 py-2 bg-book rounded-lg text-white font-bold hover:opacity-90 transition">Book List</a>
                <a href="#" class="inline-block mt-4 px-5 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">Action List</a>
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
                    <thead class="bg-gray-50 text-gray-500">
                        <tr>
                            <th class="px-5 py-3 text-left font-bold">タイトル</th>
                            <th class="px-5 py-3 text-left font-bold">参照元</th>
                            <th class="px-5 py-3 text-left font-bold">該当箇所</th>
                            <th class="px-5 py-3 text-left font-bold">タグ</th>
                            <th class="px-5 py-3 text-left font-bold">詳細</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($knowledges as $knowledge)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4 font-bold text-gray-900">{{ $knowledge->title }}</td>
                                <td class="px-5 py-4 text-gray-900">{{ $knowledge->book->title }}</td>
                                <td class="px-5 py-4 text-gray-900">{{ $knowledge->book_page ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach (array_filter([$knowledge->tag1, $knowledge->tag2, $knowledge->tag3]) as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <a href="{{ route('knowledges.show', $knowledge) }}" class="text-gray-700 font-medium hover:text-knowledge hover:underline">詳細</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- ページネーション --}}
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $knowledges->links() }}
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
