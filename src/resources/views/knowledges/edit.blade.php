<x-app-layout theme="knowledge">
    <div class="max-w-5xl mx-auto px-6 py-12">
        {{-- パンくず + 見出し --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('knowledges.index')}}" class="hover:underline">Knowledge List</a>
            <span class="mx-1">&gt;</span>
            <a href="{{ route('knowledges.show', $knowledge) }}" class="hover:underline">Knowledge Detail</a>
            <span class="mx-1">&gt;</span> Edit Knowledge
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Edit Knowledge</h1>

        <form action="{{ route('knowledges.update', $knowledge)}}" method="post" class="mt-8 space-y-8">
            @csrf
            @method('PATCH')

            {{-- 参照元 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">参照元</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- 参照元（本）※編集不可。表示のみ --}}
                        <div>
                            <label class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>参照元（本）</span>
                            </label>
                            <div class="mt-2 px-4 py-3 bg-gray-100 border border-gray-200 rounded-lg text-gray-500">
                                {{ $knowledge->book->title}}
                            </div>
                            <p class="mt-1 text-xs text-gray-400">参照元の本は変更できません</p>
                        </div>
                        {{-- 該当箇所（ページ） --}}
                        <div>
                            <label for="book_page" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>該当箇所（ページ）</span><x-optional-badge />
                            </label>
                            <input type="text" name="book_page" id="book_page" value="{{ old('book_page', $knowledge->book_page) }}" placeholder="例：P.42" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">
                            <x-input-error :messages="$errors->get('book_page')" class="mt-2"/>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 知識の内容 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">知識の内容</h2>
                <div class="px-6 py-6 space-y-6">
                    {{-- タイトル --}}
                    <div>
                        <label for="title" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>タイトル</span><x-required-badge />
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $knowledge->title) }}" required autofocus class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    {{-- 内容 --}}
                    <div>
                        <label for="content" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>内容</span><x-required-badge />
                        </label>
                        <textarea name="content" id="content" rows="5" required class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">{{ old('content', $knowledge->content) }}</textarea>
                        <x-input-error :messages="$errors->get('content')" class="mt-2" />
                    </div>
                </div>
            </section>

            {{-- タグ --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">タグ</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-3 gap-6">
                        @foreach (['tag1' => 'タグ１', 'tag2' => 'タグ２', 'tag3' => 'タグ３'] as $name => $label)
                            <div>
                                <label for="{{ $name }}" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                    <span>{{ $label }}</span><x-optional-badge />
                                </label>
                                <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $knowledge->$name) }}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">
                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ボタン --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('knowledges.show', $knowledge) }}" class="px-6 py-2 border bg-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition">Update</button>
            </div>
        </form>
    </div>
</x-app-layout>
