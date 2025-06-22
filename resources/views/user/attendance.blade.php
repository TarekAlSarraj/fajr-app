<x-app-layout>
    <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded-lg shadow-md dark:bg-gray-800">
        
        @if(session('message'))
        <div class="mb-4 p-3 rounded bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
            {{ session('message') }}
        </div>
        @endif

         @if(session('success'))
        <div class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300">
            {{ session('success') }}
        </div>
        @endif
        
        <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-100 mb-6">تسجيل حضور وتسميع</h1>
        
        <form method="POST" action="{{ route('attendance.submit') }}" class="space-y-6">
            @csrf

            {{-- Event selection --}}
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

                @error('event_id')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            {{-- Recitations --}}
            <div>
                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">التسميع</label>
                <div id="recitations" class="space-y-4">
                    <div class="recitation flex gap-2 items-center">
                        <select name="recitations[0][surah_name]" required
                            class="w-1/2 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                            <option value="" disabled selected>السورة</option>
                            @foreach($surahs as $surah)
                                <option value="{{ $surah }}">{{ $surah }}</option>
                            @endforeach
                        </select>

                        <input type="number" name="recitations[0][from_verse]" required placeholder="من آية"
                            class="w-1/4 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

                        <input type="number" name="recitations[0][to_verse]" required placeholder="إلى آية"
                            class="w-1/4 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

                        <button type="button" class="remove-recitation text-red-500 hover:text-red-700 text-xl">&times;</button>
                    </div>
                </div>

                <button type="button" id="add-recitation"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded mt-2">+ إضافة سورة أخرى</button>
            </div>

            {{-- Arrived before 6:00 AM checkbox --}}
            <div class="flex items-center">
                <input id="arrived_early" type="checkbox" name="arrived_early"
                    class="h-4 w-4 ml-2 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-indigo-400">
                <label for="arrived_early" class="ml-2 block text-gray-700 dark:text-gray-300 text-sm">
                    وصلت قبل الساعة 6:00 صباحًا
                </label>
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-md shadow focus:outline-none focus:ring-2 focus:ring-indigo-500">
                تسجيل
            </button>
        </form>
    </div>

    <script>
let recitationIndex = 1;
const recitationsDiv = document.getElementById('recitations');

function updateRemoveButtons() {
    const recitations = recitationsDiv.querySelectorAll('.recitation');
    recitations.forEach((recitation, index) => {
        const removeBtn = recitation.querySelector('.remove-recitation');
        if (removeBtn) {
            removeBtn.style.display = recitations.length > 1 ? 'inline-block' : 'none';
        }
    });
}

// Initial setup
updateRemoveButtons();

document.getElementById('add-recitation').addEventListener('click', function () {
    const newRecitation = document.createElement('div');
    newRecitation.classList.add('recitation', 'flex', 'gap-2', 'items-center');

    newRecitation.innerHTML = `
        <select name="recitations[${recitationIndex}][surah_name]" required
            class="w-1/2 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
            <option value="" disabled selected>السورة</option>
            @foreach($surahs as $surah)
                <option value="{{ $surah }}">{{ $surah }}</option>
            @endforeach
        </select>

        <input type="number" name="recitations[${recitationIndex}][from_verse]" required placeholder="من آية"
            class="w-1/4 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

        <input type="number" name="recitations[${recitationIndex}][to_verse]" required placeholder="إلى آية"
            class="w-1/4 px-2 py-2 border rounded dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">

        <button type="button" class="remove-recitation text-red-500 hover:text-red-700 text-xl">&times;</button>
    `;
    recitationsDiv.appendChild(newRecitation);
    recitationIndex++;
    updateRemoveButtons();
});

recitationsDiv.addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-recitation')) {
        e.target.parentElement.remove();
        updateRemoveButtons();
    }
});


    </script>
</x-app-layout>
