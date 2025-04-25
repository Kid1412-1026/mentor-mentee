<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        @if(isset($accountLocked) && $accountLocked)
            <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <x-flux::icon name="exclamation-triangle" class="size-5 text-yellow-400" />
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-yellow-700 dark:text-yellow-200">
                            Your account is currently inactive. A mentor needs to be assigned before you can access the system. Please contact your administrator.
                        </p>
                    </div>
                </div>
            </div>
        @else
            {{-- Profile Completion Alert --}}
            @php
                $student = auth()->user()->student;
                $incompleteFields = [];

                if (empty($student->phone)) $incompleteFields[] = 'phone number';
                if (empty($student->program)) $incompleteFields[] = 'program';
                if (empty($student->faculty)) $incompleteFields[] = 'faculty';
                if (empty($student->state)) $incompleteFields[] = 'state';
                if (empty($student->address)) $incompleteFields[] = 'address';
                if (empty($student->motto)) $incompleteFields[] = 'motto';
                if (empty($student->img)) $incompleteFields[] = 'profile photo';
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
                            Welcome back, {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">
                            {{ now()->format('l, d F Y') }}
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <img src="{{ $student?->img ? Storage::url($student->img) : asset('images/default-avatar.png') }}"
                             alt="Profile Image"
                             class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                    </div>
                </div>
            </div>

            @if(!$student)
                <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        No student profile found.
                    </div>
                </div>
            @else
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Academic Performance -->
                    <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Performance</h3>
                        <div class="mt-2">
                            <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ number_format($statistics['latest_cgpa'], 2) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Current CGPA</p>
                        </div>
                        <div class="mt-2">
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($statistics['average_pointer'], 2) }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Average Pointer</p>
                        </div>
                    </div>

                    <!-- Activities -->
                    <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Activities</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_activities'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Activities</p>
                    </div>

                    <!-- Challenges -->
                    <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Challenges</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_challenges'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Challenges</p>
                    </div>

                    <!-- Courses -->
                    <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Courses</h3>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $statistics['total_courses'] }}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Total Enrolled Courses</p>
                    </div>
                </div>

                <!-- Next Session and Mentor Profile Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Next Session -->
                    @if($nextSession)
                        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Session</h3>
                            <div class="mt-2">
                                <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                    {{ $nextSession->start_time->format('d M Y') }}
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $nextSession->start_time->format('h:i A') }}
                                </p>
                                <p class="mt-1 text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                    @php
                                        $daysToGo = floor(now()->diffInDays($nextSession->start_time, false));
                                        if ($daysToGo > 0) {
                                            echo $daysToGo . ' ' . Str::plural('day', $daysToGo) . ' to go';
                                        } else {
                                            $hoursToGo = floor(now()->diffInHours($nextSession->start_time, false));
                                            if ($hoursToGo > 0) {
                                                echo $hoursToGo . ' ' . Str::plural('hour', $hoursToGo) . ' to go';
                                            } else {
                                                $minutesToGo = max(0, floor(now()->diffInMinutes($nextSession->start_time, false)));
                                                echo $minutesToGo . ' ' . Str::plural('minute', $minutesToGo) . ' to go';
                                            }
                                        }
                                    @endphp
                                </p>
                            </div>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Duration: {{ $nextSession->duration }} minutes<br>
                                    Venue: {{ $nextSession->venue }}
                                </p>
                            </div>
                        </div>
                    @endif

                    <!-- Mentor Profile Card -->
                    @php
                        $loggedInUser = auth()->user();
                        $studentRecord = App\Models\Student::where('user_id', $loggedInUser?->id)->first();
                    @endphp
                    @if(!$loggedInUser)
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-flux::icon name="exclamation-triangle" class="size-5 text-yellow-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                        Your account will be activated soon. Please check back later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @elseif($studentRecord && $studentRecord->admin)
                        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">My Mentor</h3>
                            <div class="mt-4 flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <img src="{{ $studentRecord->admin->img ? Storage::url($studentRecord->admin->img) : asset('images/default-avatar.png') }}"
                                         alt="Mentor Profile"
                                         class="h-16 w-16 rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                                </div>
                                <div>
                                    <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $studentRecord->admin->user->name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        {{ $studentRecord->admin->pose }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        <a href="mailto:{{ $studentRecord->admin->user->email }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                            {{ $studentRecord->admin->user->email }}
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 dark:bg-yellow-900/30 border-l-4 border-yellow-400 p-4 rounded-lg">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <x-flux::icon name="exclamation-triangle" class="size-5 text-yellow-400" />
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                        Your account will be activated soon. Please check back later.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                <!-- Latest Announcements -->
                <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Latest Announcements</h3>
                    @if($announcements->isEmpty())
                        <p class="text-gray-500 dark:text-gray-400">No announcements available.</p>
                    @else
                        <div class="space-y-4">
                            @foreach($announcements as $announcement)
                                <div class="border-b dark:border-zinc-700 pb-3">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $announcement->title }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                        {{ Str::limit($announcement->description, 150) }}
                                    </p>
                                    @if($announcement->file)
                                        <div class="mt-2">
                                            @php
                                                $filePath = asset('uploads/' . $announcement->file);
                                                $fileExtension = pathinfo($announcement->file, PATHINFO_EXTENSION);
                                            @endphp

                                            @if(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ $filePath }}" alt="Attachment" class="w-24 h-auto rounded-lg">
                                            @elseif($fileExtension === 'pdf')
                                                <a href="{{ $filePath }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    View PDF
                                                </a>
                                            @else
                                                <a href="{{ $filePath }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline">
                                                    View Attachment
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                    <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                        {{ $announcement->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</x-layouts.app>



















