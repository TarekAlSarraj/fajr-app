        <div class="flex items-center justify-center gap-6 py-6">

    <!-- Avatar -->
    <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-gray-300 dark:border-gray-600">
        <img src="{{ asset('storage/avatar.png') }}" alt="User Avatar" class="object-cover w-full h-full">
    </div>

    <!-- Stats -->
    <div class="flex gap-6 text-center">

        <!-- Streak -->
        <div class="flex flex-col items-center">
            <span class="text-2xl">🔥</span>
            <span class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $streak ?? 0 }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">المتتالية</span>
        </div>

        <!-- Total Attendances -->
        <div class="flex flex-col items-center">
            <span class="text-2xl">📅</span>
            <span class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $totalAttendances ?? 0 }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">الحضور</span>
        </div>

        <!-- Gems -->
        <div class="flex flex-col items-center">
            <span class="text-2xl">💎</span>
            <span class="font-bold text-lg text-gray-900 dark:text-gray-100">{{ $gems ?? 0 }}</span>
            <span class="text-sm text-gray-500 dark:text-gray-400">الجواهر</span>
        </div>

    </div>

</div>