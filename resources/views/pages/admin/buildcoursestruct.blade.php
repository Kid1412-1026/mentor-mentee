<x-layouts.app :title="__('Build Course Structure')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-4" x-data="{ isAddingCourses: false, isExportVisible: false, selectedIntakesForExport: [] }">
        <!-- Header Section -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Course Structure</h2>
                    <p class="text-gray-600 dark:text-gray-400">View and manage course structures by programme and intake</p>
                </div>
                <div class="flex gap-2">
                    <button @click="isExportVisible = !isExportVisible"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                        <x-flux::icon name="document-arrow-down" class="size-5 mr-2" />
                        Export Reports
                    </button>
                    <button @click="isAddingCourses = true"
                            x-show="!isAddingCourses"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        <x-flux::icon name="plus" class="size-5 mr-2" />
                        Add Courses
                    </button>
                    <a href="{{ route('admin.course.index') }}"
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                        <x-flux::icon name="arrow-left" class="size-5 mr-2" />
                        Back to Courses
                    </a>
                </div>
            </div>
        </div>

        <!-- Export Form -->
        <div x-show="isExportVisible"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-4">
            <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Select Intakes to Export</h4>
            <div class="space-y-4">
                @foreach($intakes as $intake)
                <div class="flex items-center">
                    <input type="checkbox"
                           x-model="selectedIntakesForExport"
                           value="{{ $intake }}"
                           id="export-intake-{{ $intake }}"
                           class="rounded border-gray-300 dark:border-zinc-700 text-indigo-600">
                    <label for="export-intake-{{ $intake }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                        {{ $intake }}
                    </label>
                </div>
                @endforeach
            </div>

            <div class="mt-4 flex justify-end gap-3">
                <button type="button"
                        @click="isExportVisible = false"
                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                    Cancel
                </button>
                <button @click="if(selectedIntakesForExport.length > 0) exportCourseStructure(selectedIntakesForExport)"
                        :class="{ 'opacity-50 cursor-not-allowed': selectedIntakesForExport.length === 0 }"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                    Export Selected Reports
                </button>
            </div>
        </div>

        <!-- Add Courses Form (Hidden by default) -->
        <div x-show="isAddingCourses"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-4">

            <form id="addCoursesForm" action="{{ route('admin.buildcoursestruct.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <div>
                        <label for="programme_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Programme</label>
                        <select id="programme_id" name="programme_id" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Programme</option>
                            @foreach($programmes as $programme)
                                <option value="{{ $programme->id }}">{{ $programme->code }} - {{ $programme->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="intake" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intake</label>
                        <select id="intake" name="intake" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Select Intake</option>
                            @foreach($intakes as $intake)
                                <option value="{{ $intake }}">{{ $intake }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Course Selection Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700" style="display: block;">
                        <thead class="bg-gray-50 dark:bg-zinc-800" style="display: table; width: calc(100% - 1rem); table-layout: fixed;">
                            <tr>
                                <th style="width: 10%" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                                <th style="width: 30%" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th style="width: 10%" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                                <th style="width: 15%" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
                                <th style="width: 15%" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty</th>
                                <th style="width: 10%" class="px-6 py-3 text-center">
                                    <input type="checkbox" id="select-all"
                                           class="rounded border-gray-300 dark:border-zinc-700 text-indigo-600">
                                </th>
                            </tr>
                        </thead>
                        <tbody style="display: block; max-height: calc(10 * 3.5rem); overflow-y: auto;">
                            @foreach($courses as $course)
                                <tr style="display: table; width: 100%; table-layout: fixed;">
                                    <td style="width: 10%" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $course->code }}</td>
                                    <td style="width: 30%" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $course->name }}</td>
                                    <td style="width: 10%" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $course->credit_hour }}</td>
                                    <td style="width: 15%" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $course->section }}</td>
                                    <td style="width: 15%" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{ $course->faculty }}</td>
                                    <td style="width: 10%" class="px-6 py-4 text-center">
                                        <input type="checkbox" name="course_ids[]" value="{{ $course->id }}"
                                               class="course-checkbox rounded border-gray-300 dark:border-zinc-700 text-indigo-600">
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="isAddingCourses = false"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition-colors">
                        Cancel
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Add Selected Courses
                    </button>
                </div>
            </form>
        </div>

        <!-- Course Structure Table -->
        <div x-data="{ selectedIntake: null }" class="space-y-4">
            <!-- Intake Selection Buttons -->
            <div class="flex flex-wrap gap-2">
                @foreach($intakes as $intake)
                    <button
                        @click="selectedIntake = selectedIntake === '{{ $intake }}' ? null : '{{ $intake }}'"
                        :class="{'bg-indigo-600 text-white': selectedIntake === '{{ $intake }}', 'bg-gray-100 dark:bg-zinc-800 text-gray-700 dark:text-gray-300': selectedIntake !== '{{ $intake }}'}"
                        class="px-4 py-2 rounded-md transition-colors hover:bg-indigo-500 hover:text-white">
                        {{ $intake }}
                    </button>
                @endforeach
            </div>

            <!-- Course Structure Table (Hidden by default) -->
            <div
                x-show="selectedIntake !== null"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 transform -translate-y-2"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 transform translate-y-0"
                x-transition:leave-end="opacity-0 transform -translate-y-2"
                class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow">
                <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-800">
                        <tr>
                            <th class="w-[12%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                            <th class="w-[20%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                            <th class="w-[8%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                            <th class="w-[10%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
                            <th class="w-[12%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty</th>
                            <th class="w-[10%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Intake</th>
                            <th class="w-[8%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($rules as $rule)
                            <tr
                                x-show="selectedIntake === '{{ $rule->intake }}'"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0"
                                x-transition:enter-end="opacity-100"
                                class="hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                    {{ $rule->course->code }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                    {{ $rule->course->name }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 text-center">
                                    {{ $rule->course->credit_hour }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                    {{ $rule->course->section }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                    {{ $rule->course->faculty }}
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                    {{ $rule->intake }}
                                </td>
                                <td class="px-4 py-4 text-sm font-medium">
                                    <button onclick="deleteRule({{ $rule->id }})"
                                            class="inline-flex items-center justify-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                        <x-flux::icon name="trash" class="size-5" />
                                        <span class="sr-only">Delete</span>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                    No courses found for the selected intake
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section Summary Tables -->
        <div class="space-y-6">
            @php
                $allSections = [
                    'Faculty Core',
                    'Programme Core',
                    'Elective',
                    'University Core',
                    'Co-curriculum',
                    'Language',
                    'Industrial Training'
                ];
            @endphp

            @foreach($intakes as $intake)
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4">
                    <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">
                        Section Summary - {{ $intake }}
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                            <thead class="bg-gray-50 dark:bg-zinc-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                                @foreach($allSections as $section)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                            {{ $section }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rules->where('intake', $intake)
                                                    ->where('course.section', $section)
                                                    ->sum('course.credit_hour') }}
                                        </td>
                                    </tr>
                                @endforeach
                                <!-- Total Row -->
                                <tr class="bg-gray-50 dark:bg-zinc-800">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        Total
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                        {{ $rules->where('intake', $intake)->sum('course.credit_hour') }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add this hidden form for delete operation -->
        <form id="deleteRuleForm" class="hidden" method="POST">
            @csrf
            @method('DELETE')
        </form>
    </div>

    <script>
        function deleteRule(ruleId) {
            if (confirm('Are you sure you want to delete this course rule?')) {
                const form = document.getElementById('deleteRuleForm');
                form.action = `/admin/buildcoursestruct/${ruleId}`;
                form.submit();
            }
        }

        document.getElementById('select-all').addEventListener('change', function() {
            document.querySelectorAll('.course-checkbox').forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        function exportCourseStructure(selectedIntakes) {
            window.location.href = `/admin/course-structure/export-batch?intakes=${selectedIntakes.join(',')}`;
        }
    </script>
</x-layouts.app>




















