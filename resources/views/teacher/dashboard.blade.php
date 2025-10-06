@extends('layouts.te-sidebar')

@section('content')
    <!-- Teacher Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- My Students Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">My Students</span>
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">32</div>
            <div class="text-sm text-green-600 mt-1">Grade 3 Section A</div>
        </div>

        <!-- Active Assignments Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Active Assignments</span>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">5</div>
            <div class="text-sm text-gray-500 mt-1">Due this week</div>
        </div>

        <!-- Parent Meetings Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Parent Meetings</span>
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">3</div>
            <div class="text-sm text-orange-600 mt-1">Scheduled this week</div>
        </div>

        <!-- Class Performance Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Class Average</span>
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">87%</div>
            <div class="text-sm text-green-600 mt-1">+3% from last month</div>
        </div>
    </div>

    <!-- Recent Activity and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Student Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Student Activity</h3>
                <span class="text-sm text-gray-500">Last 7 days</span>
            </div>
            <div class="space-y-4">
                <div class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-medium">MA</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Maria Santos</p>
                        <p class="text-xs text-gray-500">Submitted Math Assignment #3</p>
                    </div>
                    <span class="text-xs text-gray-400">2h ago</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-green-50 rounded-lg">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-medium">JD</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Juan Dela Cruz</p>
                        <p class="text-xs text-gray-500">Perfect score on Science Quiz</p>
                    </div>
                    <span class="text-xs text-gray-400">1d ago</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                    <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-medium">AL</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Ana Lopez</p>
                        <p class="text-xs text-gray-500">Parent meeting requested</p>
                    </div>
                    <span class="text-xs text-gray-400">2d ago</span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-2 gap-3">
                <button class="flex flex-col items-center gap-2 p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                    <span class="text-sm font-medium text-blue-700">Create Assignment</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    <span class="text-sm font-medium text-green-700">Grade Papers</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                    <span class="text-sm font-medium text-yellow-700">View Students</span>
                </button>
                <button class="flex flex-col items-center gap-2 p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-sm font-medium text-purple-700">Schedule Meeting</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Upcoming Events and Announcements -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Upcoming Events -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Upcoming Events</h3>
                <a href="#" class="text-sm text-green-600 hover:text-green-700">View all</a>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex flex-col items-center justify-center">
                        <span class="text-xs font-medium text-blue-600">Oct</span>
                        <span class="text-sm font-bold text-blue-700">15</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Parent-Teacher Conference</p>
                        <p class="text-xs text-gray-500">2:00 PM - 5:00 PM</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex flex-col items-center justify-center">
                        <span class="text-xs font-medium text-green-600">Oct</span>
                        <span class="text-sm font-bold text-green-700">20</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Science Fair Preparation</p>
                        <p class="text-xs text-gray-500">9:00 AM - 12:00 PM</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-3 border border-gray-200 rounded-lg">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex flex-col items-center justify-center">
                        <span class="text-xs font-medium text-yellow-600">Oct</span>
                        <span class="text-sm font-bold text-yellow-700">25</span>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900">Monthly PTA Meeting</p>
                        <p class="text-xs text-gray-500">3:00 PM - 4:30 PM</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Announcements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Recent Announcements</h3>
                <a href="#" class="text-sm text-green-600 hover:text-green-700">View all</a>
            </div>
            <div class="space-y-4">
                <div class="p-3 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                    <p class="text-sm font-medium text-gray-900">New Learning Materials Available</p>
                    <p class="text-xs text-gray-600 mt-1">Digital resources for Math and Science subjects have been uploaded to the portal.</p>
                    <p class="text-xs text-gray-500 mt-2">Principal • 2 days ago</p>
                </div>
                <div class="p-3 bg-green-50 rounded-lg border-l-4 border-green-400">
                    <p class="text-sm font-medium text-gray-900">PTA Fundraising Event</p>
                    <p class="text-xs text-gray-600 mt-1">Join us for the upcoming school fundraising event on October 30th.</p>
                    <p class="text-xs text-gray-500 mt-2">PTA Coordinator • 3 days ago</p>
                </div>
                <div class="p-3 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                    <p class="text-sm font-medium text-gray-900">Reminder: Faculty Meeting</p>
                    <p class="text-xs text-gray-600 mt-1">Monthly faculty meeting scheduled for this Friday at 4:00 PM.</p>
                    <p class="text-xs text-gray-500 mt-2">Administration • 1 week ago</p>
                </div>
            </div>
        </div>
    </div>
@endsection