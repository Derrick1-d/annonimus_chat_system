<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}
                </div>
                <div class="p-6">
                    <!-- Lecturer Dashboard Button -->
                    <a href="{{ route('lecturer.dashboard') }}" class="btn btn-primary btn-lg d-block mb-3">
                        Lecturer Dashboard
                    </a>

                    <!-- Student Dashboard Button -->
                    <a href="{{ route('student.dashboard') }}" class="btn btn-secondary btn-lg d-block">
                        Student Dashboard
                    </a>

                    <!-- Anonymous Post Button -->
                    <a href="{{ route('student.posts') }}" class="btn btn-success btn-lg d-block mt-3">
                        Post Anonymously
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
