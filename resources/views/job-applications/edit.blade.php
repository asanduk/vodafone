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
                        <label for="position" class="block text-gray-700">Position Name</label>
                        <input type="text" name="position" id="position" class="form-control w-full" value="{{ $jobApplication->position }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_name" class="block text-gray-700">Company Name</label>
                        <input type="text" name="company_name" id="company_name" class="form-control w-full" value="{{ $jobApplication->company_name }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="applied_at" class="block text-gray-700">Application Date</label>
                        <input type="date" name="applied_at" id="applied_at" class="form-control w-full" value="{{ $jobApplication->applied_at }}" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="status" class="block text-gray-700">Status</label>
                        <select name="status" id="status" class="form-control w-full">
                            <option value="pending" {{ $jobApplication->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="interview" {{ $jobApplication->status == 'interview' ? 'selected' : '' }}>Interview Scheduled</option>
                            <option value="rejected" {{ $jobApplication->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="offered" {{ $jobApplication->status == 'offered' ? 'selected' : '' }}>Offer Received</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="job_listing_url" class="block text-gray-700">Job Listing URL</label>
                        <input type="url" name="job_listing_url" id="job_listing_url" class="form-control w-full" value="{{ $jobApplication->job_listing_url }}">
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_website_url" class="block text-gray-700">Company Website</label>
                        <input type="url" name="company_website_url" id="company_website_url" class="form-control w-full" value="{{ $jobApplication->company_website_url }}">
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes" class="block text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" class="form-control w-full">{{ $jobApplication->notes }}</textarea>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Update
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
