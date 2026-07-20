<x-app-layout theme="knowledge">
    <div class="max-w-5xl mx-auto px-6 py-12">
        {{-- パンくず + 見出し --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('knowledges.index')}}" class="hover:underline">Knowledge List</a>
            <span class="mx-1">&gt;</span> new Knowledge
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">New Knowledge</h1>

        <form action="{{ route('knowledges.store') }}" method="post" class="mt-8 space-y-8">
            @csrf
            {{-- どこから来たか（登録後の戻り先に使う）本の詳細からきたらbooks.show、知識一覧からきたらknowledges.index --}}
            <input type="hidden" name="from" value="{{ request('from', 'book')}}">

            {{-- 参照元 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-blue-50 text-blue-700 font-bold">参照元</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- 参照元（本） --}}
                        <div>
                            <label for="book_id" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>参照元（本）</span><x-required-badge />
                            </label>
                            <select name="book_id" id="book_id" required class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-knowledge focus:ring-knowledge">
                                <option value="">本を選択してください</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}" @selected(old('book_id', $selectedBookId) == $book->id)>{{ $book->title }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                        </div>
                        {{-- 該当箇所（ページ） --}}
                        <div>
                            <label for="book_page" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>該当箇所（ページ）</span><x-optional-badge />
                            </label>
                            <input type="text" name="book_page" id="book_page" value="{{ old('book_page')}}" placeholder="例：P.42" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge ">
                            <x-input-error :messages="$errors->get('book_page')" class="mt-2" />
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
                        <input type="text" name="title" id="title" value="{{old('title')}}" required autofocus placeholder="知識のタイトルを入力してください" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>
                    {{-- 内容 --}}
                    <div>
                        <label for="content" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>内容</span><x-required-badge />
                        </label>
                        <textarea name="content" id="content" rows="5" required placeholder="本から得た知識・気づきを記載してください" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">{{ old('content') }}</textarea>
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
                                <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name) }}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-knowledge focus:ring-knowledge">
                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ボタン --}}
            <div class="flex justify-end gap-3">
                <a href="{{ request('from') === 'book' && $selectedBookId ? route('books.show', $selectedBookId) : route('knowledges.index') }}" class="px-6 py-2 bg-gray-300 border border-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-knowledge rounded-lg text-white font-bold hover:opacity-90 transition">Create</button>
            </div>
        </form>
    </div>
</x-app-layout>
