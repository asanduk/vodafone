<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Job Application') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <form action="{{ route('job-applications.store') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <label for="position" class="block text-gray-700 font-medium">Position Name <span class="text-red-500">*</span></label>
                        <input type="text" name="position" id="position" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required maxlength="100" placeholder="Position name">
                        <small class="text-gray-500">Max 100 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_name" class="block text-gray-700 font-medium">Company Name <span class="text-red-500">*</span></label>
                        <input type="text" name="company_name" id="company_name" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required maxlength="100" placeholder="Company name">
                        <small class="text-gray-500">Max 100 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="applied_at" class="block text-gray-700 font-medium">Application Date <span class="text-red-500">*</span></label>
                        <input type="date" name="applied_at" id="applied_at" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" required>
                    </div>

                    <div class="form-group mb-4">
                        <label for="status" class="block text-gray-700 font-medium">Status <span class="text-red-500">*</span></label>
                        <select name="status" id="status" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="interview">Interview Scheduled</option>
                            <option value="rejected">Rejected</option>
                            <option value="offered">Offer Received</option>
                        </select>
                    </div>

                    <div class="form-group mb-4">
                        <label for="job_listing_url" class="block text-gray-700 font-medium">Job Listing URL</label>
                        <input type="url" name="job_listing_url" id="job_listing_url" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" maxlength="512" placeholder="Job listing URL">
                        <small class="text-gray-500">Max 512 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="company_website_url" class="block text-gray-700 font-medium">Company Website</label>
                        <input type="url" name="company_website_url" id="company_website_url" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" maxlength="512" placeholder="Company website">
                        <small class="text-gray-500">Max 512 characters</small>
                    </div>

                    <div class="form-group mb-4">
                        <label for="notes" class="block text-gray-700 font-medium">Notes</label>
                        <textarea name="notes" id="notes" class="form-control w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500" maxlength="2000" placeholder="Enter your notes here..."></textarea>
                        <small class="text-gray-500">Max 2000 characters</small>
                    </div>

                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Create
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
