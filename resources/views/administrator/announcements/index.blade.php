@extends('layouts.ad-sidebar')

@section('title', 'Announcements')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">School Announcements</h1>
        <div class="relative">
            <button type="button" id="announcementFilterButton" class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700">
                <span id="announcementFilterLabel">Filter</span>
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.94a.75.75 0 111.08 1.04l-4.24 4.5a.75.75 0 01-1.08 0l-4.24-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            <div id="announcementFilterMenu" class="absolute right-0 mt-2 w-44 bg-green-600 text-white rounded-lg shadow-lg hidden">
                <button class="w-full text-left px-4 py-2 text-sm hover:bg-green-700" data-filter="Today">Today</button>
                <button class="w-full text-left px-4 py-2 text-sm hover:bg-green-700" data-filter="This Week">This Week</button>
                <button class="w-full text-left px-4 py-2 text-sm hover:bg-green-700" data-filter="This Month">This Month</button>
            </div>
        </div>
    </div>

    <div class="space-y-5">
        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">Parent-Teacher Conference Schedule</h2>
                <span class="text-sm text-gray-400">2 days ago</span>
            </div>
            <p class="text-gray-600 mt-2">The annual parent-teacher conference will be held on March 15-17, 2024. Please schedule your appointments through the school portal.</p>
            <div class="flex items-center gap-2 mt-4">
                <span class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full">Important</span>
                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Event</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-orange-400">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">School Closure Notice</h2>
                <span class="text-sm text-gray-400">1 week ago</span>
            </div>
            <p class="text-gray-600 mt-2">The school will be closed on March 8, 2024 due to a scheduled maintenance. Classes will resume on March 11, 2024.</p>
            <div class="flex items-center gap-2 mt-4">
                <span class="px-3 py-1 text-xs bg-orange-100 text-orange-700 rounded-full">Notice</span>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 shadow-sm border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-gray-900">New Learning Management System</h2>
                <span class="text-sm text-gray-400">2 weeks ago</span>
            </div>
            <p class="text-gray-600 mt-2">We are excited to announce the launch of our new learning management system. Training sessions for parents will begin next week.</p>
            <div class="flex items-center gap-2 mt-4">
                <span class="px-3 py-1 text-xs bg-blue-100 text-blue-700 rounded-full">Update</span>
                <span class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">Technology</span>
            </div>
        </div>
    </div>
</div>

<script>
    const announcementFilterButton = document.getElementById('announcementFilterButton');
    const announcementFilterMenu = document.getElementById('announcementFilterMenu');
    const announcementFilterLabel = document.getElementById('announcementFilterLabel');

    announcementFilterButton.addEventListener('click', () => {
        announcementFilterMenu.classList.toggle('hidden');
    });

    document.addEventListener('click', (event) => {
        if (!announcementFilterButton.contains(event.target) && !announcementFilterMenu.contains(event.target)) {
            announcementFilterMenu.classList.add('hidden');
        }
    });

    announcementFilterMenu.querySelectorAll('[data-filter]').forEach((item) => {
        item.addEventListener('click', () => {
            announcementFilterLabel.textContent = item.dataset.filter;
            announcementFilterMenu.classList.add('hidden');
        });
    });
</script>
@endsection
