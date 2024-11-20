<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Job Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('job-applications.update', $jobApplication->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group mb-4 relative">
                        <label for="position" class="block text-gray-700">Position Name <span class="text-red-500">*</span></label>
                        <input type="text" name="position" id="position" class="form-control w-full" value="{{ $jobApplication->position }}" required maxlength="100" placeholder="Position name">
                        <span class="ml-2 cursor-pointer relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-3-3H9m3-4a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 w-48 p-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                Max 100 characters
                            </div>
                        </span>
                    </div>

                    <div class="form-group mb-4 relative">
                        <label for="company_name" class="block text-gray-700">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" class="form-control w-full" value="{{ $jobApplication->company_name }}" required maxlength="100" placeholder="Company name">
                        <span class="ml-2 cursor-pointer relative group">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 hover:text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m-3-3H9m3-4a4 4 0 100-8 4 4 0 000 8z" />
                            </svg>
                            <div class="absolute left-1/2 transform -translate-x-1/2 bottom-full mb-2 w-48 p-2 text-sm text-white bg-gray-900 rounded-lg shadow-lg opacity-0 transition-opacity duration-300 group-hover:opacity-100">
                                Max 100 characters
                            </div>
                        </span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="applied_at" class="block text-gray-700">Application Date <span class="text-red-500">*</span></label>
                        <input type="date" name="applied_at" id="applied_at" class="form-control w-full" value="{{ $jobApplication->applied_at }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="status" class="block text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-control w-full">
                            <option value="pending" {{ $jobApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="interview" {{ $jobApplication->status == 'interview' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="rejected" {{ $jobApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="offered" {{ $jobApplication->status == 'offered' ? 'selected' : '' }}>Offer Received</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="job_listing_url" class="block text-gray-700">Job Listing URL</label>
                        <input type="url" name="job_listing_url" id="job_listing_url" class="form-control w-full" value="{{ $jobApplication->job_listing_url }}" maxlength="512" placeholder="Job listing URL">
                        <small class="text-gray-500">Max 512 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_website_url" class="block text-gray-700">Company Website</label>
                        <input type="url" name="company_website_url" id="company_website_url" class="form-control w-full" value="{{ $jobApplication->company_website_url }}" maxlength="512" placeholder="Company website">
                        <small class="text-gray-500">Max 512 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes" class="block text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" class="form-control w-full" maxlength="2000" placeholder="Enter your notes here...">{{ $jobApplication->notes }}</textarea>
                        <small class="text-gray-500">Max 2000 characters</small>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>

                    @if ($errors->any())
                        <div class="mt-4">
                            <ul class="text-red-500">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
