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
                            data-target="response-rate"
                        >
                            Response Rate
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="daily-activity"
                        >
                            Daily Activity
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="monthly-stats"
                        >
                            Monthly Statistics
                        </button>
                        <button 
                            class="statistics-tab px-3 py-2 text-sm font-medium rounded-t-lg border-b-2" 
                            data-target="status-overview"
                        >
                            Status Overview
                        </button>
                    </nav>
                </div>

                <!-- Tab Contents -->
                <div class="statistics-content">
                    <!-- Response Rate Tab -->
                    <div id="response-rate" class="statistics-panel">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-600 bg-opacity-75">
                                <i class="fas fa-percentage text-white fa-2x"></i>
                            </div>
                            <div class="mx-5">
                                <h4 class="text-2xl font-semibold text-gray-700">{{ $responseRate }}%</h4>
                                <div class="text-gray-500">Response Rate</div>
                            </div>
                        </div>
                    </div>

                    <!-- Daily Activity Tab -->
                    <div id="daily-activity" class="statistics-panel hidden">
                        <div id="dailyStats"></div>
                    </div>

                    <!-- Monthly Statistics Tab -->
                    <div id="monthly-stats" class="statistics-panel hidden">
                        <div id="monthlyStats"></div>
                    </div>

                    <!-- Status Overview Tab -->
                    <div id="status-overview" class="statistics-panel hidden">
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="p-4 bg-blue-50 rounded-lg">
                                <div class="text-blue-600 text-lg font-semibold">Pending</div>
                                <div class="text-2xl font-bold">{{ $pendingCount }}</div>
                            </div>
                            <div class="p-4 bg-green-50 rounded-lg">
                                <div class="text-green-600 text-lg font-semibold">Interview</div>
                                <div class="text-2xl font-bold">{{ $interviewCount }}</div>
                            </div>
                            <div class="p-4 bg-red-50 rounded-lg">
                                <div class="text-red-600 text-lg font-semibold">Rejected</div>
                                <div class="text-2xl font-bold">{{ $rejectedCount }}</div>
                            </div>
                            <div class="p-4 bg-yellow-50 rounded-lg">
                                <div class="text-yellow-600 text-lg font-semibold">Offered</div>
                                <div class="text-2xl font-bold">{{ $offeredCount }}</div>
                            </div>
                        </div>
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

        // Daily Activity Chart
        var dailyOptions = {
            series: [{
                name: 'Applications',
                data: {!! json_encode($dailyData) !!}
            }],
            chart: {
                type: 'bar',
                height: 250
            },
            plotOptions: {
                bar: {
                    borderRadius: 4,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetY: -20,
                style: {
                    fontSize: '12px',
                    colors: ["#304758"]
                }
            },
            xaxis: {
                categories: {!! json_encode($dailyLabels) !!},
                position: 'bottom',
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                },
                tooltip: {
                    enabled: false,
                }
            },
            yaxis: {
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false,
                },
                labels: {
                    show: false,
                }
            },
            colors: ['#4f46e5']
        };

        var dailyChart = new ApexCharts(document.querySelector("#dailyStats"), dailyOptions);
        dailyChart.render();

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
                    if (tab.dataset.target === 'daily-activity') {
                        dailyChart.render();
                    } else if (tab.dataset.target === 'monthly-stats') {
                        chart.render();
                    }
                });
            });
        });
    </script>

    <!-- Add this to your existing style section or create a new one -->
    <style>
        .statistics-tab {
            @apply text-gray-500 hover:text-gray-700 border-transparent;
        }
        .active-tab {
            @apply text-indigo-600 border-indigo-600;
        }
    </style>
</x-app-layout>

