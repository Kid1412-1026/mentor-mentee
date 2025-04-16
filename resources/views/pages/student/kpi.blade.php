<x-layouts.app :title="__('View KPI')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- KPI Goals Card -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">KPI Goals</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Academic Metrics -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Academic Performance</h3>
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">CGPA Target</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->cgpa ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Leadership -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Leadership</h3>
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Leadership Positions</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->leadership ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Activities -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Activities Target</h3>
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->faculty_activity ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->university_activity ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->national_activity ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->international_activity ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Other Metrics -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Other Metrics</h3>
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg grid grid-cols-1 gap-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Professional Certification</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->professional_certification ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Mobility Program</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->mobility_program ?? '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI Achievement by Semester -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">KPI Achievement by Semester</h2>

                @forelse ($kpiIndexes->groupBy('year') as $year => $yearIndexes)
                    <div class="mb-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Year {{ $year }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($yearIndexes->groupBy('sem') as $sem => $semesterIndexes)
                                @php
                                    $kpi = $semesterIndexes->first();
                                @endphp
                                <div class="bg-gray-50 dark:bg-zinc-800 rounded-lg p-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Semester {{ $sem }}</h4>

                                    <!-- Activities -->
                                    <div class="space-y-2 mb-4">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Activities</h5>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Faculty</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->faculty_activity }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">University</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->university_activity }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">National</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->national_activity }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">International</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->international_activity }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Academic -->
                                    <div class="space-y-2 mb-4">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Academic</h5>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">CGPA</span>
                                            <span class="font-medium text-gray-900 dark:text-gray-100">{{ number_format($kpi->cgpa, 2) }}</span>
                                        </div>
                                    </div>

                                    <!-- Other Metrics -->
                                    <div class="space-y-2">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Other Achievements</h5>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Leadership</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->leadership }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Certifications</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->professional_certification }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Mobility</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->mobility_program }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 dark:text-gray-400 py-4">
                        No KPI records found.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Overall Statistics -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Overall Statistics</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Activities by Level</h3>
                        <div class="space-y-1 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Faculty</span>
                                <span class="text-sm font-medium {{ $statistics['faculty_activities'] < ($kpiGoal->faculty_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['faculty_activities'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">University</span>
                                <span class="text-sm font-medium {{ $statistics['university_activities'] < ($kpiGoal->university_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['university_activities'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">National</span>
                                <span class="text-sm font-medium {{ $statistics['national_activities'] < ($kpiGoal->national_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['national_activities'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">International</span>
                                <span class="text-sm font-medium {{ $statistics['international_activities'] < ($kpiGoal->international_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['international_activities'] }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-4">Competitions by Level</h3>
                        <div class="space-y-1 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Faculty</span>
                                <span class="text-sm font-medium {{ $statistics['faculty_competitions'] < ($kpiGoal->faculty_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['faculty_competitions'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">University</span>
                                <span class="text-sm font-medium {{ $statistics['university_competitions'] < ($kpiGoal->university_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['university_competitions'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">National</span>
                                <span class="text-sm font-medium {{ $statistics['national_competitions'] < ($kpiGoal->national_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['national_competitions'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">International</span>
                                <span class="text-sm font-medium {{ $statistics['international_competitions'] < ($kpiGoal->international_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['international_competitions'] }}
                                </span>
                            </div>
                        </div>

                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-4">Other Achievements</h3>
                        <div class="space-y-1 mt-2">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Leadership Roles</span>
                                <span class="text-sm font-medium {{ $statistics['leadership_roles'] < ($kpiGoal->leadership ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['leadership_roles'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Professional Certifications</span>
                                <span class="text-sm font-medium {{ $statistics['certifications'] < ($kpiGoal->professional_certification ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['certifications'] }}
                                </span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600 dark:text-gray-400">Mobility Programs</span>
                                <span class="text-sm font-medium {{ $statistics['mobility_programs'] < ($kpiGoal->mobility_program ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                    {{ $statistics['mobility_programs'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Latest CGPA</h3>
                        <p class="text-2xl font-semibold {{ $statistics['latest_cgpa'] < ($kpiGoal->cgpa ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                            {{ number_format($statistics['latest_cgpa'], 2) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>







