<x-layouts.app :title="__('Manage KPI Goals')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
    @if (session('alert'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showAlert(
                    '{{ session('alert.type') }}',
                    '{{ session('alert.title') }}',
                    '{{ session('alert.message') }}'
                );
            });
        </script>
    @endif
        <!-- Chart container -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">KPI Goals Overview</h2>
            <div class="w-full h-[400px]">
                <canvas id="kpiGoalsChart"></canvas>
            </div>
        </div>

        <!-- KPI Goals Table -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CGPA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activities</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Competitions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leadership</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Other Metrics</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700" x-data="{ openGoal: null }">
                    @forelse ($kpiGoals as $goal)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $goal->cgpa }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Faculty: {{ $goal->faculty_activity }}<br>
                                University: {{ $goal->university_activity }}<br>
                                National: {{ $goal->national_activity }}<br>
                                International: {{ $goal->international_activity }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Faculty: {{ $goal->faculty_competition }}<br>
                                University: {{ $goal->university_competition }}<br>
                                National: {{ $goal->national_competition }}<br>
                                International: {{ $goal->international_competition }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $goal->leadership }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Graduate on Time: {{ $goal->graduate_on_time }}<br>
                                Professional Certification: {{ $goal->professional_certification }}<br>
                                Employability: {{ $goal->employability }}<br>
                                Mobility Program: {{ $goal->mobility_program }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="openGoal = openGoal === {{ $goal->id }} ? null : {{ $goal->id }}"
                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                            </td>
                        </tr>
                        <!-- Expandable Edit Form -->
                        <tr x-show="openGoal === {{ $goal->id }}" x-collapse>
                            <td colspan="6" class="px-6 py-4">
                                <form action="{{ route('admin.kpi-goal.update', ['id' => $goal->id]) }}" method="POST" class="space-y-4">
                                    @csrf
                                    @method('PUT')

                                    <!-- Basic Metrics -->
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CGPA</label>
                                            <input type="number" name="cgpa" value="{{ $goal->cgpa }}" step="0.01" min="0" max="4" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leadership</label>
                                            <input type="number" name="leadership" value="{{ $goal->leadership }}" min="0" required
                                                   class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                        </div>
                                    </div>

                                    <!-- Activities Section -->
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Activities</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty Activity</label>
                                                <input type="number" name="faculty_activity" value="{{ $goal->faculty_activity }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">University Activity</label>
                                                <input type="number" name="university_activity" value="{{ $goal->university_activity }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">National Activity</label>
                                                <input type="number" name="national_activity" value="{{ $goal->national_activity }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">International Activity</label>
                                                <input type="number" name="international_activity" value="{{ $goal->international_activity }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Competitions Section -->
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Competitions</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty Competition</label>
                                                <input type="number" name="faculty_competition" value="{{ $goal->faculty_competition }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">University Competition</label>
                                                <input type="number" name="university_competition" value="{{ $goal->university_competition }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">National Competition</label>
                                                <input type="number" name="national_competition" value="{{ $goal->national_competition }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">International Competition</label>
                                                <input type="number" name="international_competition" value="{{ $goal->international_competition }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Other Metrics Section -->
                                    <div class="space-y-4">
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">Other Metrics</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Graduate on Time</label>
                                                <select name="graduate_on_time" required
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100">
                                                    <option value="Yes" {{ $goal->graduate_on_time === 'Yes' ? 'selected' : '' }}>Yes</option>
                                                    <option value="No" {{ $goal->graduate_on_time === 'No' ? 'selected' : '' }}>No</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Professional Certification</label>
                                                <input type="number" name="professional_certification" value="{{ $goal->professional_certification }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employability</label>
                                                <select name="employability" required
                                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md bg-white dark:bg-zinc-800 text-gray-900 dark:text-gray-100">
                                                    <option value="Within 1 year" {{ $goal->employability === 'Within 1 year' ? 'selected' : '' }}>Within 1 year</option>
                                                    <option value="Within 2 years" {{ $goal->employability === 'Within 2 years' ? 'selected' : '' }}>Within 2 years</option>
                                                    <option value="More than 2 years" {{ $goal->employability === 'More than 2 years' ? 'selected' : '' }}>More than 2 years</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mobility Program</label>
                                                <input type="number" name="mobility_program" value="{{ $goal->mobility_program }}" min="0" required
                                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-3">
                                        <button type="button" @click="openGoal = null"
                                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                            Cancel
                                        </button>
                                        <button type="submit"
                                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                            Update KPI Goal
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No KPI goals found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script>
        function initKpiGoalsChart() {
            const ctx = document.getElementById('kpiGoalsChart');
            if (!ctx) return;

            const goals = @json($kpiGoals->first());

            if (!goals) return;

            // Destroy existing chart instance if it exists
            if (window.kpiGoalsChart instanceof Chart) {
                window.kpiGoalsChart.destroy();
            }

            const data = {
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
                    label: 'Target Goals',
                    data: [
                        goals.faculty_activity,
                        goals.university_activity,
                        goals.national_activity,
                        goals.international_activity,
                        goals.faculty_competition,
                        goals.university_competition,
                        goals.national_competition,
                        goals.international_competition,
                        goals.leadership,
                        goals.professional_certification,
                        goals.mobility_program
                    ],
                    backgroundColor: '#4F46E5',
                    borderColor: '#4F46E5',
                    borderWidth: 1
                }]
            };

            const config = {
                type: 'bar',
                data: data,
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
                                stepSize: 1
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
            };

            // Store chart instance in window object
            window.kpiGoalsChart = new Chart(ctx, config);
        }

        // Initialize chart when DOM is loaded
        document.addEventListener('DOMContentLoaded', initKpiGoalsChart);

        // Re-initialize chart when navigating using Turbo/LiveWire
        document.addEventListener('turbo:load', initKpiGoalsChart);
        document.addEventListener('livewire:navigated', initKpiGoalsChart);
    </script>
    @endpush
</x-layouts.app>














