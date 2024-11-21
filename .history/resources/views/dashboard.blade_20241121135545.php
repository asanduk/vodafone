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
                <h3 class="text-lg font-semibold mb-4 text-center">Welcome!</h3>
                <p class="text-gray-700 mb-4 text-center">
                    This application helps you track and manage your job applications. You can start using the application with the following steps:
                </p>
                <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <strong>Add New Job Application:</strong> You can add new applications for positions you've applied to.
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <strong>Manage Your Applications:</strong> You can update the status of your applications, add notes, and review application details.
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <strong>Status Tracking:</strong> You can track the status of your applications (pending, invited for interview, rejected, offer received).
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle text-green-500 mr-2"></i>
                        <strong>Export to Excel:</strong> You can export all your applications in Excel format.
                    </li>
                </ul>
                <p class="text-gray-700 text-center">
                    You can get an overview of your applications from the statistics below. Also, you can use the relevant buttons to add new applications or manage your existing applications.
                </p>

                <!-- Moved buttons here -->
                <div class="mt-8 flex space-x-4">
                    <a href="{{ route('job-applications.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-edit"></i> Manage Job Applications
                    </a>
                    
                    <a href="{{ route('job-applications.create') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-plus"></i> Add New Job Application
                    </a>
                </div>
            </div>

            <!-- Statistics Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                <h3 class="text-lg font-semibold mb-4">Application Statistics</h3>
                
                <!-- Tab Navigation -->
                <div class="border-b border-gray-200 mb-6">
                    <nav class="flex space-x-4" aria-label="Statistics Navigation">
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2 active-tab" 
                            data-target="status-overview"
                        >
                            <i class="fas fa-chart-pie mr-2"></i>Status Overview
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="response-rate"
                        >
                            <i class="fas fa-percentage mr-2"></i>Response Rate
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="monthly-stats"
                        >
                            <i class="fas fa-chart-line mr-2"></i>Monthly Statistics
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="statistics-content">
                    <!-- Status Overview Tab (Default) -->
                    <div id="status-overview" class="statistics-panel">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <a href="{{ route('job-applications.index', ['status' => 'pending']) }}" 
                               class="p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-blue-600 text-lg font-semibold">Pending</div>
                                        <div class="text-2xl font-bold">{{ $pendingCount }}</div>
                                    </div>
                                    <i class="fas fa-hourglass-half text-blue-600 text-2xl"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('job-applications.index', ['status' => 'interview']) }}" 
                               class="p-4 bg-green-50 rounded-lg hover:bg-green-100 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-green-600 text-lg font-semibold">Interview</div>
                                        <div class="text-2xl font-bold">{{ $interviewCount }}</div>
                                    </div>
                                    <i class="fas fa-user-tie text-green-600 text-2xl"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('job-applications.index', ['status' => 'rejected']) }}" 
                               class="p-4 bg-red-50 rounded-lg hover:bg-red-100 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-red-600 text-lg font-semibold">Rejected</div>
                                        <div class="text-2xl font-bold">{{ $rejectedCount }}</div>
                                    </div>
                                    <i class="fas fa-times-circle text-red-600 text-2xl"></i>
                                </div>
                            </a>
                            
                            <a href="{{ route('job-applications.index', ['status' => 'offered']) }}" 
                               class="p-4 bg-yellow-50 rounded-lg hover:bg-yellow-100 transition duration-300">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <div class="text-yellow-600 text-lg font-semibold">Offered</div>
                                        <div class="text-2xl font-bold">{{ $offeredCount }}</div>
                                    </div>
                                    <i class="fas fa-check-circle text-yellow-600 text-2xl"></i>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Enhanced Response Rate Tab -->
                    <div id="response-rate" class="statistics-panel hidden">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Main Response Rate Card -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <div class="flex items-center mb-4">
                                    <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                                        <i class="fas fa-percentage text-white fa-2x"></i>
                                    </div>
                                    <div class="mx-5">
                                        <h4 class="text-3xl font-semibold text-gray-700">{{ $responseRate }}%</h4>
                                        <div class="text-gray-500">Overall Response Rate</div>
                                    </div>
                                </div>
                                <div class="text-sm text-gray-600 mt-2">
                                    Based on {{ $totalApplications }} total applications
                                </div>
                            </div>

                            <!-- Response Breakdown -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-700 mb-4">Response Breakdown</h4>
                                <div class="space-y-4">
                                    <!-- Interview Rate -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class="fas fa-user-tie text-green-600 mr-2"></i>
                                            <span>Interview Rate</span>
                                        </div>
                                        <span class="font-semibold">
                                            {{ $totalApplications > 0 ? round(($interviewCount / $totalApplications) * 100) : 0 }}%
                                        </span>
                                    </div>

                                    <!-- Rejection Rate -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class="fas fa-times-circle text-red-600 mr-2"></i>
                                            <span>Rejection Rate</span>
                                        </div>
                                        <span class="font-semibold">
                                            {{ $totalApplications > 0 ? round(($rejectedCount / $totalApplications) * 100) : 0 }}%
                                        </span>
                                    </div>

                                    <!-- Offer Rate -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class="fas fa-check-circle text-yellow-600 mr-2"></i>
                                            <span>Offer Rate</span>
                                        </div>
                                        <span class="font-semibold">
                                            {{ $totalApplications > 0 ? round(($offeredCount / $totalApplications) * 100) : 0 }}%
                                        </span>
                                    </div>

                                    <!-- Pending Rate -->
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center">
                                            <i class="fas fa-hourglass-half text-blue-600 mr-2"></i>
                                            <span>Pending Rate</span>
                                        </div>
                                        <span class="font-semibold">
                                            {{ $totalApplications > 0 ? round(($pendingCount / $totalApplications) * 100) : 0 }}%
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Statistics -->
                            <div class="bg-white rounded-lg p-6 border border-gray-200">
                                <h4 class="text-lg font-semibold text-gray-700 mb-4">Response Statistics</h4>
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Total Applications</span>
                                        <span class="font-semibold">{{ $totalApplications }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Responded Applications</span>
                                        <span class="font-semibold">{{ $respondedApplications }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Awaiting Response</span>
                                        <span class="font-semibold">{{ $totalApplications - $respondedApplications }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Monthly Statistics Tab -->
                    <div id="monthly-stats" class="statistics-panel hidden">
                        <div id="monthlyStats"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        var options = {
            series: [{
                name: 'Applications',
                data: {!! json_encode($monthlyData) !!}
            }],
            chart: {
                type: 'area',
                height: 350,
                zoom: {
                    enabled: false
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                curve: 'smooth'
            },
            title: {
                text: 'Application Trends',
                align: 'left'
            },
            grid: {
                row: {
                    colors: ['#f3f3f3', 'transparent'],
                    opacity: 0.5
                },
            },
            xaxis: {
                categories: {!! json_encode($monthlyLabels) !!},
            },
            yaxis: {
                title: {
                    text: 'Number of Applications'
                },
                min: 0,
                forceNiceScale: true,
                labels: {
                    formatter: function (value) {
                        return Math.round(value);
                    }
                }
            },
            colors: ['#4f46e5'],
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.7,
                    opacityTo: 0.9,
                    stops: [0, 90, 100]
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value + ' Applications';
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#monthlyStats"), options);
        chart.render();

        // Tab functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('.statistics-tab');
            const panels = document.querySelectorAll('.statistics-panel');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    // Remove active class from all tabs
                    tabs.forEach(t => {
                        t.classList.remove('active-tab');
                        t.classList.add('border-transparent');
                    });

                    // Add active class to clicked tab
                    tab.classList.add('active-tab');
                    tab.classList.remove('border-transparent');

                    // Hide all panels
                    panels.forEach(panel => {
                        panel.classList.add('hidden');
                    });

                    // Show selected panel
                    const targetPanel = document.getElementById(tab.dataset.target);
                    targetPanel.classList.remove('hidden');

                    // Trigger chart resize if needed
                    if (tab.dataset.target === 'monthly-stats') {
                        chart.render();
                    }
                });
            });
        });
    </script>

    <!-- Add this to your existing style section -->
    <style>
        .statistics-tab {
            @apply text-gray-500 hover:text-gray-700 border-transparent transition duration-300 flex items-center;
        }
        .active-tab {
            @apply text-indigo-600 border-indigo-600;
        }
    </style>
</x-app-layout>

