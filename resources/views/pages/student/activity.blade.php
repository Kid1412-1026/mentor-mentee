<x-layouts.app :title="__('Activities')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
         x-data="{
            filterSemYear: '',
            filterType: '',
            filterRows() {
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    // Skip the 'No activities found' row
                    if (row.cells.length <= 1) {
                        return;
                    }

                    // Default to showing the row if no filters are active
                    if (!this.filterSemYear && !this.filterType) {
                        row.style.display = '';
                        return;
                    }

                    const semYearCell = row.cells[2].textContent.trim();
                    const type = row.cells[1].textContent.trim();

                    let showRow = true;

                    // Filter by semester/year
                    if (this.filterSemYear) {
                        const [filterSem, filterYear] = this.filterSemYear.split('/');
                        const [rowSem, rowYear] = semYearCell.split('/');

                        // Compare both semester and year
                        if (rowSem !== filterSem || rowYear !== filterYear) {
                            showRow = false;
                        }
                    }

                    // Filter by type
                    if (this.filterType && type !== this.filterType) {
                        showRow = false;
                    }

                    row.style.display = showRow ? '' : 'none';
                });
            }
         }"
         x-init="
            $nextTick(() => {
                filterRows();
                console.log('Initial filter applied'); // Debug log
            });
            $watch('filterSemYear', () => filterRows());
            $watch('filterType', () => filterRows());
         ">

        <!-- Filter Section -->
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow p-4 mb-4">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                    <select x-model="filterSemYear"
                            @change="filterRows()"
                            class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800">
                        <option value="">All Semesters</option>
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
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Activity Type</label>
                    <select x-model="filterType"
                            @change="filterRows()"
                            class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800">
                        <option value="">All Types</option>
                        <option value="Faculty Activity">Faculty Activity</option>
                        <option value="University Activity">University Activity</option>
                        <option value="National Activity">National Activity</option>
                        <option value="International Activity">International Activity</option>
                        <option value="Faculty Competition">Faculty Competition</option>
                        <option value="University Competition">University Competition</option>
                        <option value="National Competition">National Competition</option>
                        <option value="International Competition">International Competition</option>
                        <option value="Leadership Program">Leadership Program</option>
                        <option value="Professional Certification">Professional Certification</option>
                        <option value="Mobility Program">Mobility Program</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <!-- Add Activity Button -->
            <div class="p-6"
                 x-data="{ isAddingActivity: false }">
                <!-- Toggle Button -->
                <div class="flex justify-end mb-4">
                    <button @click="isAddingActivity = !isAddingActivity"
                            class="inline-flex items-center justify-center p-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <x-flux::icon name="plus" class="size-5" />
                        <span class="sr-only">Add Activity</span>
                    </button>
                </div>

                <!-- Expandable Form Section -->
                <div x-show="isAddingActivity"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Activity</h3>
                    <form action="{{ route('student.activity.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                                <select name="sem_year" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                               focus:outline-none focus:ring-1 focus:ring-indigo-500
                                               bg-white dark:bg-zinc-800
                                               text-gray-900 dark:text-white">
                                    <option value="" disabled selected>Select Semester / Year</option>
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                                <select name="type" required
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                               focus:outline-none focus:ring-1 focus:ring-indigo-500
                                               bg-white dark:bg-zinc-800
                                               text-gray-900 dark:text-white">
                                    <option value="" disabled selected>Select Activity Type</option>
                                    <option value="Faculty Activity">Faculty Activity</option>
                                    <option value="University Activity">University Activity</option>
                                    <option value="National Activity">National Activity</option>
                                    <option value="International Activity">International Activity</option>
                                    <option value="Faculty Competition">Faculty Competition</option>
                                    <option value="University Competition">University Competition</option>
                                    <option value="National Competition">National Competition</option>
                                    <option value="International Competition">International Competition</option>
                                    <option value="Leadership Program">Leadership Program</option>
                                    <option value="Professional Certification">Professional Certification</option>
                                    <option value="Mobility Program">Mobility Program</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                                <input type="text" name="name" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remark</label>
                                <textarea name="remark" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload</label>
                                <input type="file" name="uploads"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" @click="isAddingActivity = false"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Add Activity
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Semester/Year</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Remark</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uploads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $activity->name }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $activity->type }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $activity->sem }}/{{ $activity->year }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ Str::limit($activity->remark, 100) }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                @if ($activity->uploads)
                                    <a href="{{ asset('storage/' . $activity->uploads) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline"
                                       target="_blank">
                                        View Upload
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No upload</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editActivity({{ $activity->id }})"
                                        class="inline-flex items-center justify-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3 transition-colors">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                                <button onclick="deleteActivity({{ $activity->id }})"
                                        class="inline-flex items-center justify-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                    <x-flux::icon name="trash" class="size-5" />
                                    <span class="sr-only">Delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No activities found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $activities->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Activity Modal -->
    <div id="editActivityModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Activity</h3>
                <form id="editActivityForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_activity_id" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                        <select id="edit_sem_year" name="sem_year" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                       focus:outline-none focus:ring-1 focus:ring-indigo-500
                                       bg-white dark:bg-zinc-800
                                       text-gray-900 dark:text-white">
                            <option value="" disabled>Select Semester / Year</option>
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                        <input type="text" id="edit_name" name="name" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <select id="edit_type" name="type" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                       focus:outline-none focus:ring-1 focus:ring-indigo-500
                                       bg-white dark:bg-zinc-800
                                       text-gray-900 dark:text-white">
                            <option value="" disabled>Select Activity Type</option>
                            <option value="Faculty Activity">Faculty Activity</option>
                            <option value="University Activity">University Activity</option>
                            <option value="National Activity">National Activity</option>
                            <option value="International Activity">International Activity</option>
                            <option value="Faculty Competition">Faculty Competition</option>
                            <option value="University Competition">University Competition</option>
                            <option value="National Competition">National Competition</option>
                            <option value="International Competition">International Competition</option>
                            <option value="Leadership Program">Leadership Program</option>
                            <option value="Professional Certification">Professional Certification</option>
                            <option value="Mobility Program">Mobility Program</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remark</label>
                        <textarea id="edit_remark" name="remark" rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload</label>
                        <input type="file" name="uploads"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editActivityModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update Activity
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <form id="deleteActivityForm" class="hidden" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function editActivity(id) {
            fetch(`/student-activity/${id}/edit`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
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
                document.getElementById('edit_activity_id').value = data.id;
                document.getElementById('edit_sem_year').value = `${data.sem}/${data.year}`;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_type').value = data.type;
                document.getElementById('edit_remark').value = data.remark || '';
                document.getElementById('editActivityForm').action = `/student-activity/${data.id}`;
                document.getElementById('editActivityModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching activity data');
            });
        }

        function deleteActivity(id) {
            if (confirm('Are you sure you want to delete this activity?')) {
                const form = document.getElementById('deleteActivityForm');
                form.action = `/student-activity/${id}`;

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
                        alert('Error deleting activity');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting activity');
                });
            }
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>





























