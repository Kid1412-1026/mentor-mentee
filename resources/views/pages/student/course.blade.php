<x-layouts.app :title="__('Available Courses')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Enrolled Courses Section -->
        <div
            class="bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-lg p-6 dark:bg-zinc-900 dark:shadow-zinc-800/30">
            <h2 class="text-2xl font-bold text-[#1b1b18] dark:text-white mb-4">My Enrolled Courses</h2>

            @if ($enrolledCourses->isEmpty())
                <div class="flex justify-center items-center min-h-[100px]">
                    <div class="text-center">
                        <p class="text-lg text-[#706f6c] dark:text-zinc-400">You haven't enrolled in any courses yet.</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Course Code</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Course Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Credit Hours</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Semester/Year</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Grade</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Pointer</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Rating</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($enrolledCourses as $enrollment)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $enrollment->course->code }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        {{ $enrollment->course->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $enrollment->course->credit_hour }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        Semester {{ $enrollment->sem }} / Year {{ $enrollment->year }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $enrollment->grade }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ number_format($enrollment->pointer, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                        {{ $enrollment->rating ?? 'Not rated' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <button onclick="openEditEnrollmentModal('{{ $enrollment->id }}', '{{ $enrollment->sem }}', '{{ $enrollment->year }}', '{{ $enrollment->grade }}', '{{ $enrollment->pointer }}', '{{ $enrollment->rating }}')"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                            <x-flux::icon name="pencil" class="size-5" />
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>

        <!-- Available Courses Section -->
        <div
            class="bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-lg p-6 dark:bg-zinc-900 dark:shadow-zinc-800/30">
            <h1 class="text-4xl font-bold text-[#1b1b18] dark:text-white mb-4 text-center">Available Courses</h1>

            @php
                $enrolledCourseIds = $enrolledCourses->pluck('course_id')->toArray();
            @endphp

            @if ($courses->isEmpty())
                <div class="flex justify-center items-center min-h-[200px]">
                    <div class="text-center">
                        <x-placeholder-pattern class="w-48 h-48 mx-auto mb-4 text-zinc-300 dark:text-zinc-600" />
                        <p class="text-xl text-[#706f6c] dark:text-zinc-400">No courses available at the moment.</p>
                    </div>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-800">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Faculty</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Course Code</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Course Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Credit Hours</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Section</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Select</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($courses as $rule)
                                @if (!in_array($rule->course->id, $enrolledCourseIds))
                                    <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rule->course->code }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rule->course->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rule->course->credit_hour }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rule->course->section }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                            {{ $rule->course->faculty }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            <input type="checkbox"
                                                class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                                onclick="openEnrollModal('{{ $rule->course->id }}', '{{ $rule->course->code }}', '{{ $rule->course->name }}')">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Enroll Modal -->
    <div id="enrollModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Enroll in Course</h3>
                <p id="selectedCourse" class="text-sm text-gray-600 dark:text-gray-400 mb-4"></p>
                <form id="enrollForm" action="{{ route('student.enroll') }}" method="POST">
                    @csrf
                    <input type="hidden" id="course_id" name="course_id">

                    <div class="mb-4">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                        <select name="semester_year" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                            <option value="">Select Semester / Year</option>
                            <option value="1/1">Semester 1 / Year 1</option>
                            <option value="2/1">Semester 2 / Year 1</option>
                            <option value="1/2">Semester 1 / Year 2</option>
                            <option value="2/2">Semester 2 / Year 2</option>
                            <option value="1/3">Semester 1 / Year 3</option>
                            <option value="2/3">Semester 2 / Year 3</option>
                            <option value="1/4">Semester 1 / Year 4</option>
                            <option value="2/4">Semester 2 / Year 4</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grade /
                            Pointer</label>
                        <select name="grade_pointer" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                            <option value="">Select Grade</option>
                            <option value="A/4.00">A / 4.00</option>
                            <option value="A-/3.67">A- / 3.67</option>
                            <option value="B+/3.33">B+ / 3.33</option>
                            <option value="B/3.00">B / 3.00</option>
                            <option value="B-/2.67">B- / 2.67</option>
                            <option value="C+/2.33">C+ / 2.33</option>
                            <option value="C/2.00">C / 2.00</option>
                            <option value="C-/1.67">C- / 1.67</option>
                            <option value="D+/1.33">D+ / 1.33</option>
                            <option value="D/1.00">D / 1.00</option>
                            <option value="E/0.00">E / 0.00</option>
                            <option value="F/0.00">F / 0.00</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating
                            (1-5)</label>
                        <input type="number" name="rating" required min="1" max="5"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800"
                            value="1">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEnrollModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Enroll
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Enrollment Modal -->
    <div id="editEnrollmentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Enrollment</h3>
                <form id="editEnrollmentForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                        <select name="semester_year" id="edit_semester_year" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                            <option value="">Select Semester / Year</option>
                            <option value="1/1">Semester 1 / Year 1</option>
                            <option value="2/1">Semester 2 / Year 1</option>
                            <option value="1/2">Semester 1 / Year 2</option>
                            <option value="2/2">Semester 2 / Year 2</option>
                            <option value="1/3">Semester 1 / Year 3</option>
                            <option value="2/3">Semester 2 / Year 3</option>
                            <option value="1/4">Semester 1 / Year 4</option>
                            <option value="2/4">Semester 2 / Year 4</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Grade / Pointer</label>
                        <select name="grade_pointer" id="edit_grade_pointer" required
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                            <option value="">Select Grade</option>
                            <option value="A/4.00">A / 4.00</option>
                            <option value="A-/3.67">A- / 3.67</option>
                            <option value="B+/3.33">B+ / 3.33</option>
                            <option value="B/3.00">B / 3.00</option>
                            <option value="B-/2.67">B- / 2.67</option>
                            <option value="C+/2.33">C+ / 2.33</option>
                            <option value="C/2.00">C / 2.00</option>
                            <option value="C-/1.67">C- / 1.67</option>
                            <option value="D+/1.33">D+ / 1.33</option>
                            <option value="D/1.00">D / 1.00</option>
                            <option value="E/0.00">E / 0.00</option>
                            <option value="F/0.00">F / 0.00</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating (1-5)</label>
                        <input type="number" name="rating" id="edit_rating" required min="1" max="5"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeEditEnrollmentModal()"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openEnrollModal(courseId, courseCode, courseName) {
            document.getElementById('course_id').value = courseId;
            document.getElementById('selectedCourse').textContent = `${courseCode} - ${courseName}`;
            document.getElementById('enrollModal').classList.remove('hidden');
        }

        function closeEnrollModal() {
            document.getElementById('enrollModal').classList.add('hidden');
            document.getElementById('enrollForm').reset();
        }

        function openEditEnrollmentModal(id, sem, year, grade, pointer, rating) {
            const form = document.getElementById('editEnrollmentForm');
            form.action = `/student-course/${id}`;

            document.getElementById('edit_semester_year').value = `${sem}/${year}`;
            document.getElementById('edit_grade_pointer').value = `${grade}/${pointer}`;
            document.getElementById('edit_rating').value = rating;

            document.getElementById('editEnrollmentModal').classList.remove('hidden');
        }

        function closeEditEnrollmentModal() {
            document.getElementById('editEnrollmentModal').classList.add('hidden');
            document.getElementById('editEnrollmentForm').reset();
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                closeEnrollModal();
            }
        }
    </script>
</x-layouts.app>

