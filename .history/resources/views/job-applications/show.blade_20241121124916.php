<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Application Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <table class="table-auto w-full mb-6">
                    <thead>
                        <tr>
                            <th class="text-left px-4 py-2">
                                <div class="flex items-center cursor-pointer" onclick="sortTable('position')">
                                    Position
                                    <span class="ml-1">↕️</span>
                                </div>
                            </th>
                            <th class="text-left px-4 py-2">
                                <div class="flex items-center cursor-pointer" onclick="sortTable('company_name')">
                                    Company Name
                                    <span class="ml-1">↕️</span>
                                </div>
                            </th>
                            <th class="text-left px-4 py-2">
                                <div class="flex items-center cursor-pointer" onclick="sortTable('applied_at')">
                                    Application Date
                                    <span class="ml-1">↕️</span>
                                </div>
                            </th>
                            <th class="text-left px-4 py-2">
                                <div class="relative">
                                    <select id="statusFilter" onchange="filterByStatus(this.value)" class="cursor-pointer appearance-none bg-transparent">
                                        <option value="">All Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="interviewing">Interviewing</option>
                                        <option value="accepted">Accepted</option>
                                    </select>
                                </div>
                            </th>
                            <th class="text-left px-4 py-2">Status:</th>
                            <th class="text-left px-4 py-2">Job Listing URL:</th>
                            <th class="text-left px-4 py-2">Company Website:</th>
                            <th class="text-left px-4 py-2">Notes:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-left px-4 py-2" data-column="position">{{ $jobApplication->position }}</td>
                            <td class="text-left px-4 py-2" data-column="company_name">{{ $jobApplication->company_name }}</td>
                            <td class="text-left px-4 py-2" data-column="applied_at">{{ $jobApplication->applied_at }}</td>
                            <td class="text-left px-4 py-2" data-column="status">{{ ucfirst($jobApplication->status) }}</td>
                            <td class="text-left px-4 py-2" data-column="job_listing_url">
                                @if($jobApplication->job_listing_url)
                                    <a href="{{ $jobApplication->job_listing_url }}" class="text-blue-500 hover:underline" target="_blank">Go to Job Listing</a>
                                @else
                                    None
                                @endif
                            </td>
                            <td class="text-left px-4 py-2" data-column="company_website_url">
                                @if($jobApplication->company_website_url)
                                    <a href="{{ $jobApplication->company_website_url }}" class="text-blue-500 hover:underline" target="_blank">Go to Website</a>
                                @else
                                    None
                                @endif
                            </td>
                            <td class="text-left px-4 py-2" data-column="notes">{{ $jobApplication->notes }}</td>
                        </tr>
                    </tbody>
                </table>

                <a href="{{ route('job-applications.edit', $jobApplication->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
    </div>

    <script>
        let sortDirection = {};
        
        function sortTable(column) {
            const table = document.querySelector('table');
            const tbody = table.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));

            // Toggle sort direction
            sortDirection[column] = !sortDirection[column];

            rows.sort((a, b) => {
                let aValue = a.querySelector(`td[data-column="${column}"]`).textContent;
                let bValue = b.querySelector(`td[data-column="${column}"]`).textContent;

                if (column === 'applied_at') {
                    aValue = new Date(aValue);
                    bValue = new Date(bValue);
                }

                if (aValue < bValue) return sortDirection[column] ? -1 : 1;
                if (aValue > bValue) return sortDirection[column] ? 1 : -1;
                return 0;
            });

            // Clear and re-append sorted rows
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }

        function filterByStatus(status) {
            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const statusCell = row.querySelector('td[data-column="status"]');
                if (!status || statusCell.textContent.toLowerCase() === status.toLowerCase()) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    </script>
</x-app-layout>
