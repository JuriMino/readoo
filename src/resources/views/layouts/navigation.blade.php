<nav class="w-full bg-secondary">
    <div class="flex items-center justify-between max-w-7xl mx-auto px-6">
        {{-- ロゴ（クリックでMyPageへ） --}}
        <a href="{{ route('mypage')}}">
            <x-application-logo class="block h-20 w-auto" />
        </a>

        {{-- 右側メニュー --}}
        <div class="flex items-center space-x-3 text-sm text-white">
            <a href="{{ route('mypage') }}" class="hover:underline font-extrabold text-xl">My Page</a>
            <span class="opacity-60 font-extrabold text-xl"> | </span>
            {{-- ログアウトはPOST送信が必須なのでフォームにする --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="hover:underline font-extrabold text-xl">Logout</button>
            </form>
        </div>
    </div>
</nav>
