<x-app-layout>

    @if(session('message'))
    <div
        class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300"
        role="alert">
        {{ session('message') }}
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
