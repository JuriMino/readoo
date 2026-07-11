<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- パンくず --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('actions.index') }}" class="hover:underline">Action List</a>
            <span class="mx-1">&gt;</span> Action Detail
        </nav>

        {{-- 見出し行：タイトル＋編集・削除アイコン --}}
        <div class="mt-2 flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Action Detail</h1>
            <div class="flex items-center gap-2">
                {{-- 編集 --}}
                <a href="{{ route('actions.edit', $action)}}" title="編集" class="p-2 text-gray-500 hover:text-action transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                    </svg>
                </a>
                {{-- 削除（モーダルを開く） --}}
                <button type="button" title="削除" x-data="" x-on:click.prevent="$dispatch('open-modal','confirm-action-deletion')" class="p-2 text-gray-500 hover:text-red-600 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.16-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.04-2.09 1.022-2.09 2.201v.916" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- 基本情報 --}}
        <section class="mt-8 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">基本情報</h2>
            <dl class="px-6 py-6 grid grid-cols-6 gap-x-6 gap-y-5 text-sm">
                <div class="col-span-6">
                    <dt class="text-gray-400">タイトル</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $action->title }}</dd>
                </div>
                <div class="col-span-4">
                    <dt class="text-gray-400">参照元（本）</dt>
                    <dd class="mt-1">
                        <a href="{{ route('books.show', $action->book )}}" class="text-secondary font-bold hover:underline">
                            {{ $action->book->title }}
                        </a>
                    </dd>
                </div>
                <div class="col-span-2">
                    <dt class="text-gray-400">該当箇所</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $action->book_page ?? '-' }}</dd>
                </div>
                <div class="col-span-6">
                    <dt class="text-gray-400">登録日</dt>
                    <dd class="mt-1 text-gray-900 font-bold">{{ $action->created_at->format('Y-m-d') }}</dd>
                </div>
            </dl>
        </section>

        {{-- 5W1H --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">5W1H</h2>
            <div class="px-6 py-6">
                @php
                    $w5h1 = [
                        'timing'        => 'WHEN：いつ実行する？',
                        'place'         => 'WHERE：どこで実行する？',
                        'target_person' => 'WHO：誰に関わる？',
                        'detail'        => 'WHAT：何をする？',
                        'reason'        => 'WHY：なぜやる？なんのためにやる？',
                        'method'        => 'HOW：どうやってやる？',
                    ];
                @endphp
                <div class="grid grid-cols-2 gap-5">
                    @foreach ($w5h1 as $name => $label)
                        <div class="border border-gray-200 rounded-xl overflow-hidden">
                            <div class="px-4 py-2 bg-orange-50 text-orange-700 text-sm font-bold">{{ $label }}</div>
                            <div class="px-4 py-3 text-sm text-gray-900 whitespace-pre-wrap">{{ $action->{$name} ?? '-' }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- タグ --}}
        <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
            <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">タグ</h2>
            <div class="px-6 py-6">
                @php
                    $tags = array_filter([$action->tag1, $action->tag2, $action->tag3]);
                @endphp
                @if (empty($tags))
                    <p class="text-sm text-gray-400">タグは登録されていません</p>
                @else
                    <div class="flex flex-wrap gap-1.5">
                        @foreach ($tags as $tag)
                            <span class="inline-block px-3 py-1 rounded-full border border-orange-200 bg-orange-50 text-orange-600 text-xs">{{ $tag }}</span>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>

        {{-- 紐づく知識（任意。ある時だけ・青テーマで他ドメインへの導線 --}}
        @if ($action->knowledge)
            <section class="mt-6 bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-knowledge text-white font-bold">紐づく知識</h2>
                <div class="px-6 py-6 bg-blue-50">
                    <a href="{{ route('knowledges.show', $action->knowledge )}}" class="text-secondary font-bold hover:underline">{{ $action->knowledge->title }}</a>
                </div>
            </section>
        @endif

        {{-- 一覧へ戻る --}}
        <div class="mt-8">
            <a href="{{ route('actions.index') }}" class="text-sm text-action hover:underline">&larr; Action List に戻る</a>
        </div>

        {{-- 削除確認モーダル --}}
        <x-modal name="confirm-action-deletion" focusable>
            <form action="{{ route('actions.destroy', $action) }}" method="POST" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-bold text-gray-900">削除していいですか？</h2>
                <p class="mt-2 text-sm text-gray-600">
                    「{{ $action->title }}」を削除します。この操作は取り消せません。
                </p>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700">Delete</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>
