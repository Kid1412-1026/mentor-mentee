
<x-layouts.app :title="__('Manage Course')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
         x-data="courseFilter()">
        <!-- Filter Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 mb-4">
            <div class="flex items-center gap-4">
                <div class="flex-1 flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Section:</label>
                    <select x-model="section"
                            class="rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800">
                        <option value="">All Sections</option>
                        <option value="Co-curriculum">Co-curriculum</option>
                        <option value="Faculty Core">Faculty Core</option>
                        <option value="Programme Core">Programme Core</option>
                        <option value="Industrial Training">Industrial Training</option>
                        <option value="Language">Language</option>
                        <option value="Elective">Elective</option>
                        <option value="University Core">University Core</option>
                    </select>
                </div>

                <div class="flex-1 flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Faculty:</label>
                    <select x-model="faculty"
                            class="rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800">
                        <option value="">All Faculties</option>
                        @foreach($courses->pluck('faculty')->unique() as $faculty)
                            <option value="{{ $faculty }}">{{ $faculty }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Button Container -->
                <div class="flex items-center gap-2">
                    <!-- Add Course Button -->
                    <button onclick="openModal()" class="inline-flex items-center justify-center p-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <x-flux::icon name="plus" class="size-5" />
                        <span class="sr-only">Add Course</span>
                    </button>

                    <!-- Build Course Structure Button -->
                    <a href="{{ route('admin.buildcoursestruct') }}" class="inline-flex items-center justify-center p-2 bg-emerald-600 dark:bg-emerald-500 text-white rounded-md hover:bg-emerald-700 dark:hover:bg-emerald-600 transition-colors">
                        <x-flux::icon name="squares-2x2" class="size-5" />
                        <span class="sr-only">Build Course Structure</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <table class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Credit Hour</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Section</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Faculty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($courses as $course)
                        <tr x-show="isVisible('{{ $course->section }}', '{{ $course->faculty }}')"
                            class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $course->code }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $course->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $course->credit_hour }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-900 dark:text-gray-300">{{ $course->section }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $course->faculty }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editCourse({{ $course->id }})"
                                        class="inline-flex items-center justify-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3 transition-colors">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                                <button onclick="deleteCourse({{ $course->id }})"
                                        class="inline-flex items-center justify-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                    <x-flux::icon name="trash" class="size-5" />
                                    <span class="sr-only">Delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No courses found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $courses->links() }}
            </div>
        </div>
    </div>

    <!-- Add Course Modal -->
    <div id="addCourseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Course</h3>
                <form action="{{ route('admin.course.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Code</label>
                        <input type="text" name="code" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Name</label>
                        <input type="text" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Credit Hour</label>
                        <input type="number" name="credit_hour" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section</label>
                        <select id="section" name="section" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300 dark:[&>option]:bg-zinc-900">
                            <option value="" disabled selected>Select a section</option>
                            <option value="Co-curriculum">Co-curriculum</option>
                            <option value="Faculty Core">Faculty Core</option>
                            <option value="Programme Core">Programme Core</option>
                            <option value="Industrial Training">Industrial Training</option>
                            <option value="Language">Language</option>
                            <option value="Elective">Elective</option>
                            <option value="University Core">University Core</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="faculty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty</label>
                        <select id="faculty" name="faculty" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300 dark:[&>option]:bg-zinc-900">
                            <option value="" disabled selected>Select a faculty</option>
                            <option value="Faculty of Computing and Informatics(FKI)">Faculty of Computing and Informatics(FKI)</option>
                            <option value="Knowledge and Language Learning(PPIB)">Knowledge and Language Learning(PPIB)</option>
                            <option value="Co-Curricular(PKPP)">Co-Curricular(PKPP)</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('addCourseModal')"
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

    <!-- Edit Course Modal -->
    <div id="editCourseModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Course</h3>
                <form id="editCourseForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_course_id" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Code</label>
                        <input type="text" id="edit_code" name="code" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Course Name</label>
                        <input type="text" id="edit_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Credit Hour</label>
                        <input type="number" id="edit_credit_hour" name="credit_hour" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Section</label>
                        <select id="edit_section" name="section" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300 dark:[&>option]:bg-zinc-900">
                            <option value="Co-curriculum">Co-curriculum</option>
                            <option value="Faculty Core">Faculty Core</option>
                            <option value="Programme Core">Programme Core</option>
                            <option value="Industrial Training">Industrial Training</option>
                            <option value="Language">Language</option>
                            <option value="Elective">Elective</option>
                            <option value="University Core">University Core</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty</label>
                        <select id="edit_faculty" name="faculty" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white text-gray-900 dark:bg-zinc-900 dark:text-gray-300 dark:[&>option]:bg-zinc-900">
                            <option value="Faculty of Computing and Informatics">Faculty of Computing and Informatics</option>
                            <option value="Knowledge and Language Learning (PPIB)">Knowledge and Language Learning (PPIB)</option>
                            <option value="Co-Curricular (PKPP)">Co-Curricular (PKPP)</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editCourseModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update Course
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteCourseForm" class="hidden" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openModal() {
            document.getElementById('addCourseModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function editCourse(id) {
            fetch(`/admin-course/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                document.getElementById('edit_course_id').value = data.id;
                document.getElementById('edit_code').value = data.code;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_credit_hour').value = data.credit_hour;
                document.getElementById('edit_section').value = data.section;
                document.getElementById('edit_faculty').value = data.faculty;
                document.getElementById('editCourseForm').action = `/admin-course/${id}`;
                document.getElementById('editCourseModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching course data');
            });
        }

        function deleteCourse(id) {
            if (confirm('Are you sure you want to delete this course?')) {
                const form = document.getElementById('deleteCourseForm');
                form.action = `/admin-course/${id}`;

                fetch(form.action, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': form._token.value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Error deleting course');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting course');
                });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        }

        function courseFilter() {
            return {
                section: '',
                faculty: '',
                isVisible(courseSection, courseFaculty) {
                    if (!this.section && !this.faculty) {
                        return true;
                    }

                    if (this.section && this.faculty) {
                        return courseSection === this.section && courseFaculty === this.faculty;
                    }

                    if (this.section) {
                        return courseSection === this.section;
                    }

                    if (this.faculty) {
                        return courseFaculty === this.faculty;
                    }

                    return true;
                }
            }
        }
    </script>
</x-layouts.app>



























