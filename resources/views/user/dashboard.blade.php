<x-app-layout>

    @if(session('message'))
    <div
        class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300"
        role="alert">
        {{ session('message') }}
    </div>
    @endif

    @if(session('error'))
    <div
        class="mb-4 p-3 rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300"
        role="error">
        {{ session('error') }}
    </div>
    @endif

    {{-- 🎁 Streak Reward Card --}}
    @if ($nextClaim)
        <div class="max-w-xl mx-auto mt-6 mb-10">
            <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-300 dark:border-yellow-800 rounded-xl shadow p-5 text-center">
                <h2 class="text-2xl font-bold text-yellow-700 dark:text-yellow-300 mb-3">🎉 مكافأة سلسلة الحضور!</h2>
                <p class="text-gray-700 dark:text-gray-300 mb-2">لقد وصلت إلى <strong>{{ $nextClaim['days'] }} أيام</strong>!</p>
                <div class="flex justify-center items-center text-yellow-500 text-3xl font-bold mb-4 gap-1">
                    {{ $nextClaim['reward'] }} <span>💎</span>
                </div>
                <form method="POST" action="{{ route('streak.claim', $nextClaim['days']) }}">
                    @csrf
                    <button class="bg-yellow-400 hover:bg-yellow-500 text-white font-semibold py-2 px-6 rounded-md transition">استلام المكافأة</button>
                </form>
            </div>
        </div>
    @endif

   <div class="py-12 rtl text-right">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Leaderboard -->
            <a href="{{ route('leaderboard') }}" class="block group">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl transition transform group-hover:scale-105 group-hover:shadow-2xl p-6 text-center">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 flex flex-col items-center gap-4">
                        <img src="{{ asset('storage/leaderboard-icon.png') }}" alt="Leaderboard" class="w-24 h-24 object-contain">
                        المتصدّرين
                    </h1>
                </div>
            </a>

            <!-- Shop -->
            <a href="{{ route('shop') }}" class="block group">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl transition transform group-hover:scale-105 group-hover:shadow-2xl p-6 text-center">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 flex flex-col items-center gap-4">
                        <img src="{{ asset('storage/store-icon.png') }}" alt="shop" class="w-20 h-20 object-contain">
                        المتجر
                    </h1>
                </div>
            </a>

            <!-- Challenges -->
            <a href="{{ route('challenges') }}" class="block group">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl transition transform group-hover:scale-105 group-hover:shadow-2xl p-6 text-center">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-gray-100 flex flex-col items-center gap-4">
                        <img src="{{ asset('storage/challenges-icon.png') }}" alt="Challenges" class="w-24 h-24 object-contain">
                        التحدّيات
                    </h1>
                </div>
            </a>
        </div>
    </div>

</x-app-layout>
