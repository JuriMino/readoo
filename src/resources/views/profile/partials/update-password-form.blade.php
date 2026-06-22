<section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
    {{-- カードヘッダー（青帯） --}}
    <div class="px-6 py-3 bg-blue-50 border-b border-gray-200">
        <h2 class="font-bold text-secondary">パスワード変更</h2>
    </div>

    <form action="{{ route('password.update') }}" method="post" class="px-6 py-6 space-y-5">
        @csrf
        @method('put')

        {{-- 現在のパスワード --}}
        <div x-data="{ showCurrentPassword: false }">
            <label for="current_password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                <span>CURRENT PASSWORD</span>
                <x-required-badge />
            </label>
            <div class="relative mt-2">
                <input :type="showCurrentPassword ? 'text' : 'password'" name="current_password" id="current_password" placeholder="**********" autocomplete="current-password" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                <button type="button" @click="showCurrentPassword =! showCurrentPassword" class="absolute inset-y-0 right-0 flex items-center pr-3" >
                    <svg x-show="!showCurrentPassword" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showCurrentPassword" x-cloak class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('current_password')" />
        </div>

        {{-- 新しいパスワード --}}
        <div x-data="{ showNewPassword: false }">
            <label for="new_password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                <span>NEW PASSWORD</span>
                <x-required-badge />
            </label>
            <div class="relative mt-2">
                <input :type="showNewPassword ? 'text' : 'password'" name="new_password" id="new_password" placeholder="**********" autocomplete="new-password" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                <button type="button" @click="showNewPassword =! showNewPassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg x-show="!showNewPassword" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showNewPassword" x-cloak class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('new_password')" />
        </div>

        {{-- 新しいパスワード（確認） --}}
        <div x-data="{ showConfirmPassword: false }">
            <label for="confirm_password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                <span>NEW PASSWORD（CONFIRM）</span>
                <x-required-badge />
            </label>
            <div class="relative mt-2">
                <input :type="showConfirmPassword ? 'text' : 'password'" name="confirm_password" id="confirm_password" placeholder="**********" autocomplete="new-password" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                <button type="button" @click="showConfirmPassword =! showConfirmPassword" class="absolute inset-y-0 right-0 flex items-center pr-3">
                    <svg x-show="!showConfirmPassword" class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="showConfirmPassword" x-cloak class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error class="mt-2" :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>

        {{-- ボタン --}}
        <div class="flex items-center justify-end gap-3">
            @if(session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-500">
                    保存しました
                </p>
            @endif
            <a href="{{ route('mypage') }}" class="px-6 py-2 bg-gray-200 rounded-lg text-gray-700 font-bold hover:bg-gray-300 transition">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-secondary rounded-lg text-white font-bold hover:opacity-90 transition">
                Update
            </button>
        </div>
    </form>
</section>
