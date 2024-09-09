<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('İş Başvuruları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- "Yeni Başvuru Ekle" ve "Excel Olarak İndir" Butonları Yan Yana -->
                <div class="flex space-x-4 mb-6">
                    <a href="{{ route('job-applications.create') }}" class="flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Yeni Başvuru Ekle
                    </a>

                    <a href="{{ route('job-applications.export') }}" class="flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16l9 9 9-9m-9-3V3"/>
                        </svg>
                        Excel Olarak İndir
                    </a>
                </div>

                <!-- Filtreleme Formu -->
                <form method="GET" action="{{ route('job-applications.index') }}" class="mb-6">
                    <label for="search" class="block text-gray-700 mb-2">Arama:</label>
                    <input type="text" name="search" id="search" class="form-control w-full mb-4" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Ara
                    </button>
                </form>
                
                <form method="GET" action="{{ route('job-applications.index') }}" class="mb-6">
                    <label for="status" class="block text-gray-700 mb-2">Duruma Göre Filtrele:</label>
                    <select name="status" id="status" class="form-control w-full mb-4">
                        <option value="">Tüm Başvurular</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Beklemede</option>
                        <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Görüşmeye Çağrıldı</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Reddedildi</option>
                        <option value="offered" {{ request('status') == 'offered' ? 'selected' : '' }}>Teklif Alındı</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filtrele
                    </button>
                </form>

                <!-- Başvuru Listesi -->
                @if($applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto bg-white rounded-lg shadow">
                            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <tr>
                                    <th class="py-3 px-6 text-left">Pozisyon</th>
                                    <th class="py-3 px-6 text-left">Şirket</th>
                                    <th class="py-3 px-6 text-left">Başvuru Tarihi</th>
                                    <th class="py-3 px-6 text-left">Durum</th>
                                    <th class="py-3 px-6 text-left">Aksiyonlar</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700 text-sm font-light">
                                @foreach($applications as $application)
                                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                                        <td class="py-3 px-6 text-left">{{ $application->position }}</td>
                                        <td class="py-3 px-6 text-left">{{ $application->company_name }}</td>
                                        <td class="py-3 px-6 text-left">{{ $application->applied_at }}</td>
                                        <td class="py-3 px-6 text-left">
                                            @if($application->status == 'pending')
                                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Beklemede</span>
                                            @elseif($application->status == 'interview')
                                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded">Görüşmeye Çağrıldı</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="bg-red-200 text-red-800 px-2 py-1 rounded">Reddedildi</span>
                                            @elseif($application->status == 'offered')
                                                <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">Teklif Alındı</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <a href="{{ route('job-applications.show', $application->id) }}" class="text-blue-500 hover:underline">Detaylar</a>
                                            <a href="{{ route('job-applications.edit', $application->id) }}" class="text-yellow-500 hover:underline ml-4">Düzenle</a>
                                            <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST" class="inline-block ml-4">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-500 hover:underline">Sil</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Sayfalama -->
                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                @else
                    <p>Henüz başvuru bulunmuyor.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
