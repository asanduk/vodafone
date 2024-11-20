<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Applications') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- "Add New Application" and "Download as Excel" Buttons Side by Side -->
                <div class="flex space-x-4 mb-6">
                    <a href="{{ route('job-applications.create') }}" class="flex items-center bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Application
                    </a>

                    <a href="{{ route('job-applications.export') }}" class="flex items-center bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16l9 9 9-9m-9-3V3"/>
                        </svg>
                        Download as Excel
                    </a>
                </div>

                <!-- Filtering Form -->
                <form method="GET" action="{{ route('job-applications.index') }}" class="mb-6">
                    <label for="search" class="block text-gray-700 mb-2">Search:</label>
                    <input type="text" name="search" id="search" class="form-control w-full mb-4" value="{{ request('search') }}">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Search
                    </button>
                </form>
                
                <form method="GET" action="{{ route('job-applications.index') }}" class="mb-6">
                    <label for="status" class="block text-gray-700 mb-2">Filter by Status:</label>
                    <select name="status" id="status" class="form-control w-full mb-4">
                        <option value="">All Applications</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="interview" {{ request('status') == 'interview' ? 'selected' : '' }}>Interview Scheduled</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="offered" {{ request('status') == 'offered' ? 'selected' : '' }}>Offer Received</option>
                    </select>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Filter
                    </button>
                </form>

                <!-- Application List -->
                @if($applications->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full table-auto bg-white rounded-lg shadow">
                            <thead class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                                <tr>
                                    <th class="py-3 px-6 text-left">Position</th>
                                    <th class="py-3 px-6 text-left">Company</th>
                                    <th class="py-3 px-6 text-left">Application Date</th>
                                    <th class="py-3 px-6 text-left">Status</th>
                                    <th class="py-3 px-6 text-left">Actions</th>
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
                                                <span class="bg-yellow-200 text-yellow-800 px-2 py-1 rounded">Pending</span>
                                            @elseif($application->status == 'interview')
                                                <span class="bg-green-200 text-green-800 px-2 py-1 rounded">Interview Scheduled</span>
                                            @elseif($application->status == 'rejected')
                                                <span class="bg-red-200 text-red-800 px-2 py-1 rounded">Rejected</span>
                                            @elseif($application->status == 'offered')
                                                <span class="bg-blue-200 text-blue-800 px-2 py-1 rounded">Offer Received</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-6 text-left">
                                            <div class="relative inline-block">
                                                <a href="{{ route('job-applications.show', $application->id) }}" class="text-blue-500 hover:underline group" title="Details">
                                                    <i class="fas fa-eye"></i>
                                                    <span class="absolute left-1/2 transform -translate-x-1/2 -translate-y-full bg-gray-700 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Details</span>
                                                </a>
                                            </div>
                                            <div class="relative inline-block">
                                                <a href="{{ route('job-applications.edit', $application->id) }}" class="text-yellow-500 hover:underline ml-4 group" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                    <span class="absolute left-1/2 transform -translate-x-1/2 -translate-y-full bg-gray-700 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Edit</span>
                                                </a>
                                            </div>
                                            <div class="relative inline-block">
                                                <form action="{{ route('job-applications.destroy', $application->id) }}" method="POST" class="inline-block ml-4" onsubmit="return confirm('Are you sure you want to delete this application?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-500 hover:underline group" title="Delete">
                                                        <i class="fas fa-trash"></i>
                                                        <span class="absolute left-1/2 transform -translate-x-1/2 -translate-y-full bg-gray-700 text-white text-xs rounded py-1 px-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">Delete</span>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $applications->appends(request()->input())->links() }}
                    </div>
                @else
                    <p>No applications found.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
