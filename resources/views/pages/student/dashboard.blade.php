<x-layouts.app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
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

            <!-- Recent Activities -->
            <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Activities</h3>
                @if($student->activities->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No recent activities found.</p>
                @else
                    <div class="space-y-4">
                        @foreach($student->activities as $activity)
                            <div class="border-b dark:border-zinc-700 pb-3">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $activity->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Type: {{ ucfirst($activity->type) }} |
                                    Semester/Year: {{ $activity->sem }}/{{ $activity->year }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Recent Challenges -->
            <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Recent Challenges</h3>
                @if($student->challenges->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No recent challenges found.</p>
                @else
                    <div class="space-y-4">
                        @foreach($student->challenges as $challenge)
                            <div class="border-b dark:border-zinc-700 pb-3">
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $challenge->name }}</h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Type: {{ ucfirst($challenge->type) }} |
                                    Semester/Year: {{ $challenge->sem }}/{{ $challenge->year }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

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
    </div>
</x-layouts.app>


