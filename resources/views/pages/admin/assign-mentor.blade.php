
<x-layouts.app :title="__('Assign Mentor')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl"
         x-data="assignMentorData({{ Js::from($mentors) }})">
        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Assign Mentor</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Assign mentors to unassigned students</p>
                </div>
                <div class="flex gap-4">
                    <button
                        @click="showMentorSelection = true"
                        x-show="selectedStudents.length > 0"
                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                        <x-flux::icon name="user-plus" class="size-5 mr-2" />
                        Assign Selected (<span x-text="selectedStudents.length"></span>)
                    </button>
                    <a href="{{ route('admin.mentor') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-gray-600 dark:bg-gray-500 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <x-flux::icon name="arrow-left" class="size-5 mr-2" />
                        Back to Mentor Dashboard
                    </a>
                </div>
            </div>

            <!-- Mentor Selection Section -->
            <div x-show="showMentorSelection"
                 x-transition
                 class="mb-6 bg-gray-50 dark:bg-zinc-800 rounded-lg p-4 border border-gray-200 dark:border-zinc-700">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Select Mentor</h3>
                    <button @click="showMentorSelection = false"
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <x-flux::icon name="x-mark" class="size-5" />
                    </button>
                </div>
                <div class="space-y-4">
                    <select x-model="selectedMentor"
                            class="w-full rounded-md border-gray-300 dark:border-zinc-700 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white">
                        <option value="">Select a mentor</option>
                        <template x-for="mentor in mentors">
                            <option :value="mentor.id" x-text="mentor.name"></option>
                        </template>
                    </select>
                    <div class="flex justify-end">
                        <button @click="assignSelectedStudents()"
                                :disabled="!selectedMentor"
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed">
                            Confirm Assignment
                        </button>
                    </div>
                </div>
            </div>

            <!-- Unassigned Students Table -->
            <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                    <thead class="bg-gray-50 dark:bg-zinc-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matric No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Faculty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Intake</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <input type="checkbox"
                                       @click="selectedStudents = $event.target.checked ? @js($unassignedStudents->pluck('id')) : []"
                                       :checked="selectedStudents.length === @js($unassignedStudents->count())"
                                       class="rounded border-gray-300 dark:border-zinc-700 text-blue-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                        @forelse($unassignedStudents as $student)
                            <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img class="h-10 w-10 rounded-full"
                                                 src="{{ $student->img ?? asset('images/default-avatar.png') }}"
                                                 alt="">
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $student->name }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $student->email }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $student->matric_no }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $student->program }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $student->faculty }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $student->intake }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox"
                                           :value="{{ $student->id }}"
                                           x-model="selectedStudents"
                                           class="rounded border-gray-300 dark:border-zinc-700 text-blue-600">
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                    No unassigned students found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $unassignedStudents->links() }}
            </div>
        </div>
    </div>

    <script>
        function assignMentorData(mentors) {
            return {
                selectedStudents: [],
                showMentorSelection: false,
                selectedMentor: '',
                mentors: mentors,

                assignSelectedStudents() {
                    if (!this.selectedStudents.length || !this.selectedMentor) return;

                    if (confirm(`Are you sure you want to assign ${this.selectedStudents.length} student(s) to this mentor?`)) {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                            || document.querySelector('input[name="_token"]')?.value;

                        if (!csrfToken) {
                            alert('CSRF token not found. Please refresh the page and try again.');
                            return;
                        }

                        fetch('{{ route("admin.assign-mentor.bulk") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                student_ids: this.selectedStudents,
                                mentor_id: this.selectedMentor
                            })
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(err => Promise.reject(err));
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                // Redirect to mentor dashboard instead of reloading
                                window.location.href = '{{ route("admin.mentor") }}';
                            } else {
                                alert(data.message || 'Failed to assign mentor. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('An error occurred while assigning the mentor: ' + (error.message || 'Unknown error'));
                        });
                    }
                }
            }
        }
    </script>
</x-layouts.app>











