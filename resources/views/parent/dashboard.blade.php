@extends('layouts.parent-sidebar')

@section('title', 'Home')

@section('content')

    <!-- Top Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Current Grade Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Current Grade</span>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">Grade 4</div>
            <div class="text-sm text-gray-500 mt-1">Section: Mabini</div>
        </div>

        <!-- Attendance Rate Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Attendance Rate</span>
                <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">96%</div>
            <div class="text-sm text-green-600 mt-1">Excellent attendance</div>
        </div>

        <!-- Outstanding Balance Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Outstanding Balance</span>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">₱2,450</div>
            <div class="text-sm text-gray-500 mt-1">Due March 20, 2024</div>
        </div>

        <!-- Upcoming Events Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-medium text-gray-500 uppercase">Upcoming Events</span>
                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <div class="text-3xl font-bold text-gray-900">3</div>
            <div class="text-sm text-gray-500 mt-1">This month</div>
        </div>
    </div>

    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Upcoming Projects -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">Upcoming Projects</h3>
            <div class="space-y-5">
                <!-- Project 1 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 20</div>
                    </div>
                    <div class="flex-1 border-l-4 border-red-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Fun Run for a Cause</div>
                        <div class="text-sm text-gray-600 mt-0.5">*Tabbo Para sa Kinabukasan* Fun Run</div>
                        <div class="text-xs text-gray-500 mt-1">5:30 AM - 8:00 AM</div>
                    </div>
                </div>

                <!-- Project 2 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 18</div>
                    </div>
                    <div class="flex-1 border-l-4 border-purple-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Fundraising Projects</div>
                        <div class="text-sm text-gray-600 mt-0.5">PTA School Fair 2025</div>
                        <div class="text-xs text-gray-500 mt-1">8:00 AM - 5:00 PM</div>
                    </div>
                </div>

                <!-- Project 3 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 22</div>
                    </div>
                    <div class="flex-1 border-l-4 border-blue-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Community and Parent Involvement</div>
                        <div class="text-sm text-gray-600 mt-0.5">Parenting seminars and workshops</div>
                        <div class="text-xs text-gray-500 mt-1">8:00 AM - 4:00 PM</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upcoming Schedule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-base font-bold text-gray-900 mb-4">Upcoming Schedule</h3>
            <div class="space-y-5">
                <!-- Schedule 1 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 15</div>
                    </div>
                    <div class="flex-1 border-l-4 border-red-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Parent-Teacher Conference</div>
                        <div class="text-sm text-gray-600 mt-0.5">Meeting with Ms. Rodriguez</div>
                        <div class="text-xs text-gray-500 mt-1">2:00 PM - 2:30 PM</div>
                    </div>
                </div>

                <!-- Schedule 2 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 18</div>
                    </div>
                    <div class="flex-1 border-l-4 border-purple-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Science Fair</div>
                        <div class="text-sm text-gray-600 mt-0.5">Sofia's project presentation</div>
                        <div class="text-xs text-gray-500 mt-1">9:00 AM - 12:00 PM</div>
                    </div>
                </div>

                <!-- Schedule 3 -->
                <div class="flex">
                    <div class="flex-shrink-0 w-14 pt-1">
                        <div class="text-xs font-semibold text-gray-500">MAR 22</div>
                    </div>
                    <div class="flex-1 border-l-4 border-blue-400 pl-4">
                        <div class="font-semibold text-gray-900 text-sm">Field Trip</div>
                        <div class="text-sm text-gray-600 mt-0.5">National Museum Visit</div>
                        <div class="text-xs text-gray-500 mt-1">8:00 AM - 4:00 PM</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- About Us Section -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-base font-bold text-gray-900 mb-4">About US</h3>
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-shrink-0">
                <img src="http://127.0.0.1:8000/images/logos/jces-logo.png" 
                     alt="School Photo" 
                     class="rounded-lg w-full md:w-52 h-28 object-cover">
            </div>
            <div class="flex-1">
                <p class="text-sm text-gray-700 leading-relaxed">
                    J. Cruz Sr. Elementary School, founded in 1975 on land donated by Juan Cruz Sr. in Panacan, 
                    Relocation, Davao City, has grown from modest beginnings into a key educational institution. For 
                    nearly five decades, it has served both local and neighboring communities, fostering strong ties 
                    through active PTA involvement and continuous school improvement projects.
                </p>
            </div>
        </div>
    </div>
@endsection
