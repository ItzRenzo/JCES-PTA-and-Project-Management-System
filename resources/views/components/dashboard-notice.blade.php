@php
    $user = auth()->user();
    $since = now()->subDays(30);
    $notifications = collect();

    if ($user) {

        // --- Recent Payments ---
        $paymentQuery = \App\Models\ProjectContribution::query()
            ->with('project')
            ->whereNotNull('contribution_date')
            ->where('contribution_date', '>=', $since);

        foreach ($paymentQuery->orderBy('contribution_date', 'desc')->get() as $p) {
            $projectName = optional($p->project)->project_name ?? 'a project';
            $notifications->push([
                'type'   => 'payment',
                'icon'   => 'payment',
                'title'  => 'Payment of ₱' . number_format($p->contribution_amount, 2) . ' was recorded for ' . $projectName,
                'time'   => $p->contribution_date,
                'status' => $p->payment_status ?? 'recorded',
            ]);
        }

        // --- Recent Project Updates ---
        $updateQuery = \App\Models\ProjectUpdate::query()
            ->with('project')
            ->whereNotNull('update_date')
            ->where('update_date', '>=', $since);

        foreach ($updateQuery->orderBy('update_date', 'desc')->get() as $u) {
            $projectName = optional($u->project)->project_name ?? 'A project';
            $notifications->push([
                'type'   => 'update',
                'icon'   => 'update',
                'title'  => $projectName . ': ' . ($u->update_title ?? 'New update posted'),
                'time'   => $u->update_date,
                'status' => $u->progress_percentage !== null ? $u->progress_percentage . '% progress' : null,
            ]);
        }

        // --- Recent Announcements for this user's role ---
        $announcementQuery = \App\Models\Announcement::with('creator')
            ->active()
            ->published()
            ->forAudience($user->user_type)
            ->where('published_at', '>=', $since);

        foreach ($announcementQuery->orderBy('published_at', 'desc')->get() as $a) {
            $notifications->push([
                'type'   => 'announcement',
                'icon'   => 'announcement',
                'title'  => $a->title,
                'time'   => $a->published_at,
                'status' => ucfirst($a->category ?? 'general'),
            ]);
        }

        // Sort all by time descending
        $notifications = $notifications->sortByDesc('time')->values();
    }
@endphp

@if($notifications->isNotEmpty())
<div x-data="{ open: true }" x-show="open" x-transition class="mb-6" role="alert" aria-live="polite">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        {{-- Header --}}
        <div class="flex items-center justify-between px-4 py-3 bg-blue-50 border-b border-blue-100">
            <div class="flex items-center gap-2">
                <span class="relative">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -top-1 -right-1 flex h-3.5 w-3.5 items-center justify-center rounded-full bg-red-500 text-[9px] font-bold text-white">{{ $notifications->count() }}</span>
                </span>
                <span class="text-sm font-semibold text-blue-800">Notifications</span>
                <span class="text-xs text-blue-600">(Last 30 days)</span>
            </div>
            <button type="button" @click="open = false" class="text-gray-400 hover:text-gray-600 transition" title="Dismiss">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Notification items --}}
        <div class="divide-y divide-gray-100 max-h-64 overflow-y-auto">
            @foreach($notifications as $notif)
                <div class="flex items-center gap-2.5 px-4 py-2 hover:bg-gray-50 transition">
                    {{-- Icon --}}
                    @if($notif['icon'] === 'payment')
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-green-100 text-green-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>
                    @elseif($notif['icon'] === 'update')
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-blue-100 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </span>
                    @elseif($notif['icon'] === 'announcement')
                        <span class="flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-orange-100 text-orange-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </span>
                    @endif

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-800 leading-snug truncate">{{ $notif['title'] }}</p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="text-[10px] text-gray-400">{{ \Illuminate\Support\Carbon::parse($notif['time'])->diffForHumans() }}</span>
                            @if($notif['status'])
                                <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[10px] font-medium
                                    @if($notif['type'] === 'payment')
                                        {{ $notif['status'] === 'approved' ? 'bg-green-100 text-green-700' : ($notif['status'] === 'pending' ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-600') }}
                                    @elseif($notif['type'] === 'announcement')
                                        {{ $notif['status'] === 'Important' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700' }}
                                    @else
                                        bg-blue-100 text-blue-700
                                    @endif
                                ">{{ $notif['status'] }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Dot indicator --}}
                    <span class="h-1.5 w-1.5 shrink-0 rounded-full
                        @if($notif['type'] === 'payment') bg-green-400
                        @elseif($notif['type'] === 'update') bg-blue-400
                        @else bg-orange-400
                        @endif
                    "></span>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endif
