@extends('layouts.pr-sidebar')

@section('title', 'Home')

@section('content')
    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Proposed Projects Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-600">Proposed Projects</span>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a2 2 0 00-2 2v8a2 2 0 002 2h6a2 2 0 002-2V6.414A2 2 0 0016.414 5L14 2.586A2 2 0 0012.586 2H9z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-gray-900">1</div>
            <div class="text-sm text-gray-500 mt-2">This month</div>
        </div>

        <!-- Active Phorents Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-600">Active Phorents</span>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-gray-900">87</div>
            <div class="text-sm text-gray-500 mt-2">+3 new this month</div>
        </div>

        <!-- Upcoming Events Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-600">Upcoming Events</span>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-gray-900">3</div>
            <div class="text-sm text-gray-500 mt-2">This month</div>
        </div>

        <!-- Active Projects Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-3">
                <span class="text-xs font-medium text-gray-600">Active Projects</span>
                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
                    </svg>
                </div>
            </div>
            <div class="text-4xl font-bold text-gray-900">12</div>
            <div class="text-sm text-gray-500 mt-2">5 completed this month</div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Announcements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-5">Recent Announcements</h3>
            <div class="space-y-5">
                <!-- Announcement 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-1 bg-red-400 rounded-full"></div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 text-sm">Parent-Teacher Conference</div>
                        <div class="text-sm text-gray-600 mt-1">Scheduled for March 15-17, 2024. Please confirm your attendance.</div>
                        <div class="text-xs text-gray-500 mt-2">2 hours ago</div>
                    </div>
                </div>

                <!-- Announcement 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-1 bg-purple-400 rounded-full"></div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 text-sm">Science Fair Winners</div>
                        <div class="text-sm text-gray-600 mt-1">Congratulations to all participants in the annual science fair.</div>
                        <div class="text-xs text-gray-500 mt-2">1 day ago</div>
                    </div>
                </div>

                <!-- Announcement 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-1 bg-blue-400 rounded-full"></div>
                    <div class="flex-1">
                        <div class="font-semibold text-gray-900 text-sm">School Maintenance</div>
                        <div class="text-sm text-gray-600 mt-1">Building maintenance scheduled for this weekend.</div>
                        <div class="text-xs text-gray-500 mt-2">3 days ago</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-5">Upcoming Schedule</h3>
            <div class="space-y-5">
                <!-- Schedule 1 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-16 text-center pt-1">
                        <div class="text-sm font-semibold text-gray-900">Oct 12</div>
                    </div>
                    <div class="flex-1 border-l-4 border-red-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Faculty Development Workshop</div>
                        <div class="text-sm text-gray-600 mt-0.5">Training on modern teaching strategies.</div>
                        <div class="text-xs text-gray-500 mt-1">8:00 AM - 10:00 AM</div>
                    </div>
                </div>

                <!-- Schedule 2 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-16 text-center pt-1">
                        <div class="text-sm font-semibold text-gray-900">Oct 28</div>
                    </div>
                    <div class="flex-1 border-l-4 border-purple-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Subject Coordination Meeting</div>
                        <div class="text-sm text-gray-600 mt-0.5">Discuss student progress with parents.</div>
                        <div class="text-xs text-gray-500 mt-1">9:00 AM - 12:00 PM</div>
                    </div>
                </div>

                <!-- Schedule 3 -->
                <div class="flex gap-4">
                    <div class="flex-shrink-0 w-16 text-center pt-1">
                        <div class="text-sm font-semibold text-gray-900">Nov 15</div>
                    </div>
                    <div class="flex-1 border-l-4 border-blue-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Parent-Teacher Consultation</div>
                        <div class="text-sm text-gray-600 mt-0.5">Discuss student progress with parents.</div>
                        <div class="text-xs text-gray-500 mt-1">8:00 AM - 4:00 PM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection