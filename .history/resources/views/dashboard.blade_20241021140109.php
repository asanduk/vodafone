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
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <h3 class="text-lg font-semibold mb-4 text-blue-600 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-3-3H9m3-4a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        Pending
                    </h3>
                    <p class="text-2xl font-bold">{{ $pendingCount }}</p>
                    <p class="text-gray-500">Applications</p>
                </div>

                <!-- Invited for Interview Applications -->
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <h3 class="text-lg font-semibold mb-4 text-green-600 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Invited for Interview
                    </h3>
                    <p class="text-2xl font-bold">{{ $interviewCount }}</p>
                    <p class="text-gray-500">Applications</p>
                </div>

                <!-- Rejected Applications -->
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <h3 class="text-lg font-semibold mb-4 text-red-600 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Rejected
                    </h3>
                    <p class="text-2xl font-bold">{{ $rejectedCount }}</p>
                    <p class="text-gray-500">Applications</p>
                </div>

                <!-- Offer Received Applications -->
                <div class="bg-white p-6 rounded-lg shadow-lg transition-transform transform hover:scale-105">
                    <h3 class="text-lg font-semibold mb-4 text-yellow-600 flex items-center">
                        <svg class="h-6 w-6 mr-2 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-3-3H9m3-4a4 4 0 100 8 4 4 0 000-8z" />
                        </svg>
                        Offer Received
                    </h3>
                    <p class="text-2xl font-bold">{{ $offeredCount }}</p>
                    <p class="text-gray-500">Applications</p>
                </div>
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
