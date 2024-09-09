<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Başvuru Detayları') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="table-auto w-full mb-6">
                    <tr>
                        <th class="text-left px-4 py-2">Pozisyon:</th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->position }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Şirket Adı:</th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->company_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Başvuru Tarihi:</th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->applied_at }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Durum:</th>
                        <td class="text-left px-4 py-2">{{ ucfirst($jobApplication->status) }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">İş İlanı Linki:</th>
                        <td class="text-left px-4 py-2">
                            @if($jobApplication->job_listing_url)
                                <a href="{{ $jobApplication->job_listing_url }}" class="text-blue-500 hover:underline" target="_blank">İş İlanına Git</a>
                            @else
                                Yok
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Firma Web Sitesi:</th>
                        <td class="text-left px-4 py-2">
                            @if($jobApplication->company_website_url)
                                <a href="{{ $jobApplication->company_website_url }}" class="text-blue-500 hover:underline" target="_blank">Web Sitesine Git</a>
                            @else
                                Yok
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Notlar:</th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->notes }}</td>
                    </tr>
                </table>

                <a href="{{ route('job-applications.edit', $jobApplication->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Düzenle
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
