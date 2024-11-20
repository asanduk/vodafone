<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- General Information Content -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Welcome!</h3>
                <p class="text-gray-700 mb-4">
                    This application helps you track and manage your job applications. You can start using the application with the following steps:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4">
                    <li><strong>Add New Job Application:</strong> You can add new applications for positions you've applied to.</li>
                    <li><strong>Manage Your Applications:</strong> You can update the status of your applications, add notes, and review application details.</li>
                    <li><strong>Status Tracking:</strong> You can track the status of your applications (pending, invited for interview, rejected, offer received).</li>
                    <li><strong>Export to Excel:</strong> You can export all your applications in Excel format.</li>
                </ul>
                <p class="text-gray-700">
                    You can get an overview of your applications from the statistics below. Also, you can use the relevant buttons to add new applications or manage your existing applications.
                </p>
            </div>

            <!-- Statistic Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Pending Applications -->
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-blue-200 rounded-t-lg">
                        <i class="fas fa-clock fa-3x text-blue-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pending Applications</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $pendingCount }} Applications</p>
                    </div>
                </a>

                <!-- Invited for Interview Applications -->
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-green-200 rounded-t-lg">
                        <i class="fas fa-user-check fa-3x text-green-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Invited for Interview</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $interviewCount }} Applications</p>
                    </div>
                </a>

                <!-- Rejected Applications -->
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-red-200 rounded-t-lg">
                        <i class="fas fa-times-circle fa-3x text-red-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rejected Applications</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $rejectedCount }} Applications</p>
                    </div>
                </a>

                <!-- Offer Received Applications -->
                <a href="#" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-yellow-200 rounded-t-lg">
                        <i class="fas fa-gift fa-3x text-yellow-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Offer Received</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $offeredCount }} Applications</p>
                    </div>
                </a>
            </div>

            <!-- Manage Job Applications and Add New Application Buttons -->
            <div class="mt-8 flex space-x-4">
                <a href="{{ route('job-applications.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Manage Job Applications
                </a>
                
                <a href="{{ route('job-applications.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Add New Job Application
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
