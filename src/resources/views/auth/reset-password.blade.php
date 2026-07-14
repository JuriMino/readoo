<x-guest-layout>
    <div class="w-full max-w-xl">
        <h1 class="text-3xl font-bold text-center text-gray-900">新しいパスワードの設定</h1>
        <p class="mt-2 text-center text-gray-500">新しいパスワードを入力してください</p>

        <div class="mt-8 px-8 py-10 bg-white border border-gray-200 rounded-2xl">
            <form method="POST" action="{{ route('password.store') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <div>
                    <label for="email" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>E-MAIL</span><x-required-badge />
                    </label>
                    <input id="email" name="email" type="email"
                        value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg
                               text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="mt-6">
                    <label for="password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>NEW PASSWORD</span><x-required-badge />
                    </label>
                    <input id="password" name="password" type="password"
                        placeholder="**********" required autocomplete="new-password"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg
                               text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <p class="mt-1 text-sm text-gray-500">英数字8文字以上</p>
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <div class="mt-6">
                    <label for="password_confirmation" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>CONFIRM PASSWORD</span><x-required-badge />
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        placeholder="**********" required autocomplete="new-password"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error class="mt-2" :messages="$errors->get('password_confirmation')" />
                </div>

                <button type="submit"
                    class="block w-full mt-8 px-4 py-3 bg-secondary rounded-lg text-white font-bold hover:opacity-90 transition">
                    パスワードを再設定
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
