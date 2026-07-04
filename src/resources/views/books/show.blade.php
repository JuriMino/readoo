<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- パンくず --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('books.index')}}" class="hover:underline">Book List</a>
            <span class="mx-1">&gt;</span> Book Detail
        </nav>

        {{-- 見出し行：タイトル＋編集・削除アイコン --}}
        <div class="mt-2 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Book Detail</h1>
            <div class="flex items-center gap-2">
                {{-- 編集（編集画面へ） --}}
                <a href="{{route('books.edit', $book)}}" title="編集" class="p-2 text-gray-500 hover:text-secondary transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                </a>
                {{-- 削除（モーダルを開く） --}}
                <button type="button" title="削除" x-data="" x-on:click.prevent="$dispatch('open-modal','confirm-book-deletion')" class="p-2 text-gray-500 hover:text-red-600 transition ">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.16-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.04-2.09 1.022-2.09 2.201v.916" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- ステータスのバッジ色 --}}
        @php
            $statusStyles = [
                'unread'   => 'bg-gray-100 text-gray-600',
                'reading'  => 'bg-blue-100 text-blue-600',
                'finished' => 'bg-green-100 text-green-600',
            ];
        @endphp

        {{-- 基本情報 --}}
        <section class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-green-50 text-gray-700 font-bold">基本情報</h2>
            <dl class="px-6 py-6 grid grid-cols-6 gap-x-6 gap-y-5 text-sm">
                <div class="col-span-6">
                    <dt class="text-gray-400">タイトル</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->title }}</dd>
                </div>
                <div class="col-span-3">
                    <dt class="text-gray-400">著者</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->author }}</dd>
                </div>
                <div class="col-span-3">
                    <dt class="text-gray-400">出版社</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->publisher ?? '-' }}</dd>
                </div>
                <div class="col-span-3">
                    <dt class="text-gray-400">ジャンル</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ \App\Models\Book::GENRES[$book->genre] }}</dd>
                </div>
                <div class="col-span-3">
                    <dt class="text-gray-400">ステータス</dt>
                    <dd class="mt-1">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-bold {{ $statusStyles[$book->status] }}">
                            {{ \App\Models\Book::STATUSES[$book->status] }}
                        </span>
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">読書開始日</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->started_at?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">読了日</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->finished_at?->format('Y-m-d') ?? '-' }}</dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">登録日</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $book->created_at->format('Y-m-d') }}</dd>
                </div>
            </dl>
        </section>

        {{-- 本文情報 --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-green-50 text-gray-700 font-bold">本文情報</h2>
            <dl class="px-6 py-6 space-y-5 text-sm">
                <div>
                    <dt class="text-gray-400">要約</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $book->summary ?? '-' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-400">メモ</dt>
                    <dd class="mt-1 text-gray-900 whitespace-pre-wrap">{{ $book->memo ?? '-' }}</dd>
                </div>
            </dl>
        </section>

        {{-- 紐づく知識 --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <div class="px-6 py-3 bg-blue-50 flex items-center justify-between">
                <h2 class="text-blue-700 font-bold">この本から得た知識</h2>
                <a href="{{route('knowledges.create',['from' => 'book', 'book_id' => $book->id])}}" class="px-4 py-1.5 bg-knowledge rounded-lg text-white text-sm font-bold hover:opacity-90 transition"> + 知識を追加</a>
            </div>
            @if ($book->knowledges->isEmpty())
                <p class="px-6 py-8 text-center text-sm text-gray-500">まだ知識が登録されていません</p>
            @else
                <ul class="divide-y divide-gray-100">
                    @foreach ($book->knowledges as $knowledge)
                        <li class="px-6 py-4 hover:bg-gray-50 transition">
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <p class="font-bold text-gray-900">{{ $knowledge->title}}</p>
                                    @if ($knowledge->book_page)
                                        <p class="mt-0.5 text-xs text-gray-400">{{$knowledge->book_page}}</p>
                                    @endif
                                    <div class="mt-2 flex flex-wrap gap-1.5">
                                        @foreach (array_filter([$knowledge->tag1, $knowledge->tag2, $knowledge->tag3]) as $tag)
                                            <span class="inline-block px-3 py-1 rounded-full border border-blue-200 bg-blue-50 text-blue-600 text-xs">{{ $tag }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <a href="{{ route('knowledges.show', $knowledge) }}" class="shrink-0 text-sm text-gray-700 font-medium hover:text-knowledge hover:underline">詳細</a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        {{-- 一覧へ戻る --}}
        <div class="mt-8">
            <a href="{{ route('books.index') }}" class="text-sm text-secondary hover:underline">&larr; Book Listに戻る</a>
        </div>

        {{-- 削除確認モーダル --}}
        <x-modal name="confirm-book-deletion" focusable>
            <form action="{{ route('books.destroy', $book) }}" method="post" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-bold text-gray-900">削除していいですか？</h2>
                <p class="mt-2 text-sm text-gray-600">
                    「{{ $book->title }}を削除します。この操作は取り消せません。」
                </p>
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
