<div class="flex items-center space-x-4 mb-4">
    {{-- Geri Butonu --}}
    @if(url()->previous() !== url()->current())
        <button onclick="window.history.back()" 
                class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>
            Back
        </button>
    @endif

    {{-- Ana Sayfa Butonu --}}
    @if(url()->current() !== route('dashboard'))
        <a href="{{ route('dashboard') }}" 
           class="flex items-center px-4 py-2 bg-indigo-500 hover:bg-indigo-600 text-white rounded-lg transition duration-200">
            <i class="fas fa-home mr-2"></i>
            Dashboard
        </a>
    @endif

    {{-- İleri Butonu --}}
    @if(url()->next())
        <button onclick="window.history.forward()" 
                class="flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition duration-200">
            Forward
            <i class="fas fa-arrow-right ml-2"></i>
        </button>
    @endif
</div> 