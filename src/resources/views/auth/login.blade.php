<x-guest-layout>
    <div class="w-full max-w-xl">
        <!-- 見出し -->
        <h1 class="text-3xl font-bold text-center text-gray-900">Log in</h1>
        <p class="mt-2 text-center text-gray-500">アカウントにログインしてください</p>

        <!-- セッションステータス（パスワード変更後などの通知） -->
        <x-auth-session-status class="mt-4" :status="session('status')" />

        <!-- カード -->
        <div class="mt-8 px-8 py-10 bg-white border border-gray-200 rounded-2xl">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- メールアドレス -->
                <div>
                    <label for="email" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>E-MAIL</span>
                        <x-required-badge />
                    </label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}"
                        placeholder="example@email.com" required autofocus autocomplete="username"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg
                               text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <!-- パスワード -->
                <div class="mt-6">
                    <label for="password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>PASSWORD</span>
                        <x-required-badge />
                    </label>
                    <input id="password" name="password" type="password"
                        placeholder="**********" required autocomplete="current-password"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg
                               text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <p class="mt-1 text-sm text-gray-500">英数字8文字以上</p>
                    <x-input-error class="mt-2" :messages="$errors->get('password')" />
                </div>

                <!-- ログインボタン -->
                <button type="submit"
                    class="block w-full mt-8 px-4 py-3 bg-secondary rounded-lg text-white font-bold
                           hover:opacity-90 transition">
                    Log in
                </button>
            </form>

            <!-- 新規登録への導線 -->
            <p class="mt-6 text-center text-sm text-gray-500">
                アカウントをお持ちでないですか？<a href="{{ route('register') }}"
                    class="text-secondary underline">新規登録はこちら</a>
            </p>
        </div>
    </div>
</x-guest-layout>
