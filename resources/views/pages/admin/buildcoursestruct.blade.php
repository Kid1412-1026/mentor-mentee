<x-layouts.app :title="__('Build Course Structure')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-4">
        <!-- Header Section -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-4">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200">Course Structure</h2>
            <p class="text-gray-600 dark:text-gray-400">View and manage course structures by programme and intake</p>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-zinc-900 rounded-lg shadow p-4 mb-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="programme" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Programme</label>
                    <select id="programme" name="programme" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Programme</option>
                        @foreach($programmes as $programme)
                            <option value="{{ $programme->id }}">{{ $programme->code }} - {{ $programme->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="intake" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Intake</label>
                    <select id="intake" name="intake" class="mt-1 block w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Select Intake</option>
                        @foreach($intakes as $intake)
                            <option value="{{ $intake }}">{{ $intake }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Add Course Button -->
            <div class="mt-4 flex justify-end">
                <button onclick="openAddCourseModal()"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <x-flux::icon name="plus" class="size-5 mr-2" />
                    Add Course to Structure
                </button>
            </div>
        </div>

        <!-- Course Structure Table -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 rounded-lg shadow">
            <table class="min-w-full table-fixed divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="w-[12%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Code</th>
                        <th class="w-[20%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Course Name</th>
                        <th class="w-[20%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Programme</th>
                        <th class="w-[8%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hours</th>
                        <th class="w-[10%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
                        <th class="w-[12%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty</th>
                        <th class="w-[10%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Intake</th>
                        <th class="w-[8%] px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($rules as $rule)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50">
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                {{ $rule->course->code }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                {{ $rule->course->name }}
                            </td>
                            <td class="px-4 py-4 text-sm text-gray-900 dark:text-gray-300 truncate">
                                {{ $rule->programme->code }} - {{ $rule->programme->name }}
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
                                No courses found for the selected programme and intake
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Add this hidden form for delete operation -->
        <form id="deleteRuleForm" class="hidden" method="POST">
            @csrf
            @method('DELETE')
        </form>

        <!-- Add Course to Structure Modal -->
        <div id="addCourseStructModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Course to Structure</h3>
                    <form action="{{ route('admin.buildcoursestruct.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Programme</label>
                            <select name="programme_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300">
                                <option value="">Select Programme</option>
                                @foreach($programmes as $programme)
                                    <option value="{{ $programme->id }}">{{ $programme->code }} - {{ $programme->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course</label>
                            <select name="course_id" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300">
                                <option value="">Select Course</option>
                                @foreach($courses as $course)
                                    <option value="{{ $course->id }}">{{ $course->code }} - {{ $course->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Intake</label>
                            <select name="intake" required
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300">
                                <option value="">Select Intake</option>
                                @foreach($intakes as $intake)
                                    <option value="{{ $intake }}">{{ $intake }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" onclick="closeModal('addCourseStructModal')"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Add Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openAddCourseModal() {
            document.getElementById('addCourseStructModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function deleteRule(ruleId) {
            if (confirm('Are you sure you want to delete this course rule?')) {
                const form = document.getElementById('deleteRuleForm');
                form.action = `/admin/buildcoursestruct/${ruleId}`;
                form.submit();
            }
        }
    </script>
</x-layouts.app>





