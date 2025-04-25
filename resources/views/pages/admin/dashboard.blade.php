<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        {{-- Profile Completion Alert --}}
        @php
            $admin = auth()->user()->admin;
            $incompleteFields = [];

            if (empty($admin->phone)) $incompleteFields[] = 'phone number';
            if (empty($admin->faculty)) $incompleteFields[] = 'faculty';
            if (empty($admin->pose)) $incompleteFields[] = 'position';
            if (empty($admin->img)) $incompleteFields[] = 'profile photo';
        @endphp

        @if(!empty($incompleteFields))
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon name="exclamation-triangle" class="size-5 text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            Your profile is incomplete. Please update your {{ implode(', ', $incompleteFields) }}.
                            <a href="{{ route('settings.profile') }}" class="font-medium underline text-yellow-700 dark:text-yellow-200 hover:text-yellow-600 dark:hover:text-yellow-300">
                                Complete your profile
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Welcome Section --}}
        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-2">
                        Welcome, {{ auth()->user()->name }}!
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        You are logged in as
                        @if($adminPose)
                            {{ $adminPose }}
                        @else
                            an Administrator
                        @endif.
                        Here you can manage students and monitor their academic progress.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <img src="{{ auth()->user()->admin->img ? Storage::url(auth()->user()->admin->img) : asset('images/default-avatar.png') }}"
                         alt="Profile Image"
                         class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                </div>
            </div>
        </div>

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Total Mentees -->
            <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-indigo-100 dark:bg-indigo-900">
                        <x-flux::icon name="users" class="size-6 text-indigo-600 dark:text-indigo-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Mentees</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_mentees'] }}</p>
                    </div>
                </div>
            </div>

            <!-- Total Counseling Sessions -->
            <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-green-100 dark:bg-green-900">
                        <x-flux::icon name="chat-bubble-left-right" class="size-6 text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Counseling Sessions</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_counseling'] }}</p>

                        @if($statistics['upcoming_counseling'])
                            <div class="mt-2 text-sm">
                                <p class="text-indigo-600 dark:text-indigo-400">
                                    Next session: {{ $statistics['upcoming_counseling']->start_time->format('d M Y, h:i A') }}
                                    <br>
                                    with {{ $statistics['upcoming_counseling']->student->name }}
                                </p>
                            </div>
                        @endif

                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ $statistics['this_week_counseling'] }} sessions this week
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Reports -->
            <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                <div class="flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 dark:bg-yellow-900">
                        <x-flux::icon name="document-text" class="size-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="ml-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Pending Reports</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_pending_reports'] }}</p>

                        @if($statistics['pending_reports']->isNotEmpty())
                            <div class="mt-2 text-sm space-y-1">
                                @foreach($statistics['pending_reports'] as $pending)
                                    <p class="text-yellow-600 dark:text-yellow-400">
                                        Batch {{ $pending['batch'] }}: {{ $pending['missing'] }} reports
                                        <div class="ml-2">
                                            <span class="text-xs font-medium text-gray-500">To Be Submitted:</span>
                                            <div class="ml-2 text-xs text-gray-500">
                                                @foreach($pending['details'] as $detail)
                                                    <div>Semester {{ $detail['sem'] }} Year {{ $detail['year'] }}</div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </p>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Scheduled Meetings Section --}}
        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Scheduled Meetings
                </h2>
            </div>

            @if($upcomingMeetings->isNotEmpty())
                <div class="divide-y divide-gray-200 dark:divide-zinc-700">
                    @foreach($upcomingMeetings as $meeting)
                        <div class="py-3">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $meeting->start_time->format('d M Y, h:i A') }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Duration: {{ $meeting->duration }} minutes
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Venue: {{ $meeting->venue }}
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $meeting->student->name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $meeting->student->matric_no }}
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $meeting->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' :
                                           ($meeting->status === 'confirmed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' :
                                           'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200') }}">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <p class="text-gray-500 dark:text-gray-400">No upcoming meetings scheduled.</p>
                </div>
            @endif
        </div>

        {{-- Debug information --}}
        @if(auth()->user()->admin)
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Admin ID: {{ auth()->user()->admin->id }}
            </div>
        @endif

        <div x-data="{ openStudent: null }" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @forelse ($students as $student)
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md dark:shadow-zinc-800/30 p-4 cursor-pointer"
                     @click="openStudent = openStudent === {{ $student->id }} ? null : {{ $student->id }}"
                     :class="{ 'ring-2 ring-indigo-500': openStudent === {{ $student->id }} }"
                     :class="{'col-span-1 md:col-span-2': openStudent === {{ $student->id }}}">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img class="h-12 w-12 rounded-full" src="{{ $student->img ?? asset('images/default-avatar.png') }}" alt="">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                {{ $student->name }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $student->matric_no }}
                            </p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Intake: {{ $student->intake }}
                            </p>
                        </div>
                    </div>

                    <!-- Expanded Details -->
                    <div x-show="openStudent === {{ $student->id }}"
                         x-collapse
                         class="mt-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Program:</span> {{ $student->program }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Email:</span> {{ $student->email }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Phone:</span> {{ $student->phone }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">State:</span> {{ $student->state }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Address:</span> {{ $student->address }}
                            </p>
                            <p class="text-gray-700 dark:text-gray-300">
                                <span class="font-medium">Motto:</span> {{ $student->motto }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center py-6 text-gray-500 dark:text-gray-400">
                        No students found.
                        @if(!auth()->user()->admin)
                            (No admin profile found)
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>
</x-layouts.app>








