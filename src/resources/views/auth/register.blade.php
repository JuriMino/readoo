<x-guest-layout>
    <div class="w-full max-w-xl">
        {{-- 見出し --}}
        <h1 class="text-3xl font-bold text-center text-gray-900">Register</h1>
        <p class="mt-2 text-center text-gray-500">新しいアカウントを作成してください</p>

        {{-- カード --}}
        <div class="mt-8 px-8 py-10 bg-white border border-gray-200 rounded-2xl">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- User Name -->
                <div>
                    <label for="username" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>USERNAME</span>
                        <x-required-badge />
                    </label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        placeholder="例：山田 太郎" required autofocus autocomplete="username"
                        class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg
                               text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error class="mt-2" :messages="$errors->get('username')" />
                </div>

                <!-- Email Address -->
                <div class="mt-6">
                    <label for="email" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>E-MAIL</span>
                        <x-required-badge />
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" placeholder="example@email.com" required autocomplete="email" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="mt-6">
                    <label for="password" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>PASSWORD</span>
                        <x-required-badge />
                    </label>
                    <input type="password" name="password" id="password" placeholder="**********" required autocomplete="new-password" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <p class="mt-1 text-sm text-gray-500">英数字８文字以上</p>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="mt-6">
                    <label for="password_confirmation" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                        <span>PASSWORD（CONFIRM）</span>
                        <x-required-badge />
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" placeholder="**********" required autocomplete="new-password" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>

                {{-- 登録ボタン --}}
                <button type="submit" class="block w-full mt-8 px-4 py-3 bg-primary rounded-lg text-white font-bold hover:opacity-90 transition">
                    Register
                </button>
            </form>

            {{-- ログイン画面への導線 --}}
            <p class="mt-6 text-center text-sm text-gray-500">
                すでにアカウントをお持ちですか？<a href="{{ route('login') }}" class="text-secondary underline">ログインはこちら</a>
            </p>
        </div>
    </div>
</x-guest-layout>
