<x-layouts.app :title="__('Student Activities')">
    @if(session('show-notification'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Alpine.store('notifications.{{ session('show-notification') }}', true);
            });
        </script>
    @endif

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
         x-data="{
            isAddingActivity: false,
            filterSemYear: '',
            filterType: '',
            sortColumn: null,
            sortDirection: 'asc',
            filterRows() {
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    // Skip the 'No activities found' row
                    if (row.classList.contains('empty-row')) {
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
                        const match = semYearCell.match(/(\d+)\/(\d+)/);

                        if (match) {
                            const [, rowSem, rowYear] = match;
                            if (rowSem !== filterSem || rowYear !== filterYear) {
                                showRow = false;
                            }
                        } else {
                            showRow = false;
                        }
                    }

                    // Filter by type
                    if (this.filterType && type !== this.filterType) {
                        showRow = false;
                    }

                    // Show or hide the row
                    row.style.display = showRow ? '' : 'none';
                });
            },
            sort(column) {
                if (this.sortColumn === column) {
                    this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
                } else {
                    this.sortColumn = column;
                    this.sortDirection = 'asc';
                }

                const tbody = document.querySelector('tbody');
                const rows = Array.from(tbody.querySelectorAll('tr:not(.empty-row)'));

                rows.sort((a, b) => {
                    let aValue, bValue;

                    switch(column) {
                        case 'name':
                            aValue = a.cells[0].textContent.trim();
                            bValue = b.cells[0].textContent.trim();
                            break;
                        case 'type':
                            aValue = a.cells[1].textContent.trim();
                            bValue = b.cells[1].textContent.trim();
                            break;
                        case 'semYear':
                            const [aSem, aYear] = a.cells[2].textContent.trim().split('/');
                            const [bSem, bYear] = b.cells[2].textContent.trim().split('/');
                            aValue = (parseInt(aYear) * 2) + parseInt(aSem);
                            bValue = (parseInt(bYear) * 2) + parseInt(bSem);
                            break;
                        case 'remark':
                            aValue = a.cells[3].textContent.trim();
                            bValue = b.cells[3].textContent.trim();
                            break;
                    }

                    // Handle null/undefined values
                    if (aValue === null || aValue === undefined) aValue = '';
                    if (bValue === null || bValue === undefined) bValue = '';

                    // Compare values
                    if (this.sortDirection === 'asc') {
                        return aValue > bValue ? 1 : aValue < bValue ? -1 : 0;
                    }
                    return aValue < bValue ? 1 : aValue > bValue ? -1 : 0;
                });

                // Reorder the rows
                rows.forEach(row => tbody.appendChild(row));
            }
         }"
         x-init="$nextTick(() => filterRows())">
        <!-- Activity Overview Section -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <div class="p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Activity Overview</h2>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    <!-- Chart Section -->
                    <div class="h-[400px]">
                        <canvas id="activityDistributionChart"></canvas>
                    </div>

                    <!-- Summary Section -->
                    <div class="grid grid-cols-1 gap-4">
                        <!-- Activities Section -->
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Activities</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'Faculty Activity')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'University Activity')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'National Activity')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'International Activity')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Competitions Section -->
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Competitions</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Faculty Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'Faculty Competition')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">University Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'University Competition')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">National Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'National Competition')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">International Level</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'International Competition')->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Other Activities Section -->
                        <div class="bg-gray-50 dark:bg-zinc-800 p-4 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-3">Other Activities</h3>
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Leadership Program</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'Leadership Program')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Professional Certification</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'Professional Certification')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600 dark:text-gray-400">Mobility Program</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->where('type', 'Mobility Program')->count() }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center border-t dark:border-zinc-700 mt-3 pt-3">
                                    <span class="text-gray-600 dark:text-gray-400 font-medium">Total Activities</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">
                                        {{ $activities->count() }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

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
                <!-- Add this line for the notification -->
                <x-action-message on="activity-added">
                    Activities added successfully!
                </x-action-message>

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
                     x-data="{ activities: [{}] }"
                     class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Activities</h3>
                    <form action="{{ route('student.activity.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <template x-for="(activity, index) in activities" :key="index">
                            <div class="mb-6 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                <div class="flex justify-end mb-2">
                                    <button type="button" @click="activities = activities.filter((_, i) => i !== index)"
                                            x-show="activities.length > 1"
                                            class="text-red-600 hover:text-red-800">
                                        <x-flux::icon name="trash" class="size-5" />
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                                        <select :name="'activities['+index+'][sem_year]'" required
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
                                        <select :name="'activities['+index+'][type]'" required
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
                                        <input type="text" :name="'activities['+index+'][name]'" required
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remark</label>
                                        <textarea :name="'activities['+index+'][remark]'" rows="3"
                                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Upload</label>
                                        <input type="file" :name="'activities['+index+'][uploads]'"
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <div class="flex items-center justify-between mt-4">
                            <button type="button" @click="activities.push({})"
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 rounded-md hover:bg-indigo-50">
                                <x-flux::icon name="plus" class="size-5 mr-2" />
                                Add Another Activity
                            </button>

                            <div class="flex justify-end gap-3">
                                <button type="button" @click="isAddingActivity = false"
                                        class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                    Save Activities
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <table class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th @click="sort('name')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-zinc-700">
                            <div class="flex items-center">
                                Name
                                <template x-if="sortColumn === 'name'">
                                    <span x-text="sortDirection === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                                </template>
                            </div>
                        </th>
                        <th @click="sort('type')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-zinc-700">
                            <div class="flex items-center">
                                Type
                                <template x-if="sortColumn === 'type'">
                                    <span x-text="sortDirection === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                                </template>
                            </div>
                        </th>
                        <th @click="sort('semYear')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-zinc-700">
                            <div class="flex items-center">
                                Semester/Year
                                <template x-if="sortColumn === 'semYear'">
                                    <span x-text="sortDirection === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                                </template>
                            </div>
                        </th>
                        <th @click="sort('remark')"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider cursor-pointer hover:bg-gray-100 dark:hover:bg-zinc-700">
                            <div class="flex items-center">
                                Remark
                                <template x-if="sortColumn === 'remark'">
                                    <span x-text="sortDirection === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                                </template>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Uploads</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($activities as $activity)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $activity->name }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $activity->type }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                {{ $activity->sem }}/{{ $activity->year }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ Str::limit($activity->remark, 100) }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                @if ($activity->uploads)
                                    @php
                                        $fileExtension = pathinfo($activity->uploads, PATHINFO_EXTENSION);
                                    @endphp

                                    @if (in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                        <img src="{{ asset('storage/' . $activity->uploads) }}"
                                             alt="Activity Upload"
                                             class="w-24 h-auto rounded-lg">
                                    @elseif (strtolower($fileExtension) === 'pdf')
                                        <a href="{{ asset('storage/' . $activity->uploads) }}"
                                           class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline"
                                           target="_blank">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            View PDF
                                        </a>
                                    @else
                                        <a href="{{ asset('storage/' . $activity->uploads) }}"
                                           class="text-indigo-600 dark:text-indigo-400 hover:underline"
                                           target="_blank">
                                            View Upload
                                        </a>
                                    @endif
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

    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Check if activityChart already exists before declaring
    if (typeof window.activityChart === 'undefined') {
        window.activityChart = null;
    }

    function initActivityChart() {
        const ctx = document.getElementById('activityDistributionChart');
        if (!ctx) return;

        // Destroy existing chart if it exists
        if (window.activityChart instanceof Chart) {
            window.activityChart.destroy();
        }

        const activityData = @json($activities->groupBy('type')->map->count());

        window.activityChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: Object.keys(activityData),
                datasets: [{
                    data: Object.values(activityData),
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
                layout: {
                    padding: {
                        bottom: 100 // Add padding at the bottom for the legend
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#6B7280',
                            font: {
                                size: 11
                            },
                            padding: 20,
                            boxWidth: 12,
                            usePointStyle: true, // Makes legend items circular instead of rectangular
                            pointStyle: 'circle'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(17, 24, 39, 0.8)',
                        padding: 10,
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.raw || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Initialize chart when DOM is loaded
    document.addEventListener('DOMContentLoaded', initActivityChart);

    // Re-initialize chart when navigating with Livewire
    document.addEventListener('livewire:navigated', initActivityChart);

    // Initialize when navigating with Turbo
    document.addEventListener('turbo:load', initActivityChart);

    // Clean up chart when navigating away
    document.addEventListener('livewire:navigating', () => {
        if (window.activityChart instanceof Chart) {
            window.activityChart.destroy();
        }
    });
    document.addEventListener('turbo:before-visit', () => {
        if (window.activityChart instanceof Chart) {
            window.activityChart.destroy();
        }
    });
</script>
@endpush

</x-layouts.app>




















