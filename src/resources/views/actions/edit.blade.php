<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12">
        {{-- パンくず + 見出し --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('actions.index')}}" class="hover:underline">Action List</a>
            <span class="mx-1">&gt;</span>
            <a href="{{ route('actions.show', $action) }}" class="hover:underline">Action Detail</a>
            <span class="mx-1">&gt;</span> Edit Action
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Edit Action</h1>

        <form action="{{ route('actions.update', $action) }}" method="post" class="mt-8 space-y-8">
            @csrf
            @method('PATCH')

            {{-- 参照元（本・知識は変更不可。表示のみ） --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">参照元</h2>
                <div class="px-6 py-6 space-y-6">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- 参照元（本）※編集不可 --}}
                        <div>
                            <span class="text-sm font-bold text-gray-700">参照元（本）</span>
                            <div class="mt-2 px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-500">
                                {{ $action->book->title }}
                            </div>
                            <p class="mt-1 text-xs text-gray-400">参照元の本は変更できません</p>
                        </div>
                        {{-- 該当箇所（ページ）※編集可 --}}
                        <div>
                            <label for="book_page" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>該当箇所（ページ）</span><x-optional-badge />
                            </label>
                            <input type="text" name="book_page" id="book_page" value="{{ old('book_page', $action->book_page) }}" placeholder="例：P.42" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
                            <x-input-error :messages="$errors->get('book_page')" class="mt-2" />
                        </div>
                    </div>

                    {{-- 関連知識 ※編集不可 --}}
                    <div>
                        <span class="text-sm font-bold text-gray-700">関連知識</span>
                        <div class="mt-2 px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-500">
                            {{ $action->knowledge?->title ?? '(紐付けなし)' }}
                        </div>
                        <p class="mt-1 text-xs text-gray-400">関連知識は変更できません</p>
                    </div>
                </div>
            </section>

            {{-- 行動の内容 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">行動の内容</h2>
                <div class="px-6 py-6">
                    <div>
                        <label for="title" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>タイトル</span><x-required-badge />
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $action->title )}}" required autofocus class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                </div>
            </section>

            {{-- 5W1H --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">5W1H（具体的な行動の設計）</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-6">
                        @php
                            $w5h1 = [
                                'timing'        => ['label' => 'WHEN：いつ実行する？', 'req' => true],
                                'place'         => ['label' => 'WHERE：どこで実行する？', 'req' => true],
                                'target_person' => ['label' => 'WHO：誰に関わる？', 'req' => false],
                                'detail'        => ['label' => 'WHAT：何をする？', 'req' => false],
                                'reason'        => ['label' => 'WHY：なぜやる？なんのためにやる？', 'req' => true],
                                'method'        => ['label' => 'HOW：どうやってやる？', 'req' => true],
                            ];
                        @endphp
                        @foreach ($w5h1 as $name => $field)
                            <div>
                                <label for="{{ $name }}" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                    <span>{{ $field['label'] }}</span>
                                    @if($field['req'])<x-required-badge /> @else <x-optional-badge /> @endif
                                </label>
                                <textarea name="{{ $name }}" id="{{ $name }}" rows="2" @if($field['req']) required @endif class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">{{ old($name, $action->{$name}) }}</textarea>
                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- タグ --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">タグ</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-3 gap-6">
                        @foreach (['tag1' => 'タグ１', 'tag2' => 'タグ２', 'tag3' => 'タグ３',] as $name => $label)
                            <div>
                                <label for="{{ $name }}" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                    <span>{{ $label }}</span><x-optional-badge />
                                </label>
                                <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $action->$name) }}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ボタン --}}
            <div class="flex justify-end gap-3">
                <a href="{{route('actions.show', $action) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
