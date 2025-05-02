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

                    <!-- Activities -->
                    <div class="space-y-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Competition Target</h3>
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->faculty_competition ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->university_competition ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->national_competition ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpiGoal->international_competition ?? '-' }}</span>
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

                                    <!-- Add the pie chart canvas here -->
                                    <div class="h-[300px] mb-4">
                                        <canvas id="combinedChart-{{ $year }}-{{ $sem }}"></canvas>
                                    </div>

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

                                    <!-- Competitions -->
                                    <div class="space-y-2 mb-4">
                                        <h5 class="text-sm font-medium text-gray-700 dark:text-gray-300">Competitions</h5>
                                        <div class="grid grid-cols-2 gap-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Faculty</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->faculty_competition }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">University</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->university_competition }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">National</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->national_competition }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">International</span>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kpi->international_competition }}</span>
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

                <!-- Add the canvas for the bar chart -->
                <div class="mt-6 w-full h-[300px]">
                    <canvas id="overallStatsChart"></canvas>
                </div>
            </div>
        </div>

        <!-- CGPA Trend Chart -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">CGPA Trend</h2>
                <div class="w-full h-[300px]">
                    <canvas id="cgpaChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Check if semesterCharts already exists before declaring
        if (typeof window.semesterCharts === 'undefined') {
            window.semesterCharts = {};
        }

        function initAllCharts() {
            initCGPAChart();
            initOverallStatsChart();
            // Initialize pie charts for each semester
            @foreach($kpiIndexes->groupBy('year') as $year => $yearIndexes)
                @foreach($yearIndexes->groupBy('sem') as $sem => $semesterIndexes)
                    @php
                        $kpi = $semesterIndexes->first();
                    @endphp
                    initSemesterPieCharts('{{ $year }}', '{{ $sem }}', @json($kpi));
                @endforeach
            @endforeach
        }

        // Initialize on DOM content loaded
        document.addEventListener('DOMContentLoaded', initAllCharts);

        // Initialize when navigating with Livewire
        document.addEventListener('livewire:navigated', initAllCharts);

        // Initialize when navigating with Turbo
        document.addEventListener('turbo:load', initAllCharts);

        // Initialize when Livewire updates the page
        document.addEventListener('livewire:update', initAllCharts);

        function destroyAllCharts() {
            // Destroy CGPA chart
            if (window.cgpaChart instanceof Chart) {
                window.cgpaChart.destroy();
            }

            // Destroy overall stats chart
            if (window.overallStatsChart instanceof Chart) {
                window.overallStatsChart.destroy();
            }

            // Destroy all semester charts
            Object.values(window.semesterCharts).forEach(chart => {
                if (chart instanceof Chart) {
                    chart.destroy();
                }
            });
            window.semesterCharts = {};
        }

        function initCGPAChart() {
            destroyAllCharts();
            const ctx = document.getElementById('cgpaChart');
            if (!ctx) return;

            const cgpaData = @json($kpiIndexes->sortBy('sem')->sortBy('year')
                ->map(function($kpi) {
                    return [
                        'semester' => "Semester {$kpi->sem}, Year {$kpi->year}",
                        'cgpa' => $kpi->cgpa
                    ];
                })->values());

            window.cgpaChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: cgpaData.map(item => item.semester),
                    datasets: [{
                        label: 'CGPA',
                        data: cgpaData.map(item => item.cgpa),
                        borderColor: '#4F46E5',
                        backgroundColor: '#4F46E5',
                        tension: 0.4,
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: false,
                            min: 0,
                            max: 4,
                            ticks: {
                                stepSize: 0.5
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 10,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        function initOverallStatsChart() {
            const ctx = document.getElementById('overallStatsChart');
            if (!ctx) return;

            // Get statistics and goals data using PHP's json_encode
            const statistics = {!! json_encode([
                'faculty_activities' => $statistics['faculty_activities'] ?? 0,
                'university_activities' => $statistics['university_activities'] ?? 0,
                'national_activities' => $statistics['national_activities'] ?? 0,
                'international_activities' => $statistics['international_activities'] ?? 0,
                'faculty_competitions' => $statistics['faculty_competitions'] ?? 0,
                'university_competitions' => $statistics['university_competitions'] ?? 0,
                'national_competitions' => $statistics['national_competitions'] ?? 0,
                'international_competitions' => $statistics['international_competitions'] ?? 0,
                'leadership_roles' => $statistics['leadership_roles'] ?? 0,
                'certifications' => $statistics['certifications'] ?? 0,
                'mobility_programs' => $statistics['mobility_programs'] ?? 0
            ]) !!};

            const goals = {!! json_encode([
                'faculty_activity' => $kpiGoal->faculty_activity ?? 0,
                'university_activity' => $kpiGoal->university_activity ?? 0,
                'national_activity' => $kpiGoal->national_activity ?? 0,
                'international_activity' => $kpiGoal->international_activity ?? 0,
                'faculty_competition' => $kpiGoal->faculty_competition ?? 0,
                'university_competition' => $kpiGoal->university_competition ?? 0,
                'national_competition' => $kpiGoal->national_competition ?? 0,
                'international_competition' => $kpiGoal->international_competition ?? 0,
                'leadership' => $kpiGoal->leadership ?? 0,
                'professional_certification' => $kpiGoal->professional_certification ?? 0,
                'mobility_program' => $kpiGoal->mobility_program ?? 0
            ]) !!};

            const labels = [
                'Faculty Activities',
                'University Activities',
                'National Activities',
                'International Activities',
                'Faculty Competitions',
                'University Competitions',
                'National Competitions',
                'International Competitions',
                'Leadership Roles',
                'Professional Certifications',
                'Mobility Programs'
            ];

            // Destroy existing chart if it exists
            if (window.overallStatsChart instanceof Chart) {
                window.overallStatsChart.destroy();
            }

            window.overallStatsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Current Achievement',
                            data: Object.values(statistics),
                            backgroundColor: '#4F46E5',
                            borderColor: '#4F46E5',
                            borderWidth: 1
                        },
                        {
                            label: 'Target Goal',
                            data: Object.values(goals),
                            backgroundColor: 'rgba(79, 70, 229, 0.2)',
                            borderColor: '#4F46E5',
                            borderWidth: 1,
                            borderDash: [5, 5]
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                stepSize: 1,
                                callback: function(value) {
                                    if (Math.floor(value) === value) {
                                        return value;
                                    }
                                }
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            },
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                color: '#6B7280'
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.8)',
                            padding: 10,
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            borderColor: 'rgba(255, 255, 255, 0.1)',
                            borderWidth: 1
                        }
                    }
                }
            });
        }

        function initSemesterPieCharts(year, sem, kpi) {
            const chartId = `combinedChart-${year}-${sem}`;
            const combinedCtx = document.getElementById(chartId);
            if (!combinedCtx) return;

            // Destroy existing chart if it exists
            if (window.semesterCharts[chartId] instanceof Chart) {
                window.semesterCharts[chartId].destroy();
            }

            window.semesterCharts[chartId] = new Chart(combinedCtx, {
                type: 'pie',
                data: {
                    labels: [
                        'Faculty Activities',
                        'University Activities',
                        'National Activities',
                        'International Activities',
                        'Faculty Competitions',
                        'University Competitions',
                        'National Competitions',
                        'International Competitions',
                        'Leadership',
                        'Professional Certification',
                        'Mobility Program'
                    ],
                    datasets: [{
                        data: [
                            kpi.faculty_activity,
                            kpi.university_activity,
                            kpi.national_activity,
                            kpi.international_activity,
                            kpi.faculty_competition,
                            kpi.university_competition,
                            kpi.national_competition,
                            kpi.international_competition,
                            kpi.leadership,
                            kpi.professional_certification,
                            kpi.mobility_program
                        ],
                        backgroundColor: [
                            '#4F46E5', // Faculty Activities
                            '#6366F1', // University Activities
                            '#818CF8', // National Activities
                            '#A5B4FC', // International Activities
                            '#10B981', // Faculty Competitions
                            '#34D399', // University Competitions
                            '#6EE7B7', // National Competitions
                            '#A7F3D0', // International Competitions
                            '#F59E0B', // Leadership
                            '#FBBF24', // Professional Certification
                            '#FCD34D'  // Mobility Program
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#6B7280',
                                font: {
                                    size: 11
                                },
                                padding: 10
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return `${label}: ${value}`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Clean up charts when navigating away
        document.addEventListener('livewire:navigating', destroyAllCharts);
        document.addEventListener('turbo:before-visit', destroyAllCharts);
    </script>
    @endpush
</x-layouts.app>



































