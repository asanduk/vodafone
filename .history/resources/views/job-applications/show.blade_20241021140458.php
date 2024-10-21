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
                    <tr>
                        <th class="text-left px-4 py-2">
                            <a href="{{ route('job-applications.index', ['sort' => 'position', 'order' => 'asc']) }}">Position (A-Z)</a> |
                            <a href="{{ route('job-applications.index', ['sort' => 'position', 'order' => 'desc']) }}">Position (Z-A)</a>
                        </th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->position }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">
                            <a href="{{ route('job-applications.index', ['sort' => 'company_name', 'order' => 'asc']) }}">Company Name (A-Z)</a> |
                            <a href="{{ route('job-applications.index', ['sort' => 'company_name', 'order' => 'desc']) }}">Company Name (Z-A)</a>
                        </th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->company_name }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">
                            <a href="{{ route('job-applications.index', ['sort' => 'applied_at', 'order' => 'asc']) }}">Application Date (Oldest to Newest)</a> |
                            <a href="{{ route('job-applications.index', ['sort' => 'applied_at', 'order' => 'desc']) }}">Application Date (Newest to Oldest)</a>
                        </th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->applied_at }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">
                            <a href="{{ route('job-applications.index', ['sort' => 'status', 'order' => 'asc']) }}">Status</a>
                        </th>
                        <td class="text-left px-4 py-2">{{ ucfirst($jobApplication->status) }}</td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Job Listing URL:</th>
                        <td class="text-left px-4 py-2">
                            @if($jobApplication->job_listing_url)
                                <a href="{{ $jobApplication->job_listing_url }}" class="text-blue-500 hover:underline" target="_blank">Go to Job Listing</a>
                            @else
                                None
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Company Website:</th>
                        <td class="text-left px-4 py-2">
                            @if($jobApplication->company_website_url)
                                <a href="{{ $jobApplication->company_website_url }}" class="text-blue-500 hover:underline" target="_blank">Go to Website</a>
                            @else
                                None
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th class="text-left px-4 py-2">Notes:</th>
                        <td class="text-left px-4 py-2">{{ $jobApplication->notes }}</td>
                    </tr>
                </table>

                <a href="{{ route('job-applications.edit', $jobApplication->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
