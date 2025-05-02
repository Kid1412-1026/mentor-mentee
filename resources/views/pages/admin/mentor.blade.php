<x-layouts.app :title="__('Mentor Mentee')">
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
    <!-- Load FullCalendar JS first -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Define Alpine Component before using it -->
    <script>
        window.calendarApp = () => ({
            calendar: null,
            isUpdateSectionOpen: false,
            selectedEventId: null,
            selectedStatus: '',

            init() {
                if (typeof FullCalendar === 'undefined') {
                    setTimeout(() => this.init(), 100);
                    return;
                }
                this.initCalendar();
            },

            initCalendar() {
                const calendarEl = document.getElementById('calendar');
                if (!calendarEl) {
                    console.error('Calendar element not found!');
                    return;
                }

                this.calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek'
                    },
                    events: '{{ route("admin.mentor.events") }}',
                    timeZone: 'Asia/Kuala_Lumpur',
                    editable: false,
                    selectable: false,
                    dayMaxEvents: false,
                    eventDisplay: 'block',
                    height: 'auto',
                    eventMaxStack: 3,
                    firstDay: 1, // Start week on Monday
                    weekends: true, // Show weekends
                    views: {
                        dayGrid: {
                            dayMaxEventRows: 4
                        }
                    },
                    eventContent: (arg) => this.renderEventContent(arg),
                    eventClick: (info) => this.handleEventClick(info)
                });

                this.calendar.render();
            },

            renderEventContent(arg) {
                const startTime = arg.event.start.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                const endTime = arg.event.end.toLocaleTimeString('en-US', {
                    hour: '2-digit',
                    minute: '2-digit',
                    hour12: true
                });
                const duration = (arg.event.end - arg.event.start) / (1000 * 60);
                const durationText = `${Math.floor(duration / 60)}h ${duration % 60}m`;

                return {
                    html: `
                        <div class="fc-content p-1" style="min-height: 80px;">
                            <div class="fc-title font-semibold mb-1">
                                ${arg.event.title}
                            </div>
                            <div class="fc-description text-xs">
                                <div><strong>Student:</strong> ${arg.event.extendedProps.student_name}</div>
                                <div><strong>Start:</strong> ${startTime}</div>
                                <div><strong>End:</strong> ${endTime}</div>
                                <div><strong>Duration:</strong> ${durationText}</div>
                                <div><strong>Venue:</strong> ${arg.event.extendedProps.venue}</div>
                                <div><strong>Status:</strong> ${arg.event.extendedProps.status}</div>
                            </div>
                        </div>
                    `
                };
            },

            handleEventClick(info) {
                this.selectedEventId = info.event.id;
                this.selectedStatus = info.event.extendedProps.status;
                this.isUpdateSectionOpen = true;
                // Scroll to update section
                setTimeout(() => {
                    document.getElementById('updateStatusSection').scrollIntoView({ behavior: 'smooth' });
                }, 100);
            },

            async updateStatus() {
                const form = document.getElementById('updateStatusForm');
                const formData = new FormData(form);

                try {
                    const response = await fetch(
                        `{{ route('admin.mentor.update-status', '') }}/${this.selectedEventId}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            }
                        }
                    );

                    const data = await response.json();

                    if (data.success) {
                        this.calendar.refetchEvents();
                        this.isUpdateSectionOpen = false;
                        this.selectedEventId = null;
                        this.selectedStatus = '';
                    } else {
                        alert('Failed to update status');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    alert('An error occurred while updating the status');
                }
            }
        });
    </script>

    <!-- Calendar Container -->
    <div
        x-data="calendarApp()"
        x-init="init()"
        class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <!-- Status Update Section -->
        <div id="updateStatusSection"
             x-show="isUpdateSectionOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900 dark:text-white">Update Session Status</h3>
                <button @click="isUpdateSectionOpen = false"
                        class="text-gray-400 hover:text-gray-500">
                    <x-flux::icon name="x-mark" class="size-5" />
                </button>
            </div>
            <form id="updateStatusForm"
                  :action="`{{ route('admin.mentor.update-status', '') }}/${selectedEventId}`"
                  method="POST">
                @csrf
                <input type="hidden" name="counseling_id" x-model="selectedEventId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Status:</label>
                        <p class="text-gray-600 dark:text-gray-400 mt-1" x-text="selectedStatus"></p>
                    </div>
                    <div>
                        <label for="newStatus" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Status:</label>
                        <select id="newStatus"
                                name="status"
                                class="mt-1 w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update Status
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">Mentor-Mentee Management</h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and monitor mentor-mentee relationships</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.meetingreport') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-500 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600 transition-colors">
                        <x-flux::icon name="clipboard-document-list" class="size-5 mr-2" />
                        View Meeting Report
                    </a>
                    <a href="{{ route('admin.assign-mentor') }}"
                       class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 dark:bg-blue-500 text-white rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors">
                        <x-flux::icon name="user-plus" class="size-5 mr-2" />
                        Assign Mentor
                    </a>
                </div>
            </div>

            <div id='calendar' style="min-height: 500px;"></div>
        </div>

        <!-- Students Table -->
        <div class="overflow-x-auto bg-white dark:bg-zinc-800 rounded-lg shadow">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Student</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Matric No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Program</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Faculty</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Activities</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse($students as $student)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="{{ $student->img ?? asset('images/default-avatar.png') }}" alt="">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $student->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $student->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $student->matric_no }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $student->program }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">{{ $student->faculty }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100">
                                {{ $student->activities_count ?? $student->activities->count() }} activities
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <a href="#" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">View Details</a>
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
        <div class="mt-4">
            {{ $students->links() }}
        </div>
    </div>

    <style>
        [x-cloak] {
            display: none !important;
        }
        /* Day of week header text (Sunday, Monday, etc.) */
        .fc .fc-col-header-cell-cushion,
        .fc-theme-standard .fc-col-header-cell-cushion,
        .fc-col-header-cell a {
            color: #171717 !important;
            font-weight: 500 !important;
            text-decoration: none !important;
        }

        /* Header background */
        .fc-theme-standard th {
            background-color: #f3f4f6 !important;
            color: #171717 !important;
            border-color: #e5e7eb !important;
        }

        /* Dark mode styles */
        .dark .fc .fc-col-header-cell-cushion,
        .dark .fc-theme-standard .fc-col-header-cell-cushion,
        .dark .fc-col-header-cell a {
            color: #ffffff !important;
        }

        .dark .fc-theme-standard th {
            background-color: #27272a !important;
            color: #ffffff !important;
            border-color: #3f3f46 !important;
        }

        /* Day numbers */
        .fc-daygrid-day-number {
            color: #171717 !important;
            padding: 8px !important;
        }

        .dark .fc-daygrid-day-number {
            color: #ffffff !important;
        }

        /* Event styles */
        .fc-event {
            cursor: default !important;
        }

        .fc-event:hover {
            cursor: default !important;
            opacity: 1 !important;
        }

        /* Calendar borders */
        .fc-theme-standard td,
        .fc-theme-standard th {
            border-color: #e5e7eb !important;
        }

        .dark .fc-theme-standard td,
        .dark .fc-theme-standard th {
            border-color: #3f3f46 !important;
        }

        /* Make day grid cells responsive */
        .fc .fc-daygrid-day-frame {
            min-height: 100px !important;
            height: auto !important;
        }

        .fc .fc-daygrid-day-events {
            min-height: 2em;
            margin: 0 !important;
        }

        .fc .fc-daygrid-body {
            width: 100% !important;
        }

        .fc .fc-daygrid-body-balanced {
            width: 100% !important;
        }

        .fc .fc-daygrid-body-unbalanced {
            width: 100% !important;
        }

        .fc table {
            width: 100% !important;
        }

        .fc .fc-scrollgrid-section table {
            width: 100% !important;
        }

        .fc .fc-daygrid-body table {
            width: 100% !important;
        }

        /* Ensure cells take up equal width */
        .fc td {
            width: calc(100% / 7) !important;
        }

        /* Handle very small screens */
        @media (max-width: 640px) {
            .fc .fc-daygrid-day-frame {
                min-height: 75px !important;
            }
        }

        /* Fix non-business days and weekend background fill */
        .fc .fc-day-disabled,
        .fc .fc-day-other,
        .fc-daygrid-day.fc-day-disabled,
        .fc-daygrid-day.fc-day-other,
        .fc-day-sun,  /* Add Sunday */
        .fc-day-sat { /* Add Saturday */
            background-color: rgba(0, 0, 0, 0.05) !important;
        }

        .dark .fc .fc-day-disabled,
        .dark .fc .fc-day-other,
        .dark .fc-daygrid-day.fc-day-disabled,
        .dark .fc-daygrid-day.fc-day-other,
        .dark .fc-day-sun,  /* Add Sunday */
        .dark .fc-day-sat { /* Add Saturday */
            background-color: rgba(255, 255, 255, 0.05) !important;
        }

        /* Ensure background fills entire cell */
        .fc-daygrid-day {
            position: relative;
            background-clip: padding-box !important;
        }

        .fc .fc-daygrid-day-frame {
            height: 100% !important;
            position: relative;
            background: inherit;
            min-height: inherit;
            display: flex;
            flex-direction: column;
        }

        /* Fix background inheritance */
        .fc-daygrid-day-events {
            flex-grow: 1;
            position: relative;
            background: inherit;
        }

        .fc-daygrid-day-bg {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: inherit;
        }
    </style>
</x-layouts.app>



