<x-app-layout>
    <h1 style="font-size:60px" class="text-white text-center">🛒 المتجر</h1>

    @if(session('success'))
    <div
        class="mb-4 p-3 rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300"
        role="success">
        {{ session('success') }}
    </div>
    @endif
    
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

    <div class="max-w-4xl mx-auto p-4 space-y-8">
        @foreach ($itemsByCategory as $category => $group)
            <div>
                <h2 class="text-2xl font-bold text-white mb-4">{{ $category }}</h2>

                @foreach ($group as $item)
                    @php
                        $cardClass = $item->name === 'اعتكاف قرآني'
                            ? 'bg-green-100 dark:bg-green-800'
                            : 'bg-white dark:bg-gray-800';
                    @endphp

                    <div class="{{ $cardClass }} p-4 rounded shadow mb-4">
                        <div class="flex gap-4 items-center">
                            @if($item->image_path)
                                <img src="{{ asset('storage/items/' . $item->image_path) }}" alt="{{ $item->name }}" class="w-24 h-24 object-cover rounded">
                            @else
                                <div class="w-24 h-24 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center text-gray-400">No Image</div>
                            @endif

                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $item->name }}</h3>
                                <p class="text-gray-600 dark:text-gray-300">{{ $item->description }}</p>

                                <div class="flex justify-between items-center mt-2">
                                    <span class="text-yellow-400 font-semibold">{{ $item->price }} 💎</span>

                                    {{-- Buy Button --}}
                                    @if ($item->can_buy)
                                        <form method="POST" action="{{route('items.buy', $item->id)}}">
                                            @csrf
                                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded">شراء</button>
                                        </form>

                                    {{-- Already Bought or Locked --}}
                                    @else
                                        <span class="text-gray-400 text-sm">
                                            @if ($item->locked_until)
                                                مغلق حتى {{ $item->locked_until->translatedFormat('Y-m-d H:i') }}
                                            @else
                                                تم الشراء ✅
                                            @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
</x-app-layout>
