<x-layouts.app :title="__('Meeting Reports')">
@if (session('alert'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                showAlert(
                    '{{ session('alert.type') }}',
                    '{{ session('alert.title') }}',
                    '{{ session('alert.message') }}'
                );
            });
        </script>
    @endif
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <!-- Back Button -->
        <div class="flex justify-end">
            <a href="{{ route('admin.mentor') }}"
               class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors">
                <x-flux::icon name="arrow-left" class="size-5 mr-2" />
                Back to Mentor
            </a>
        </div>

        <!-- Batch Export Section -->
        <div x-data="{ isExportVisible: false, selectedMeetings: [] }" class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
            <div class="flex justify-between items-center mb-4">
                <button @click="isExportVisible = !isExportVisible"
                    class="inline-flex items-center justify-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                    <x-flux::icon name="document-arrow-down" class="size-5 mr-2" />
                    Batch Export
                </button>
            </div>

            <!-- Batch Export Form -->
            <div x-show="isExportVisible" x-cloak class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Select Reports to Export</h4>
                <div class="space-y-4">
                    @foreach($meetings as $meeting)
                    <div class="flex items-center">
                        <input type="checkbox"
                            x-model="selectedMeetings"
                            value="{{ $meeting->id }}"
                            id="meeting-{{ $meeting->id }}"
                            class="rounded border-gray-300 dark:border-zinc-700 text-indigo-600">
                        <label for="meeting-{{ $meeting->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                            Meeting on {{ \Carbon\Carbon::parse($meeting->session_date)->format('d/m/Y') }} -
                            Batch {{ $meeting->batch }} ({{ ucfirst($meeting->method) }})
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
                    <button @click="if(selectedMeetings.length > 0) exportBatchReports(selectedMeetings)"
                        :class="{ 'opacity-50 cursor-not-allowed': selectedMeetings.length === 0 }"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                        Export Selected Reports
                    </button>
                </div>
            </div>
        </div>

        <!-- Form Section -->
        <div x-data="{ isFormVisible: false }" class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6">
            <!-- Add Meeting Report Button -->
            <div class="flex justify-end mb-4">
                <button @click="isFormVisible = !isFormVisible"
                        class="inline-flex items-center justify-center p-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <x-flux::icon name="plus" class="size-5" />
                </button>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    @foreach ($errors->all() as $error)
                        <span class="block sm:inline">{{ $error }}</span>
                    @endforeach
                </div>
            @endif

            <!-- Add Meeting Report Form -->
            <div x-show="isFormVisible"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-4"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-4"
                 class="mb-8 bg-gray-50 dark:bg-zinc-800 p-6 rounded-lg">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">Add New Meeting Report</h2>
                <form action="{{ route('admin.meetings.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Semester / Year</label>
                            <select name="sem_year" required class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm">
                                <option value="">Select Semester/Year</option>
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
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Batch</label>
                            <input type="text" name="batch" required maxlength="9" placeholder="Enter batch (e.g., 2021/2022)"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Session Date</label>
                            <input type="date" name="session_date" required
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Method</label>
                            <select name="method" required class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm">
                                <option value="face-to-face">Face-to-face</option>
                                <option value="online">Online</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Duration (minutes)</label>
                            <input type="number" name="duration" required min="1"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm">
                        </div>
                    </div>

                    <div class="space-y-4 mt-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Agenda</label>
                            <textarea name="agenda" required rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Discussion</label>
                            <textarea name="discussion" required rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Action</label>
                            <textarea name="action" required rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm"></textarea>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Remarks (Optional)</label>
                            <textarea name="remarks" rows="3"
                                class="w-full rounded-md border-gray-300 dark:border-zinc-700 dark:bg-zinc-800 shadow-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit"
                            class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition-colors">
                            Submit Meeting Report
                        </button>
                        <button type="button"
                            @click="isFormVisible = false"
                            class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

        <!-- Meetings Cards Grid -->
        <div class="mt-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Meeting Results</h3>
            @if($meetings->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($meetings as $meeting)
                        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md dark:shadow-zinc-800/30 overflow-hidden">
                            <!-- Header -->
                            <div class="bg-gray-50 dark:bg-zinc-700 px-4 py-3 border-b border-gray-200 dark:border-zinc-600">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        Semester {{ $meeting->sem }} / Year {{ $meeting->year }}
                                    </span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($meeting->session_date)->format('d/m/Y') }}
                                        </span>
                                        <button onclick="exportMeetingReport({{ $meeting->id }})"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            <x-flux::icon name="document-arrow-down" class="size-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4 space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Batch</span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->batch }}</span>
                                    </div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Method</span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ ucfirst($meeting->method) }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Duration</span>
                                        <span class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->duration }} minutes</span>
                                    </div>
                                </div>

                                <!-- Students List -->
                                <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Students</h4>
                                    @php
                                        $students = \App\Models\Student::where('intake', $meeting->batch)->get();
                                    @endphp
                                    <div class="space-y-2 max-h-40 overflow-y-auto">
                                        @forelse($students as $student)
                                            <div class="flex justify-between items-center text-sm">
                                                <span class="text-gray-900 dark:text-gray-100">{{ $student->name }}</span>
                                                <span class="text-gray-500 dark:text-gray-400">{{ $student->matric_no }}</span>
                                            </div>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No students found for this batch</p>
                                        @endforelse
                                    </div>
                                </div>

                                <!-- Agenda Section -->
                                <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Agenda</h4>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->agenda }}</p>
                                </div>

                                <!-- Discussion Section -->
                                <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Discussion</h4>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->discussion }}</p>
                                </div>

                                <!-- Action Section -->
                                <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Action</h4>
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->action }}</p>
                                </div>

                                @if($meeting->remarks)
                                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-4">
                                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Remarks</h4>
                                        <p class="text-sm text-gray-900 dark:text-gray-100">{{ $meeting->remarks }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex justify-center items-center min-h-[200px] bg-gray-50 dark:bg-zinc-800 rounded-lg">
                    <div class="text-center">
                        <p class="text-xl text-gray-500 dark:text-gray-400">No meetings found.</p>
                    </div>
                </div>
            @endif

            <!-- Pagination -->
            <div class="mt-6">
                {{ $meetings->links() }}
            </div>
        </div>
    </div>

    <script>
        function exportMeetingReport(meetingId) {
            window.location.href = `/admin/meeting/${meetingId}/export`;
        }

        function exportBatchReports(selectedIds) {
            window.location.href = `/admin/meeting/export-batch?ids=${selectedIds.join(',')}`;
        }
    </script>
</x-layouts.app>
