<section class="p-6 bg-red-50 border border-red-200 rounded-2xl">
    <h2 class="font-bold text-red-600">退会</h2>
    <p class="mt-2 text-sm text-gray-600">
        退会するとすべてのデータ（本・知識・行動）が削除されます。この操作は取り消せません。
    </p>
    {{-- 確認モーダルを開くボタン --}}
    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="mt-4 px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700 transtion">
        Delete Account
    </button>

    {{-- 確認モーダル（本人確認のためパスワード入力） --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable maxWidth="lg">
        <form action="{{ route('profile.destroy') }}" method="post" class="p-6">
            @csrf
            @method('delete')

            {{-- ヘッダー --}}
            <div class="p-6">
                <h2 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span aria-hidden="ture">退会して良いですか？</span>
                </h2>
                <p class="mt-2 text-sm text-gray-500">この操作は取り消せません</p>
            </div>

            <div class="border-t border-gray-200"></div>

            {{-- 本文 --}}
            <div class="p-6 space-y-6">
                {{-- 統計サマリ --}}
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center bg-gray-50 border border-gray-200 rounded-xl py-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $bookCount }}</p>
                        <p class="mt-1 text-xs text-gray-500">登録した本</p>
                    </div>
                    <div class="text-center bg-gray-50 border border-gray-200 rounded-xl py-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $knowledgeCount }}</p>
                        <p class="mt-1 text-xs text-gray-500">登録した知識</p>
                    </div>
                    <div class="text-center bg-gray-50 border border-gray-200 rounded-xl py-4">
                        <p class="text-2xl font-bold text-gray-900">{{ $actionCount }}</p>
                        <p class="mt-1 text-xs text-gray-500">登録した行動</p>
                    </div>
                </div>

                {{-- 警告ボックス --}}
                <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                    <p class="fount-bold text-red-600 flex items-center gap-2">
                        <span aria-hidden="true">⚠️</span> 退会すると全てのデータが削除されます
                    </p>
                    <p class="mt-2 text-sm text-red-600">
                        上記の全てのデータは完全に削除され、復元できません。<br>
                        退会後はログインできなくなります。
                    </p>
                </div>
                {{-- 本人確認のためパスワード入力 --}}
                <div>
                    <label for="delete_password" class="block text-sm font-medium text-gray-700">
                        確認のためパスワードを入力してください
                    </label>
                    <input id="delete_password" name="password" type="password" placeholder="**********" class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
                </div>
            </div>

            {{-- フッター --}}
            <div class="p-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700 transition">Delete Account</button>
            </div>
        </form>
    </x-modal>
</section>
