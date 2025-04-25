<x-layouts.app :title="__('Student Reports')">
    <div x-data="{
        selectedStudent: null,
        searchQuery: '',
        filterStudents(row) {
            if (!this.searchQuery) return true;

            const query = this.searchQuery.toLowerCase();
            const name = row.querySelector('.student-name').textContent.toLowerCase();
            const matricNo = row.querySelector('.student-matric').textContent.toLowerCase();

            return name.includes(query) || matricNo.includes(query);
        }
    }">
        <!-- Search and Filter Section -->
        <div class="mb-6 bg-white dark:bg-zinc-800 rounded-lg shadow p-4">
            <div class="flex gap-4 items-center">
                <input type="text"
                    x-model="searchQuery"
                    @input="$nextTick(() => {
                        selectedStudent = null;  // Reset expanded section
                        document.querySelectorAll('tbody tr.student-row').forEach(row => {
                            row.style.display = filterStudents(row) ? '' : 'none';
                        });
                    })"
                    placeholder="Search by name or matric no..."
                    class="flex-1 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-gray-300">
                <select id="intake-filter" class="rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-gray-300">
                    <option value="">All Intakes</option>
                </select>
            </div>
        </div>

        <!-- Student List Table -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student Details</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activities</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Challenges</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">KPI Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Progress</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($students as $student)
                            <!-- Main Row -->
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50 student-row"
                                :class="{'bg-gray-50 dark:bg-zinc-700/50': selectedStudent === {{ $student->id }}}">
                                <!-- Student Details -->
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full" src="{{ $student->img ?? asset('images/default-avatar.png') }}" alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 student-name">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 student-matric">
                                                {{ $student->matric_no }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $student->program }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                Intake: {{ $student->intake }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Activities Summary -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        Total: {{ $student->activities->count() }}
                                    </div>
                                </td>

                                <!-- Challenges Summary -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        Total: {{ $student->challenges->count() }}
                                    </div>
                                </td>

                                <!-- KPI Status -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        CGPA: {{ $student->result?->cgpa ?? 'N/A' }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Professional Certification: {{ $student->result?->professional_certification ?? 0 }}<br>
                                        Leadership: {{ $student->result?->leadership_competition ?? 0 }}
                                    </div>
                                </td>

                                <!-- Course Progress -->
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        Enrolled: {{ $student->enrolments->count() }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Avg Grade: {{ $student->enrolments->avg('pointer') ?? 'N/A' }}
                                    </div>
                                </td>

                                <!-- Actions -->
                                <td class="px-6 py-4 text-sm font-medium">
                                    <div class="flex gap-2">
                                        <button @click="selectedStudent = selectedStudent === {{ $student->id }} ? null : {{ $student->id }}"
                                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                            <span x-text="selectedStudent === {{ $student->id }} ? 'Hide Details' : 'View Details'"></span>
                                        </button>
                                        <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                            onclick="exportReport('{{ $student->id }}')">
                                            Export
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Expanded Details Row -->
                            <tr x-show="selectedStudent === {{ $student->id }}"
                                x-cloak
                                x-data="{ activeTab: 'info' }"
                                class="bg-gray-50 dark:bg-zinc-700/30">
                                <td colspan="6" class="px-6 py-4">
                                    <!-- Tab Navigation -->
                                    <div class="border-b border-gray-200 dark:border-zinc-700 mb-4">
                                        <nav class="flex space-x-4" aria-label="Tabs">
                                            <button @click="activeTab = 'info'"
                                                    :class="{'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400': activeTab === 'info',
                                                            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'info'}"
                                                    class="px-3 py-2 text-sm font-medium">
                                                Student Information
                                            </button>
                                            <button @click="activeTab = 'activities'"
                                                    :class="{'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400': activeTab === 'activities',
                                                            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'activities'}"
                                                    class="px-3 py-2 text-sm font-medium">
                                                Activities
                                            </button>
                                            <button @click="activeTab = 'challenges'"
                                                    :class="{'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400': activeTab === 'challenges',
                                                            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'challenges'}"
                                                    class="px-3 py-2 text-sm font-medium">
                                                Challenges
                                            </button>
                                            <button @click="activeTab = 'kpi'"
                                                    :class="{'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400': activeTab === 'kpi',
                                                            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'kpi'}"
                                                    class="px-3 py-2 text-sm font-medium">
                                                KPI Status
                                            </button>
                                            <button @click="activeTab = 'courses'"
                                                    :class="{'text-indigo-600 dark:text-indigo-400 border-b-2 border-indigo-600 dark:border-indigo-400': activeTab === 'courses',
                                                            'text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300': activeTab !== 'courses'}"
                                                    class="px-3 py-2 text-sm font-medium">
                                                Courses
                                            </button>
                                        </nav>
                                    </div>

                                    <!-- Tab Contents -->
                                    <div x-show="activeTab === 'info'" class="space-y-4">
                                        <!-- Student Information -->
                                        <div class="space-y-2">
                                            <h3 class="font-medium text-gray-900 dark:text-gray-100">Student Information</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Matric No:</span> {{ $student->matric_no }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Name:</span> {{ $student->name }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Program:</span> {{ $student->program }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Email:</span> {{ $student->email }}
                                            </p>
                                        </div>

                                        <!-- Contact Information -->
                                        <div class="space-y-2">
                                            <h3 class="font-medium text-gray-900 dark:text-gray-100">Contact Information</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Phone:</span> {{ $student->phone ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">State:</span> {{ $student->state ?? 'N/A' }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Address:</span> {{ $student->address ?? 'N/A' }}
                                            </p>
                                        </div>

                                        <!-- Additional Details -->
                                        <div class="space-y-2">
                                            <h3 class="font-medium text-gray-900 dark:text-gray-100">Additional Details</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Faculty:</span> {{ $student->faculty }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Intake:</span> {{ $student->intake }}
                                            </p>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <span class="font-medium">Motto:</span> {{ $student->motto ?? 'N/A' }}
                                            </p>
                                        </div>
                                    </div>

                                    <!-- Activities Tab Content -->
                                    <div x-show="activeTab === 'activities'" class="space-y-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                                <thead class="bg-gray-50 dark:bg-zinc-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester/Year</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remark</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Upload</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                                    @forelse($student->activities as $activity)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $activity->name }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $activity->type }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $activity->sem }}/{{ $activity->year }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ Str::limit($activity->remark, 50) }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                @if($activity->uploads)
                                                                    <a href="{{ asset('storage/' . $activity->uploads) }}"
                                                                       class="text-indigo-600 dark:text-indigo-400 hover:underline"
                                                                       target="_blank">
                                                                        View
                                                                    </a>
                                                                @else
                                                                    <span class="text-gray-500 dark:text-gray-400">No upload</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                No activities found
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div x-show="activeTab === 'challenges'" class="space-y-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                                <thead class="bg-gray-50 dark:bg-zinc-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester/Year</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                                    @forelse($student->challenges as $challenge)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $challenge->name }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $challenge->type }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Semester {{ $challenge->sem }} / Year {{ $challenge->year }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ Str::limit($challenge->remark, 100) }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No challenges found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>

                                    <div x-show="activeTab === 'kpi'" class="space-y-4">
                                        <!-- KPI Goals vs Achievement -->
                                        @php
                                            $kpiGoal = \App\Models\KpiGoal::first();
                                        @endphp
                                        <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow">
                                            <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">KPI Goals Achievement Status</h4>
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                                <!-- Academic Goals - Left Column -->
                                                <div class="space-y-2">
                                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Academic Goals</h5>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">CGPA</span>
                                                        <span class="font-medium {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->cgpa < ($kpiGoal->cgpa ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->cgpa ?? 'N/A' }} / {{ $kpiGoal->cgpa ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Graduate on Time</span>
                                                        <span class="font-medium {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->graduate_on_time < ($kpiGoal->graduate_on_time ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->graduate_on_time ?? 'N/A' }} / {{ $kpiGoal->graduate_on_time ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <!-- Activities and Competitions - Middle Column -->
                                                <div class="space-y-6">
                                                    <!-- Activities Section -->
                                                    <div class="space-y-2">
                                                        <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Activities Goals</h5>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">Faculty</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('faculty_activity') < ($kpiGoal->faculty_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('faculty_activity') }} / {{ $kpiGoal->faculty_activity ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">University</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('university_activity') < ($kpiGoal->university_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('university_activity') }} / {{ $kpiGoal->university_activity ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">National</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('national_activity') < ($kpiGoal->national_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('national_activity') }} / {{ $kpiGoal->national_activity ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">International</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('international_activity') < ($kpiGoal->international_activity ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('international_activity') }} / {{ $kpiGoal->international_activity ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Competitions Section -->
                                                    <div class="space-y-2">
                                                        <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Competition Goals</h5>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">Faculty</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('faculty_competition') < ($kpiGoal->faculty_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('faculty_competition') }} / {{ $kpiGoal->faculty_competition ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">University</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('university_competition') < ($kpiGoal->university_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('university_competition') }} / {{ $kpiGoal->university_competition ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">National</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('national_competition') < ($kpiGoal->national_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('national_competition') }} / {{ $kpiGoal->national_competition ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                        <div class="flex justify-between">
                                                            <span class="text-gray-600 dark:text-gray-400">International</span>
                                                            <span class="font-medium {{ $student->kpiIndexes->sum('international_competition') < ($kpiGoal->international_competition ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                                {{ $student->kpiIndexes->sum('international_competition') }} / {{ $kpiGoal->international_competition ?? 'N/A' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Other Goals - Right Column -->
                                                <div class="space-y-2">
                                                    <h5 class="text-sm font-medium text-gray-500 dark:text-gray-400">Other Goals</h5>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Leadership</span>
                                                        <span class="font-medium {{ $student->kpiIndexes->sum('leadership') < ($kpiGoal->leadership ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $student->kpiIndexes->sum('leadership') }} / {{ $kpiGoal->leadership ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Professional Cert</span>
                                                        <span class="font-medium {{ $student->kpiIndexes->sum('professional_certification') < ($kpiGoal->professional_certification ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $student->kpiIndexes->sum('professional_certification') }} / {{ $kpiGoal->professional_certification ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Mobility Program</span>
                                                        <span class="font-medium {{ $student->kpiIndexes->sum('mobility_program') < ($kpiGoal->mobility_program ?? 0) ? 'text-red-500' : 'text-gray-900 dark:text-gray-100' }}">
                                                            {{ $student->kpiIndexes->sum('mobility_program') }} / {{ $kpiGoal->mobility_program ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Latest KPI Summary -->
                                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                            <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Academic Performance</h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Latest CGPA</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->cgpa ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Graduate on Time</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sortByDesc('year')->sortByDesc('sem')->first()?->graduate_on_time ?? 'N/A' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Activities Total</h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('faculty_activity') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('university_activity') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('national_activity') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('international_activity') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-white dark:bg-zinc-800 p-4 rounded-lg shadow">
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Competitions Total</h4>
                                                <div class="space-y-2">
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('faculty_competition') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('university_competition') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('national_competition') }}
                                                        </span>
                                                    </div>
                                                    <div class="flex justify-between">
                                                        <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $student->kpiIndexes->sum('international_competition') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Semester-wise KPI Details -->
                                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow overflow-hidden">
                                            <div class="overflow-x-auto">
                                                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                                    <thead class="bg-gray-50 dark:bg-zinc-700">
                                                        <tr>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester/Year</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CGPA</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activities</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Competitions</th>
                                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Other Achievements</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                                        @forelse($student->kpiIndexes->sortByDesc('year')->sortByDesc('sem') as $kpi)
                                                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                    Semester {{ $kpi->sem }} / Year {{ $kpi->year }}
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                    {{ number_format($kpi->cgpa, 2) }}
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                    <div class="space-y-1">
                                                                        <div>Faculty: {{ $kpi->faculty_activity }}</div>
                                                                        <div>University: {{ $kpi->university_activity }}</div>
                                                                        <div>National: {{ $kpi->national_activity }}</div>
                                                                        <div>International: {{ $kpi->international_activity }}</div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                    <div class="space-y-1">
                                                                        <div>Faculty: {{ $kpi->faculty_competition }}</div>
                                                                        <div>University: {{ $kpi->university_competition }}</div>
                                                                        <div>National: {{ $kpi->national_competition }}</div>
                                                                        <div>International: {{ $kpi->international_competition }}</div>
                                                                    </div>
                                                                </td>
                                                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                                                    <div class="space-y-1">
                                                                        <div>Leadership: {{ $kpi->leadership }}</div>
                                                                        <div>Professional Cert: {{ $kpi->professional_certification }}</div>
                                                                        <div>Mobility Program: {{ $kpi->mobility_program }}</div>
                                                                        <div>Employability: {{ $kpi->employability }}</div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                                    No KPI records found
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div x-show="activeTab === 'courses'" class="space-y-4">
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                                                <thead class="bg-gray-50 dark:bg-zinc-700">
                                                    <tr>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester/Year</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Grade</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pointer</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Rating</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                                                    @forelse($student->enrolments as $enrolment)
                                                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $enrolment->course->code }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $enrolment->course->name }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">Semester {{ $enrolment->sem }} / Year {{ $enrolment->year }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $enrolment->grade }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ number_format($enrolment->pointer, 2) }}</td>
                                                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $enrolment->rating ?? 'Not rated' }}</td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">No courses enrolled</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $students->links() }}
            </div>
        </div>
    </div>

    <script>
        function exportReport(studentId) {
            window.location.href = `/admin/student/${studentId}/export`;
        }
    </script>
</x-layouts.app>



























