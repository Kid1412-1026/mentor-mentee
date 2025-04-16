<x-layouts.app :title="__('Student Report')" x-data="reportApp">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Search and Filter Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 mb-4">
            <div class="flex gap-4 items-center">
                <input type="text" id="search" placeholder="Search by name or matric no..."
                    class="flex-1 rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-gray-300">
                <select id="program-filter" class="rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-900 dark:text-gray-300">
                    <option value="">All Programs</option>
                </select>
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
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                            <!-- Student Details -->
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $student->img ?? asset('images/default-avatar.png') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $student->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
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
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Faculty: {{ $student->activities->where('type', 'faculty')->count() }}<br>
                                    University: {{ $student->activities->where('type', 'university')->count() }}<br>
                                    National: {{ $student->activities->where('type', 'national')->count() }}
                                </div>
                            </td>

                            <!-- Challenges Summary -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    Total: {{ $student->challenges->count() }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Latest: {{ optional($student->challenges->last())->created_at?->format('d/m/Y') ?? 'N/A' }}
                                </div>
                            </td>

                            <!-- KPI Status -->
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    CGPA: {{ $student->result?->cgpa ?? 'N/A' }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Professional Cert: {{ $student->result?->professional_certification ?? 0 }}<br>
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
                                    <button onclick="viewDetails({{ $student->id }})"
                                            class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                                        View Details
                                    </button>
                                    <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300"
                                        onclick="exportReport('{{ $student->id }}')">
                                        Export
                                    </button>
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
        function viewDetails(studentId) {
            window.location.href = `/admin/student/${studentId}/show`;
        }

        function exportReport(studentId) {
            window.location.href = `/admin/student/${studentId}/export`;
        }
    </script>
</x-layouts.app>





