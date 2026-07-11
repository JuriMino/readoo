<x-app-layout>
    @php
        // 登録後・キャンセル時の戻り先（開いた画面に応じて分岐）
        $cancelUrl = match(request('from')){
            'knowledge' => $selectedKnowledgeId ? route('knowledges.show', $selectedKnowledgeId) : route('actions.index'),
            'book'      => $selectedBookId ? route('books.show', $selectedBookId) : route('actions.index'),
            default     => route('actions.index'),
        };
    @endphp

    <div class="max-w-3xl mx-auto px-6 py-12">
        {{-- パンくず + 見出し --}}
        <nav class="text-sm text-gray-400">
            <a href="{{ route('actions.index') }}" class="hover:underline">Action List</a>
            <span class="mx-1">&gt;</span> New Action
        </nav>
        <h1 class="mt-2 text-3xl font-bold text-gray-900">New Action</h1>

        <form action="{{ route('actions.store') }}" method="post" class="mt-8 space-y-8">
            @csrf
            {{-- どこからきたか（登録後の戻り先に使う） --}}
            <input type="hidden" name="from" value="{{ request('from', 'index')}}">

            {{-- 参照元(本 → 知識の連動ドロップダウンをこのカード内で完結) --}}
            <section class="bg-white border border-gray-200 rounded-2xl overflow-hidden"
                x-data="{
                    selectedBook: '{{ old('book_id', $selectedBookId) }}',
                    selectedKnowledge: '{{ old('knowledge_id', $selectedKnowledgeId) }}',
                    knowledges: @js($knowledges),
                    get filterKnowledges(){
                        return this.knowledges.filter(k => String(k.book_id) === String(this.selectedBook));
                    },
                }"
            >
                <h2 class="px-6 py-3 bg-orange-50 text-orange-700 font-bold">参照元</h2>
                <div class="px-6 py-6">
                    <div class="grid grid-cols-2 gap-6">
                        {{-- 参照元（本） --}}
                        <div>
                            <label for="book_id" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>参照元（本）</span><x-required-badge />
                            </label>
                            <select name="book_id" id="book_id" required
                                x-model="selectedBook"
                                x-on:change="selectedKnowledge = '' "
                                class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-action focus:ring-action">
                                <option value="">本を選択してください</option>
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">{{ $book->title }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('book_id')" class="mt-2" />
                        </div>
                        {{-- 該当箇所（ページ） --}}
                        <div>
                            <label for="book_page" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                <span>該当箇所（ページ）</span><x-optional-badge />
                            </label>
                            <input type="text" name="book_page" id="book_page" value="{{ old('book_page')}}" placeholder="例：P.42" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
                            <x-input-error :messages="$errors->get('book_page')" class="mt-2" />
                        </div>
                    </div>
                    {{-- 関連知識(本を選ぶと絞り込まれる。任意) --}}
                    <div>
                        <label for="knowledge_id" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                            <span>関連知識</span><x-optional-badge />
                        </label>
                        <select name="knowledge_id" id="knowledge_id" x-model="selectedKnowledge" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 focus:border-action focus:ring-action">
                            <option value="">知識を選択してください（本を選択後に絞り込まれます）</option>
                            <template x-for="k in filterKnowledges" :key="k.id">
                                <option :value="k.id" x-text="k.title"></option>
                            </template>
                        </select>
                        <p class="mt-2 text-xs text-gray-400">知識に紐付けない場合は空欄のままにしてください</p>
                        <x-input-error :messages="$errors->get('knowledge_id')" class="mt-2" />
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
                        <input type="text" name="title" id="title" value="{{ old('title') }}" required autofocus placeholder="行動のタイトルを入力してください" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
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
                                'timing' => ['label' => 'WHEN:いつ実行する？', 'req' => true, 'ph' => '例：起床時' ],
                                'place' => ['label' => 'WHERE:どこで実行する？', 'req' => true, 'ph' => '例：自宅のデスク' ],
                                'target_person' => ['label' => 'WHO:誰に関わる？', 'req' => false, 'ph' => '例：自分一人で、パートナーと一緒に' ],
                                'detail' => ['label' => 'WHAT：何をする？', 'req' => false, 'ph' => '例：瞑想をする' ],
                                'reason' => ['label' => 'WHY:なぜやる？なんのためにやる？', 'req' => true, 'ph' => '例：集中力を高めるため' ],
                                'method' => ['label' => 'HOW:どうやってやる？', 'req' => true, 'ph' => '例：スモールステップで数秒でもやったらOKにする' ],
                            ];
                        @endphp
                        @foreach ($w5h1 as $name => $field)
                            <div>
                                <label for="{{ $name }}" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                    <span>{{ $field['label']}}</span>
                                    @if($field['req'])
                                        <x-required-badge />
                                    @else
                                        <x-optional-badge />
                                    @endif
                                </label>
                                <textarea name="{{ $name }}" id="{{ $name }}" rows="2" @if($field['req']) required @endif placeholder="{{ $field['ph']}} " class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">{{ old($name) }}</textarea>
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
                        @foreach (['tag1' => 'タグ１', 'tag2' => 'タグ２', 'tag3' => 'タグ３'] as $name => $label)
                            <div>
                                <label for="{{ $name }}" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                                    <span>{{ $label }}</span><x-optional-badge />
                                </label>
                                <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name) }}" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-action focus:ring-action">
                                <x-input-error :messages="$errors->get($name)" class="mt-2" />
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>

            {{-- ボタン --}}
            <div class="flex justify-end gap-3">
                <a href="{{ $cancelUrl }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 font-bold hover:bg-gray-50 transition">Cancel</a>
                <button type="submit" class="px-6 py-2 bg-action rounded-lg text-white font-bold hover:opacity-90 transition">
                    Create
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
