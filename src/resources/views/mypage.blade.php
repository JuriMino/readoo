<x-app-layout>
    <div class="max-w-7xl mx-auto px-6 py-12">
        {{-- 見出し行：左にタイトル＋挨拶、右にEdit Profile --}}
        <div class="flex items-start justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900">My Page</h1>
                <p class="mt-2 text-gray-500">Hello! {{ Auth::user()->usernameさん }}</p>
            </div>
            <a href="{{ route('profile.edit') }}" class="px-5 py-2 border border-secondary rounded-lg text-secondary font-bold hover:bg-secondary hover:text-white transition">Edit Profile

            </a>
        </div>

        {{-- コレクション一覧 --}}
        <h2 class="mt-12 text-lg font-bold tracking-wider text-gray-400">YOUR COLLECTIONS</h2>
        <div class="mt-4 bg-white border border-gray-200 rounded-2xl divide-y divide-gray-200">
            {{-- 本 --}}
            <a href="#" class="flex items-center px-6 py-5 hover:bg-gray-50 transition">
                <span class="w-3 h-3 bg-book rounded-full"></span>
                <div class="flex-1 ml-4">
                    <p class="text-xl font-bold text-gray-900">Book List</p>
                    <p class="text-sm text-gray-500">読んだ本・読みたい本を管理する</p>
                </div>
                <span class="text-gray-400">&gt;</span>
            </a>
            {{-- 知識 --}}
            <a href="#" class="flex items-center px-6 py-5 hover:bg-gray-50 transition">
                <span class="w-3 h-3 bg-knowledge rounded-full"></span>
                <div class="flex-1 ml-4">
                    <p class="text-xl font-bold text-gray-900">Knowledge List</p>
                    <p class="text-sm text-gray-500">本から得た知識を記録する</p>
                </div>
                <span class="text-gray-400">&gt;</span>
            </a>
            {{-- 行動 --}}
            <a href="#" class="flex items-center px-6 py-5 hover:bg-gray-50 transition">
                <span class="w-3 h-3 bg-action rounded-full"></span>
                <div class="flex-1 ml-4">
                    <p class="text-xl font-bold text-gray-900">Action List</p>
                    <p class="text-sm text-gray-500">本・知識から生まれた行動を管理する</p>
                </div>
                <span class="text-gray-400">&gt;</span>
            </a>
        </div>
    </div>
</x-app-layout>
