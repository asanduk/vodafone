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

                    <div class="form-group mb-4">
                        <label for="position" class="block text-gray-700">Position Name <span class="text-red-500">*</span></label>
                        <input type="text" name="position" id="position" class="form-control w-full" value="{{ $jobApplication->position }}" required maxlength="100" placeholder="Position name">
                        <span class="info-icon" data-toggle="tooltip" title="Max 100 characters">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_name" class="block text-gray-700">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" class="form-control w-full" value="{{ $jobApplication->company_name }}" required maxlength="100" placeholder="Company name">
                        <span class="info-icon" data-toggle="tooltip" title="Max 100 characters">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="applied_at" class="block text-gray-700">Application Date <span class="text-red-500">*</span></label>
                        <input type="date" name="applied_at" id="applied_at" class="form-control w-full" value="{{ $jobApplication->applied_at }}" required>
                        <span class="info-icon" data-toggle="tooltip" title="Select the date you applied">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="status" class="block text-gray-700">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-control w-full">
                            <option value="pending" {{ $jobApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="interview" {{ $jobApplication->status == 'interview' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="rejected" {{ $jobApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="offered" {{ $jobApplication->status == 'offered' ? 'selected' : '' }}>Offer Received</option>
                        </select>
                        <span class="info-icon" data-toggle="tooltip" title="Select the current status of your application">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="job_listing_url" class="block text-gray-700">Job Listing URL</label>
                        <input type="url" name="job_listing_url" id="job_listing_url" class="form-control w-full" value="{{ $jobApplication->job_listing_url }}" maxlength="512" placeholder="Job listing URL">
                        <span class="info-icon" data-toggle="tooltip" title="Max 512 characters">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_website_url" class="block text-gray-700">Company Website</label>
                        <input type="url" name="company_website_url" id="company_website_url" class="form-control w-full" value="{{ $jobApplication->company_website_url }}" maxlength="512" placeholder="Company website">
                        <span class="info-icon" data-toggle="tooltip" title="Max 512 characters">ℹ️</span>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes" class="block text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" class="form-control w-full" maxlength="2000" placeholder="Enter your notes here...">{{ $jobApplication->notes }}</textarea>
                        <span class="info-icon" data-toggle="tooltip" title="Max 2000 characters">ℹ️</span>
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
