<section class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
    {{-- カードヘッダー（青帯） --}}
    <div class="px-6 py-3 bg-blue-50 border-b border-gray-200">
        <h2 class="font-bold text-secondary">プロフィール情報</h2>
    </div>
    <form action="{{ route('profile.update') }}" method="post" class="px-6 py-6 space-y-5">
        @csrf
        @method('patch')
        {{-- ユーザー名 --}}
        <div>
            <label for="username" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                <span>USER NAME</span>
                <x-required-badge />
            </label>
            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}" placeholder="例：山田 太郎" required autofocus autocomplete="username" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
            <x-input-error class="mt-2" :messages="$errors->get('username')" />
        </div>
        {{-- メールアドレス --}}
        <div>
            <label for="email" class="flex items-center space-x-2 text-sm font-bold text-gray-700">
                <span>E-MAIL</span>
                <x-required-badge />
            </label>
            <input type="email" name="email" id="email" value="{{ old('email', $user->email)}}" placeholder="example@email.com" required autocomplete="username" class="block w-full mt-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-400 focus:border-secondary focus:ring-secondary">
            <x-input-error class="mt-2" :messages="$errors->get('email')" />
        </div>
        {{-- 保存ボタン --}}
        <div class="flex items-center justify-end gap-3">
            @if(session('status') === 'profile-updated')
            <p x-data="{ show:true }" x-show="show" x-transition x-init="setTimeout(() => false,2000)" class="text-sm text-gray-500">
                保存しました
            </p>
            @endif
            <button type="submit" class="px-6 py-2 bg-secondary rounded-lg text-white font-bold hover:opacity-90 transistion">Update</button>
        </div>
    </form>

</section>
