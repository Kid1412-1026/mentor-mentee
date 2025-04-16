<x-layouts.app :title="__('Calendar')">
    <!-- Include FullCalendar -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <!-- Alpine Component -->
    <script>
        window.calendarApp = function() {
            return {
                calendar: null,
                isFormOpen: false,

                init() {
                    console.log('Calendar component initialized');
                    setTimeout(() => this.initCalendar(), 100);
                },

                initCalendar() {
                    if (typeof FullCalendar === 'undefined') {
                        console.error('FullCalendar not loaded');
                        return;
                    }

                    const calendarEl = document.getElementById('calendar');
                    if (!calendarEl) {
                        console.error('Calendar element not found');
                        return;
                    }

                    this.calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'dayGridMonth',
                        headerToolbar: {
                            left: 'prev,next today addEvent',
                            center: 'title',
                            right: 'dayGridMonth,timeGridWeek'
                        },
                        customButtons: {
                            addEvent: {
                                text: 'Schedule Meeting',
                                click: () => {
                                    console.log('Schedule Meeting clicked');
                                    this.openForm();
                                }
                            }
                        },
                        events: '{{ route("student.mentor.events") }}',
                        timeZone: 'Asia/Kuala_Lumpur',
                        slotMinTime: '08:00:00',
                        slotMaxTime: '18:00:00',
                        firstDay: 1, // Monday
                        businessHours: {
                            daysOfWeek: [1, 2, 3, 4, 5], // Monday - Friday
                            startTime: '08:00',
                            endTime: '18:00',
                        },
                        editable: false,
                        selectable: false,
                        dayMaxEvents: false,
                        eventDisplay: 'block',
                        height: 'auto',
                        handleWindowResize: true,
                        contentHeight: 'auto',
                        expandRows: true,
                        windowResizeDelay: 200,
                        eventMaxStack: 3,
                        views: {
                            dayGrid: {
                                dayMaxEventRows: 4
                            }
                        },
                        eventInteractive: false,
                        eventContent: this.renderEventContent
                    });

                    this.calendar.render();
                    this.initDateTimeHandlers();
                },

                renderEventContent(arg) {
                    const statusColor = arg.event.backgroundColor;
                    const textColor = statusColor === '#dc2626' ? 'white' : 'black';

                    const duration = parseInt(arg.event.extendedProps.duration);
                    const hours = Math.floor(duration / 60);
                    const minutes = duration % 60;
                    const durationText = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;

                    const startTime = arg.event.start.toLocaleTimeString('en-MY', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Kuala_Lumpur'
                    });
                    const endTime = arg.event.end.toLocaleTimeString('en-MY', {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: true,
                        timeZone: 'Asia/Kuala_Lumpur'
                    });

                    return {
                        html: `
                            <div class="fc-content p-1" style="min-height: 80px;">
                                <div class="fc-title font-semibold mb-1" style="color: ${textColor};">
                                    ${arg.event.title}
                                </div>
                                <div class="fc-description text-xs" style="color: ${textColor};">
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

                openForm() {
                    console.log('Opening scheduling form');
                    this.isFormOpen = true;
                },

                closeForm() {
                    console.log('Closing scheduling form');
                    this.isFormOpen = false;
                    this.$refs.addEventForm?.reset();
                },

                async handleSubmit(event) {
                    event.preventDefault();
                    const form = event.target;
                    const formData = new FormData(form);
                    const csrfToken = document.querySelector('input[name="_token"]').value;

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            alert(data.error + (data.debug ? '\n' + data.debug : ''));
                            return;
                        }

                        // Show success message
                        alert('Counseling session scheduled successfully');

                        // Close the form and refresh calendar
                        this.closeForm();
                        this.calendar.refetchEvents();

                        // Perform the redirect
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        } else {
                            window.location.reload(); // Fallback if no redirect URL provided
                        }

                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred while scheduling the meeting. Please try again.');
                    }
                },

                initDateTimeHandlers() {
                    const startTimeInput = document.getElementById('start_time');
                    const endTimeInput = document.getElementById('end_time');
                    const durationDisplay = document.getElementById('duration_display');
                    const durationMinutes = document.getElementById('duration_minutes');

                    const updateDuration = () => {
                        if (startTimeInput.value && endTimeInput.value) {
                            const start = new Date(startTimeInput.value);
                            const end = new Date(endTimeInput.value);
                            const diff = end - start;
                            const minutes = Math.round(diff / 60000);
                            const hours = Math.floor(minutes / 60);
                            const remainingMinutes = minutes % 60;

                            durationDisplay.value = `${hours} hour(s) ${remainingMinutes} minute(s)`;
                            durationMinutes.value = minutes;
                        }
                    };

                    // Set minimum date-time to now in Malaysia time
                    const now = new Date();
                    const malaysiaTime = new Date(now.toLocaleString('en-US', {
                        timeZone: 'Asia/Kuala_Lumpur'
                    }));

                    // Format the date-time string properly
                    const year = malaysiaTime.getFullYear();
                    const month = String(malaysiaTime.getMonth() + 1).padStart(2, '0');
                    const day = String(malaysiaTime.getDate()).padStart(2, '0');
                    const hours = String(malaysiaTime.getHours()).padStart(2, '0');
                    const minutes = String(malaysiaTime.getMinutes()).padStart(2, '0');

                    const minDateTime = `${year}-${month}-${day}T${hours}:${minutes}`;
                    startTimeInput.min = minDateTime;

                    startTimeInput.addEventListener('change', function() {
                        if (this.value) {
                            endTimeInput.min = this.value;
                        }
                        updateDuration();
                    });

                    endTimeInput.addEventListener('change', updateDuration);
                }
            }
        }
    </script>

    <!-- Calendar Container -->
    <div
        x-data="calendarApp()"
        x-init="init"
        class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">

        <div class="bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg p-6">
            <!-- Expandable Scheduling Form -->
            <div x-show="isFormOpen"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="mb-6 bg-gray-50 dark:bg-zinc-800 rounded-lg p-4 border border-gray-200 dark:border-zinc-700">

                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Schedule Counseling Session</h3>
                    <button @click="closeForm" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                        <x-flux::icon name="x-mark" class="size-5" />
                    </button>
                </div>

                <form id="addEventForm"
                      x-ref="addEventForm"
                      @submit.prevent="handleSubmit"
                      action="{{ route('student.mentor.store') }}"
                      method="POST"
                      class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf

                    <!-- Start Time -->
                    <div>
                        <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Time
                        </label>
                        <input type="datetime-local"
                               id="start_time"
                               name="start_time"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                    </div>

                    <!-- End Time -->
                    <div>
                        <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Time
                        </label>
                        <input type="datetime-local"
                               id="end_time"
                               name="end_time"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                    </div>

                    <!-- Duration (calculated automatically) -->
                    <div>
                        <label for="duration_display" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Duration (minutes)
                        </label>
                        <input type="text"
                               id="duration_display"
                               readonly
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md bg-gray-50 dark:bg-zinc-700 cursor-not-allowed">
                        <input type="hidden"
                               id="duration_minutes"
                               name="duration"
                               required>
                    </div>

                    <!-- Venue -->
                    <div>
                        <label for="venue" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Venue
                        </label>
                        <input type="text"
                               id="venue"
                               name="venue"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500 bg-white dark:bg-zinc-800"></textarea>
                    </div>

                    <!-- Hidden Status Field - will be set to 'pending' by default in the controller -->
                    <input type="hidden" name="status" value="pending">

                    <!-- Form Actions -->
                    <div class="md:col-span-2 flex justify-end gap-3">
                        <button type="button"
                                @click="closeForm"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Schedule Session
                        </button>
                    </div>
                </form>
            </div>

            <!-- Calendar -->
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Add Tippy.js for better tooltips -->
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

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














