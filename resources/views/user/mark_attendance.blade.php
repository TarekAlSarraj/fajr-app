<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">تسجيل حضور وتسميع</h1>

        @if(session('message'))
            <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
                {{ session('message') }}
            </div>
        @endif

        <form method="POST" action="{{ route('attendance.submit') }}" class="space-y-6">
            @csrf

            <div>
                <label for="event_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    نوع الموعد
                </label>
                <select id="event_id" name="event_id" required
                    class="w-full px-3 py-2 mb-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    <option value="" disabled selected>اختر نوع الموعد</option>
                    @foreach ($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }}</option>
                    @endforeach
                </select>

                <!-- <label for="surah_name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    السورة
                </label>
                <select id="surah_name" name="surah_name" required
                    class="w-full px-3 mb-2 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    <option value="" disabled selected>السورة</option>
                    @foreach ($surahs as $surah)
                        <option value="{{ $surah }}">{{ $surah }}</option>
                    @endforeach
                </select>

                <label for="from_verse" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    من الآية
                </label>

                <input type="number" id="from_verse" name="from_verse" required class="mb-2 w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

                <label for="to_verse" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                    إلي الآية
                </label>
                <input type="number" id="to_verse" name="to_verse" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
 -->


                @error('event_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-indigo-500">
                تسجيل
            </button>
        </form>
    </div>
</x-app-layout>
