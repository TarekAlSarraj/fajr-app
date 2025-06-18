<x-app-layout>
    <h1 style="font-size:60px" class="text-white text-center mb-6">🏆 التحديات</h1>

    <div class="max-w-4xl mx-auto p-4 space-y-4">
        @foreach ($challenges as $challenge)
            @php
                // Find user challenge record for current user and this challenge
                $userChallenge = $userChallenges->firstWhere('pivot.challenge_id', $challenge->id);
                $lockedUntil = optional($userChallenge)->locked_until;
                $isLocked = $lockedUntil && $lockedUntil->isFuture();
                $isUnique = $challenge->is_unique;
                $hasLockDays = $challenge->lock_days > 0;
            @endphp

            <div class="bg-white dark:bg-gray-800 p-4 rounded shadow mb-4">
                <div class="flex gap-4 items-center">
                            @if($challenge->image_path)
                                <img src="{{ asset('storage/challenges/' . $challenge->image_path) }}" alt="{{ $challenge->title }}" class="w-24 h-24 object-cover rounded">
                            @else
                                <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                </div>

                <div class="flex-1">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $challenge->title }}</h3>
                    <p class="text-gray-600 dark:text-gray-300">{{ $challenge->description }}</p>

                    <div class="flex justify-between items-center mt-2">

                        <span class="text-yellow-400 font-semibold">{{ $challenge->reward_gems }} 💎</span>
                        @if ($isLocked)
                            <div class="flex items-center text-gray-400 gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.104 0 2 .896 2 2v1H10v-1c0-1.104.896-2 2-2z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 11v-2a6 6 0 1112 0v2" />
                                </svg>
                                <span>محجوز حتى {{ $lockedUntil->translatedFormat('Y-m-d H:i') }}</span>
                            </div>

                        @elseif ($userChallenge)
                            @if ($isUnique)
                                @if ($userChallenge->pivot->approved === 1)
                                    <span class="text-green-600 font-semibold">✅</span>
                                @elseif($userChallenge->pivot->approved === 0)
                                    <span class="text-yellow-600 font-semibold">❌ مرفوض</span>
                                @else
                                    <span class="text-yellow-600 font-semibold">⏳ بانتظار الموافقة</span>
                                @endif

                                @if ($hasLockDays && $lockedUntil)
                                    <div class="text-gray-500 text-sm mt-1">
                                        متاح مرة أخرى في {{ $lockedUntil->translatedFormat('Y-m-d H:i') }}
                                    </div>
                                @endif
                            @else
                                {{-- Not unique, show submit button --}}
                                <form method="POST" action="{{ route('challenges.submit', $challenge->id) }}">
                                    @csrf
                                    <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">إكمال التحدي</button>
                                </form>
                            @endif

                        @else
                            {{-- Not locked and not done yet --}}
                            <form method="POST" action="{{ route('challenges.submit', $challenge->id) }}">
                                @csrf
                                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">إكمال التحدي</button>
                            </form>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-app-layout>
