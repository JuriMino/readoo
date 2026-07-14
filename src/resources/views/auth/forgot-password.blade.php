<x-guest-layout>
    <div class="w-full max-w-xl">
        <h1 class="text-3xl font-bold text-center text-gray-900">パスワード再設定</h1>
        <p class="mt-2 text-center text-gray-500">
            登録済みのメールアドレスを入力してください。<br>
            パスワード再設定用のリンクをお送りします。
        </p>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mt-8 px-8 py-10 bg-white border border-gray-200 rounded-2xl">
            <form method="POST" action="{{ route('password.email') }}">
                @csrf
                <!-- Email Address -->
                <div>
                    <label for="email" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>E-MAIL</span>
                        <x-required-badge />
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="example@email.com" required autofocus autocomplete="username" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>
                <button type="submit" class="block w-full mt-8 px-4 py-3 bg-secondary rounded-lg text-white font-bold hover:opacity-90 transition">
                    再設定リンクを送信
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-500">
                <a href="{{ route('login') }}" class="text-secondary underline">ログイン画面に戻る</a>
            </p>
        </div>
    </div>
</x-guest-layout>
