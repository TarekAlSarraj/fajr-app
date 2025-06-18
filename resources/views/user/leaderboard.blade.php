<x-app-layout>
<div class="flex items-center justify-center ">
        <div class="w-full max-w-3xl p-6 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
            <h1 class="text-3xl font-bold text-center text-gray-800 dark:text-gray-100 mb-6">
                قائمة المتصدّرين
            </h1>

            <div class="overflow-x-auto justify-center flex">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600 text-center">
                    <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase font-semibold text-gray-700 dark:text-gray-300">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">الاسم</th>
                            <th class="px-4 py-2">الحضور الكلّي</th>
                            <th class="px-4 py-2">الحضور المتتالي</th>
                            <th class="px-4 py-2">الجواهر</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($leaderboard as $index => $entry)
                            <tr class="{{ $index === 0 ? 'bg-yellow-100 dark:bg-yellow-900' : '' }}">
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100">{{ $entry['name'] }}</td>
                                <td class="px-4 py-2 text-gray-700 dark:text-gray-300">📅 {{ $entry['total'] }}</td>
                                <td class="px-4 py-2 font-semibold text-green-600 dark:text-green-400">🔥 {{ $entry['streak'] }}</td>
                                <td class="px-4 py-2 font-semibold text-blue-600 dark:text-blue-400">💎 {{ $entry['gems'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
