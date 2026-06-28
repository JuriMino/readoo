<x-app-layout>
    <div class="max-w-3xl mx-auto px-6 py-12">

        {{-- パンくず＋見出し --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('books.index') }}" class="hover:underline">Book List</a>
            <span class="mx-1">&gt;</span>
            <a href="{{ route('books.show', $book)}}" class="hover:underline">Book Detail</a>
            <span class="mx-1">&gt;</span> Edit Book
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">Edit Book</h1>

        <form action="{{ route('books.update', $book) }}" method="post" class="mt-8 space-y-8">
            @csrf
            @method('PATCH')

            {{-- 基本情報 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 bg-green-50 text-gray-700 font-bold">基本情報</h2>
                <div class="px-6 py-6 space-y-6">
                    {{-- タイトル --}}
                    <div>
                        <label for="title" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>タイトル</span><x-required-badge />
                        </label>
                        <input type="text" name="title" id="title" value="{{ old('title', $book->title) }}" required autofocus class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- 著者・出版社 --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="author" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>著者</span><x-required-badge />
                            </label>
                            <input type="text" name="author" id="author" value="{{ old('author', $book->author) }}" required class="block w-full mt-2 px4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                            <x-input-error :messages="$errors->get('author')" class="mt-2" />
                        </div>
                        <div>
                            <label for="publisher" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>出版社</span><x-optional-badge />
                            </label>
                            <input type="text" name="publisher" id="publisher" value="{{ old('publisher', $book->publisher) }}"  class="block w-full mt-2 px4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                            <x-input-error :messages="$errors->get('publisher')" class="mt-2" />
                        </div>
                    </div>

                    {{-- ジャンル・ステータス --}}
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="genre" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>ジャンル</span><x-required-badge />
                            </label>
                            <select name="genre" id="genre" required class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-secondary focus:ring-secondary">
                                <option value="">選択してください</option>
                                @foreach (\App\Models\Book::GENRES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('genre', $book->genre) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('genre')" class="mt-2" />
                        </div>
                        <div>
                            <label for="status" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>ステータス</span><x-required-badge />
                            </label>
                            <select name="status" id="status" required class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-secondary focus:ring-secondary">
                                @foreach (\App\Models\Book::STATUSES as $value => $label)
                                    <option value="{{ $value }}" @selected(old('status', $book->status) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </section>

            {{-- 読書期間 --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 border-green-50 text-gray-700 font-bold">読書期間</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label for="started_at" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>読書開始日</span><x-optional-badge />
                            </label>
                            <input type="date" name="started_at" id="started_at" value="{{ old('started_at', $book->started_at?->format('Y-m-d'))}}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-secondary focus:ring-secondary">
                            <x-input-error :messages="$errors->get('started_at')" class="mt-2" />
                        </div>
                        <div>
                            <label for="finished_at" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>読了日</span><x-optional-badge />
                            </label>
                            <input type="date" name="finished_at" id="finished_at" value="{{ old('finished_at', $book->finished_at?->format('Y-m-d'))}}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-secondary focus:ring-secondary">
                            <p class="mt-1 text-xs text-gray-400">読書開始日以降の日付を選択してください</p>
                            <x-input-error :messages="$errors->get('finished_at')" class="mt-2" />
                        </div>
                    </div>
                </div>
            </section>

            {{-- 本文情報 --}}
            <section class="bg-white border border-grau-200 rounded-2xl overflow-hidden">
                <h2 class="px-6 py-3 br-green-50 text-gray-700 font-bold">本文情報</h2>
                <div class="px-6 py-6 space-y-6">
                    <div>
                        <label for="summary" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>要約</span><x-optional-badge />
                        </label>
                        <textarea name="summary" id="summary" rows="4" class="block w-full mt-2 px-4 py-3 bg-gray-50 border boder-gray-700 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">{{ old('summary', $book->summary )}}</textarea>
                        <x-input-error :messages="$errors->get('summary')" class="mt-2" />
                    </div>
                    <div>
                        <label for="memo" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>メモ</span><x-optional-badge />
                        </label>
                        <textarea name="memo" id="memo" rows="4" class="block w-full mt-2 px-4 py-3 bg-gray-50 border boder-gray-700 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">{{ old('memo', $book->memo )}}</textarea>
                        <x-input-error :messages="$errors->get('memo')" class="mt-2" />
                    </div>
                </div>
            </section>

            {{-- ボタン --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('books.show', $book) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-secondary rounded-lg text-white font-bold hover:opacity-90 transition">
                    Upsate
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
