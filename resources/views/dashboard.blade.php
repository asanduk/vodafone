<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Genel Bilgilendirme İçeriği -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Hoşgeldiniz!</h3>
                <p class="text-gray-700 mb-4">
                    Bu uygulama, iş başvurularınızı takip etmenize ve yönetmenize yardımcı olur. Aşağıdaki adımlarla uygulamayı kullanmaya başlayabilirsiniz:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    <li><strong>Yeni İş Başvurusu Ekleyin:</strong> Başvurduğunuz pozisyonlar için yeni başvurular ekleyebilirsiniz.</li>
                    <li><strong>Başvurularınızı Yönetin:</strong> Başvurularınızın durumunu güncelleyebilir, notlar ekleyebilir ve başvuru detaylarını inceleyebilirsiniz.</li>
                    <li><strong>Durum Takibi:</strong> Başvurularınızın durumunu (beklemede, görüşmeye çağrıldı, reddedildi, teklif alındı) takip edebilirsiniz.</li>
                    <li><strong>Excel'e Aktarın:</strong> Tüm başvurularınızı Excel formatında dışa aktarabilirsiniz.</li>
                </ul>
                <p class="text-gray-700">
                    Aşağıdaki istatistiklerden başvurularınıza genel bir bakış elde edebilirsiniz. Ayrıca, yeni başvurular eklemek veya mevcut başvurularınızı yönetmek için ilgili butonları kullanabilirsiniz.
                </p>
            </div>

            <!-- İstatistik Kartları -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Beklemede Başvurular -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Beklemede</h3>
                    <p>{{ $pendingCount }} Başvuru</p>
                </div>

                <!-- Görüşmeye Çağrılan Başvurular -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Görüşmeye Çağrıldı</h3>
                    <p>{{ $interviewCount }} Başvuru</p>
                </div>

                <!-- Reddedilen Başvurular -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Reddedildi</h3>
                    <p>{{ $rejectedCount }} Başvuru</p>
                </div>

                <!-- Teklif Alınan Başvurular -->
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h3 class="text-lg font-semibold mb-4">Teklif Alındı</h3>
                    <p>{{ $offeredCount }} Başvuru</p>
                </div>
            </div>

            <!-- İş Başvurularını Yönet ve Yeni Başvuru Ekle Butonları -->
            <div class="mt-8 flex space-x-4">
                <a href="{{ route('job-applications.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    İş Başvurularını Yönet
                </a>
                
                <a href="{{ route('job-applications.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Yeni İş Başvurusu Ekle
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
