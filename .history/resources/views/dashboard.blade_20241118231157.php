<x-app-layout>
    <x-slot name="header">
            {{ __('Dashboard') }}
        </h2>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
                <a href="{{ route('job-applications.index', ['status' => 'pending']) }}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-blue-200 rounded-t-lg p-4">
                        <i class="fas fa-clock fa-3x text-blue-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Pending Applications</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $pendingCount }} Applications</p>
                    </div>
                </a>

                <!-- Invited for Interview Applications -->
                <a href="{{ route('job-applications.index', ['status' => 'interview']) }}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-green-200 rounded-t-lg p-4">
                        <i class="fas fa-user-check fa-3x text-green-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Invited for Interview</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $interviewCount }} Applications</p>
                    </div>
                </a>

                <!-- Rejected Applications -->
                <a href="{{ route('job-applications.index', ['status' => 'rejected']) }}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-red-200 rounded-t-lg p-4">
                        <i class="fas fa-times-circle fa-3x text-red-600"></i>
                    </div>
                    <div class="flex flex-col justify-between p-4 leading-normal">
                        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Rejected Applications</h5>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">{{ $rejectedCount }} Applications</p>
                    </div>
                </a>

                <!-- Offer Received Applications -->
                <a href="{{ route('job-applications.index', ['status' => 'offered']) }}" class="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700">
                    <div class="flex items-center justify-center w-full h-48 md:h-auto md:w-48 bg-yellow-200 rounded-t-lg p-4">
                        <i class="fas fa-trophy fa-3x text-black"></i>
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

            <!-- New Section for Application Statistics -->
            <div class="max-w-sm w-full bg-white rounded-lg shadow dark:bg-gray-800 p-4 md:p-6">
                <div class="flex justify-between">
                    <div>
                        <h5 class="leading-none text-3xl font-bold text-gray-900 dark:text-white pb-2">{{ $interviewCount }}</h5>
                        <p class="text-base font-normal text-gray-500 dark:text-gray-400">Total Applications</p>
                    </div>
                    <div class="flex items-center px-2.5 py-0.5 text-base font-semibold text-green-500 dark:text-green-500 text-center">
                        {{ $interviewCount }}%
                        <svg class="w-3 h-3 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/>
                        </svg>
                    </div>
                </div>
                <div id="area-chart"></div>
                <div class="grid grid-cols-1 items-center border-gray-200 border-t dark:border-gray-700 justify-between">
                    <div class="flex justify-between items-center pt-5">
                        <!-- Button -->
                        <button id="dropdownDefaultButton" data-dropdown-toggle="lastDaysdropdown" data-dropdown-placement="bottom" class="text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-gray-900 text-center inline-flex items-center dark:hover:text-white" type="button">
                            Last 7 days
                            <svg class="w-2.5 m-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                            </svg>
                        </button>
                        <!-- Dropdown menu -->
                        <div id="lastDaysdropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                            <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Yesterday</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Today</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 7 days</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 30 days</a></li>
                                <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Last 90 days</a></li>
                            </ul>
                        </div>
                        <a href="#" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 hover:bg-gray-100 dark:hover:bg-gray-700 dark:focus:ring-gray-700 dark:border-gray-700 px-3 py-2">
                            Users Report
                            <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>

            <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
            <script>
                var options = {
                    chart: {
                        type: 'area',
                        height: 350
                    },
                    series: [{
                        name: 'Applications',
                        data: [{{ $pendingCount }}, {{ $interviewCount }}, {{ $rejectedCount }}, {{ $offeredCount }}]
                    }],
                    xaxis: {
                        categories: ['Pending', 'Interview', 'Rejected', 'Offered']
                    }
                };

                var chart = new ApexCharts(document.querySelector("#area-chart"), options);
                chart.render();
            </script>

        </div>
    </div>
</x-app-layout>
