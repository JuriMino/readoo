<section class="p-6 bg-red-50 border border-red-200 rounded-2xl">
    <h2 class="font-bold text-red-600">危険</h2>
    <p class="mt-2 text-sm text-gray-600">
        退会するとすべてのデータ（本・知識・行動）が削除され、この操作は元に戻せません
    </p>
    {{-- 確認モーダルを開くボタン --}}
    <button type="button" x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="mt-4 px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700 transtion">
        Delete Account
    </button>

    {{-- 確認モーダル（本人確認のためパスワード入力） --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form action="{{ route('profile.destroy') }}" method="post" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-grau-900">本当に退会しますか？</h2>
            <p class="mt-2 text-sm text-grau-600">
                退会するとアカウントとすべてのデータが削除されます。確認のためパスワードを入力してください。
            </p>
            <div class="mt-6">
                <input id="delete_password" name="password" type="password" placeholder="**********" class="block w-3/4 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                <x-input-error class="mt-2" :messages="$errors->userDeletion->get('password')" />
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition">Cancel</button>
                <button type="submit" class="px-5 py-2 bg-red-600 rounded-lg text-white font-bold hover:bg-red-700 transition">Delete Account</button>
            </div>
        </form>
    </x-modal>
</section>
