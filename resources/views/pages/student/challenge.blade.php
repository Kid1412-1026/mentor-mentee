<x-layouts.app :title="__('Challenges')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
         x-data="{
            isAddingChallenge: false,
            filterSemYear: '',
            filterType: '',
            sortColumn: null,
            sortDirection: 'asc',
            filterRows() {
                const rows = document.querySelectorAll('tbody tr');

                rows.forEach(row => {
                    // Skip the 'No challenges found' row
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
                        const match = semYearCell.match(/Semester (\d+) \/ Year (\d+)/);

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
                            // Get semester and year values directly from the cell
                            const [aSem, aYear] = a.cells[2].textContent.trim().split('/');
                            const [bSem, bYear] = b.cells[2].textContent.trim().split('/');
                            // Convert to comparable numbers
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
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Challenge Type</label>
                    <select x-model="filterType"
                            @change="filterRows()"
                            class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800">
                        <option value="">All Types</option>
                        <option value="Time Management">Time Management</option>
                        <option value="Financial Constraints">Financial Constraints</option>
                        <option value="Academic Pressure">Academic Pressure</option>
                        <option value="Mental Health Struggles">Mental Health Struggles</option>
                        <option value="Lack of Motivation">Lack of Motivation</option>
                        <option value="Balancing Work and Study">Balancing Work and Study</option>
                        <option value="Social or Peer Pressure">Social or Peer Pressure</option>
                        <option value="Health Issues">Health Issues</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <!-- Add Challenge Button -->
            <div class="p-6" x-data="{
                isAddingChallenge: false,
                challenges: [],
                initializeForm() {
                    this.isAddingChallenge = true;
                    this.challenges = [{}];  // Initialize with one empty challenge when opening the form
                }
            }">
                <!-- Toggle Button -->
                <div class="flex justify-end mb-4">
                    <button @click="initializeForm()"
                            class="inline-flex items-center justify-center p-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <x-flux::icon name="plus" class="size-5" />
                        <span class="sr-only">Add Challenge</span>
                    </button>
                </div>

                <!-- Expandable Form Section -->
                <div x-show="isAddingChallenge"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Challenges</h3>
                    <form action="{{ route('student.challenge.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <template x-for="(challenge, index) in challenges" :key="index">
                                <div class="space-y-4 p-4 border border-gray-200 dark:border-zinc-700 rounded-lg">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester/Year</label>
                                        <select :name="'challenges[' + index + '][sem_year]'" required
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
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
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                        <input type="text" :name="'challenges[' + index + '][name]'" required
                                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                        <select :name="'challenges[' + index + '][type]'" required
                                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                                            <option value="" disabled selected>Select Category</option>
                                            <option value="Time Management">Time Management</option>
                                            <option value="Financial Constraints">Financial Constraints</option>
                                            <option value="Academic Pressure">Academic Pressure</option>
                                            <option value="Mental Health Struggles">Mental Health Struggles</option>
                                            <option value="Lack of Motivation">Lack of Motivation</option>
                                            <option value="Balancing Work and Study">Balancing Work and Study</option>
                                            <option value="Social or Peer Pressure">Social or Peer Pressure</option>
                                            <option value="Health Issues">Health Issues</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                        <textarea :name="'challenges[' + index + '][remark]'" required rows="4"
                                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white"></textarea>
                                    </div>

                                    <!-- Remove Challenge Button -->
                                    <div x-show="challenges.length > 1" class="flex justify-end">
                                        <button type="button" @click="challenges.splice(index, 1)"
                                                class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <x-flux::icon name="trash" class="size-5" />
                                            <span class="sr-only">Remove Challenge</span>
                                        </button>
                                    </div>
                                </div>
                            </template>

                            <div class="flex items-center justify-between mt-4">
                                <button type="button" @click="challenges.push({})"
                                        class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-600 bg-white border border-indigo-600 rounded-md hover:bg-indigo-50">
                                    <x-flux::icon name="plus" class="size-5 mr-2" />
                                    Add Another Challenge
                                </button>

                                <div class="flex justify-end gap-3">
                                    <button type="button" @click="isAddingChallenge = false; challenges = []"
                                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                        Save Challenges
                                    </button>
                                </div>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($challenges as $challenge)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors"
                            data-sem-year="Semester {{ $challenge->sem }} / Year {{ $challenge->year }}"
                            data-type="{{ $challenge->type }}">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $challenge->name }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $challenge->type }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                {{ $challenge->sem }}/{{ $challenge->year }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ Str::limit($challenge->remark, 100) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editChallenge({{ $challenge->id }})"
                                        class="inline-flex items-center justify-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3 transition-colors">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                                <button onclick="deleteChallenge({{ $challenge->id }})"
                                        class="inline-flex items-center justify-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                    <x-flux::icon name="trash" class="size-5" />
                                    <span class="sr-only">Delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No challenges found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Challenge Modal -->
    <div id="editChallengeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Challenge</h3>
                <form id="editChallengeForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_challenge_id" name="id">
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
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                      focus:outline-none focus:ring-1 focus:ring-indigo-500
                                      bg-white dark:bg-zinc-800
                                      text-gray-900 dark:text-white">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                        <select id="edit_type" name="type" required
                                class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                       focus:outline-none focus:ring-1 focus:ring-indigo-500
                                       bg-white dark:bg-zinc-800
                                       text-gray-900 dark:text-white">
                            <option value="" disabled>Select Challenge</option>
                            <option value="Time Management">Time Management</option>
                            <option value="Financial Constraints">Financial Constraints</option>
                            <option value="Academic Pressure">Academic Pressure</option>
                            <option value="Mental Health Struggles">Mental Health Struggles</option>
                            <option value="Lack of Motivation">Lack of Motivation</option>
                            <option value="Balancing Work and Study">Balancing Work and Study</option>
                            <option value="Social or Peer Pressure">Social or Peer Pressure</option>
                            <option value="Health Issues">Health Issues</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remark</label>
                        <textarea id="edit_remark" name="remark" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md
                                         focus:outline-none focus:ring-1 focus:ring-indigo-500
                                         bg-white dark:bg-zinc-800
                                         text-gray-900 dark:text-white"></textarea>
                    </div>
                    <div class="flex justify-end gap-4">
                        <button type="button"
                                onclick="closeModal('editChallengeModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update Challenge
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Keep your modal-related JavaScript only -->
    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function editChallenge(id) {
            fetch(`/student-challenge/${id}/edit`, {
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

                const semYear = `${data.sem}/${data.year}`;

                document.getElementById('edit_challenge_id').value = data.id;
                document.getElementById('edit_sem_year').value = semYear;
                document.getElementById('edit_name').value = data.name;
                document.getElementById('edit_type').value = data.type;
                document.getElementById('edit_remark').value = data.remark || '';
                document.getElementById('editChallengeForm').action = `/student-challenge/${data.id}`;
                document.getElementById('editChallengeModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching challenge data');
            });
        }

        function deleteChallenge(id) {
            if (confirm('Are you sure you want to delete this challenge?')) {
                const form = document.getElementById('deleteChallengeForm');
                form.action = `/student-challenge/${id}`;

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
                        alert('Error deleting challenge');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting challenge');
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

    <!-- Hidden delete form -->
    <form id="deleteChallengeForm" class="hidden" method="POST">
        @csrf
        @method('DELETE')
    </form>
</x-layouts.app>























