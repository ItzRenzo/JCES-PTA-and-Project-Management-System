@extends('layouts.pr-sidebar')

@section('content')
    <!-- Principal Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Total Students Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Total Students</span>
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">847</div>
            <div class="text-sm text-green-600 mt-1">+12 new this month</div>
        </div>

        <!-- Active Teachers Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Active Teachers</span>
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">34</div>
            <div class="text-sm text-gray-500 mt-1">All positions filled</div>
        </div>

        <!-- Monthly PTA Funds Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Monthly PTA Funds</span>
                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">₱125,450</div>
            <div class="text-sm text-green-600 mt-1">+18% from last month</div>
        </div>

        <!-- Active Projects Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Active Projects</span>
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">8</div>
            <div class="text-sm text-blue-600 mt-1">3 awaiting approval</div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent Project Updates -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Recent Project Updates</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">Library Renovation Project</h3>
                        <p class="text-sm text-gray-600">Phase 2 construction completed. Ready for final inspection.</p>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">Computer Lab Upgrade</h3>
                        <p class="text-sm text-gray-600">New computers delivered and installation in progress.</p>
                        <span class="text-xs text-gray-500">5 hours ago</span>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                    <div class="flex-1">
                        <h3 class="font-medium text-gray-900">Playground Equipment</h3>
                        <p class="text-sm text-gray-600">Awaiting budget approval for additional safety features.</p>
                        <span class="text-xs text-gray-500">1 day ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Performance Metrics -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">School Performance Metrics</h2>
            </div>
            <div class="p-6 space-y-6">
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Overall Attendance Rate</span>
                        <span class="text-sm font-semibold text-green-600">94.2%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 94.2%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">PTA Participation</span>
                        <span class="text-sm font-semibold text-blue-600">78.5%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 78.5%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-gray-700">Project Completion Rate</span>
                        <span class="text-sm font-semibold text-purple-600">85.7%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 85.7%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Summary -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold text-gray-900">Financial Summary</h2>
                <button class="text-sm text-blue-600 hover:text-blue-700 font-medium">View Full Report</button>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <div class="text-2xl font-bold text-green-600">₱2,450,000</div>
                    <div class="text-sm text-gray-500">Total Budget Allocated</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-blue-600">₱1,850,000</div>
                    <div class="text-sm text-gray-500">Funds Utilized</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold text-yellow-600">₱600,000</div>
                    <div class="text-sm text-gray-500">Remaining Budget</div>
                </div>
            </div>
        </div>
    </div>
@endsection