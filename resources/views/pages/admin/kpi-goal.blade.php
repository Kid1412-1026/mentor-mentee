<x-layouts.app :title="__('Manage KPI Goals')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <!-- KPI Goals Table -->
            <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">CGPA</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Activities</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Competitions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Leadership</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Other Metrics</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($kpiGoals as $goal)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $goal->cgpa }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Faculty: {{ $goal->faculty_activity }}<br>
                                University: {{ $goal->university_activity }}<br>
                                National: {{ $goal->national_activity }}<br>
                                International: {{ $goal->international_activity }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Faculty: {{ $goal->faculty_competition }}<br>
                                University: {{ $goal->university_competition }}<br>
                                National: {{ $goal->national_competition }}<br>
                                International: {{ $goal->international_competition }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $goal->leadership }}</td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                Graduate on Time: {{ $goal->graduate_on_time }}<br>
                                Professional Certification: {{ $goal->professional_certification }}<br>
                                Employability: {{ $goal->employability }}<br>
                                Mobility Program: {{ $goal->mobility_program }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button onclick="editGoal({{ $goal->id }})" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No KPI goals found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit KPI Goal Modal -->
    <div id="editKpiGoalModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-[800px] shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit KPI Goal</h3>
                <form id="editKpiGoalForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_kpi_goal_id" name="id">

                    <div class="grid grid-cols-2 gap-4">
                        <!-- CGPA -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">CGPA</label>
                            <input type="number" id="edit_cgpa" name="cgpa" step="0.01" min="0" max="4" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <!-- Leadership -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Leadership</label>
                            <input type="number" id="edit_leadership" name="leadership" min="0" required
                                   class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                        </div>

                        <!-- Activities Section -->
                        <div class="col-span-2">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Activities</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty Activity</label>
                                    <input type="number" id="edit_faculty_activity" name="faculty_activity" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">University Activity</label>
                                    <input type="number" id="edit_university_activity" name="university_activity" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">National Activity</label>
                                    <input type="number" id="edit_national_activity" name="national_activity" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">International Activity</label>
                                    <input type="number" id="edit_international_activity" name="international_activity" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Competitions Section -->
                        <div class="col-span-2">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Competitions</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Faculty Competition</label>
                                    <input type="number" id="edit_faculty_competition" name="faculty_competition" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">University Competition</label>
                                    <input type="number" id="edit_university_competition" name="university_competition" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">National Competition</label>
                                    <input type="number" id="edit_national_competition" name="national_competition" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">International Competition</label>
                                    <input type="number" id="edit_international_competition" name="international_competition" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>

                        <!-- Other Metrics Section -->
                        <div class="col-span-2">
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Other Metrics</h4>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Graduate on Time</label>
                                    <input type="text" id="edit_graduate_on_time" name="graduate_on_time" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Professional Certification</label>
                                    <input type="number" id="edit_professional_certification" name="professional_certification" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employability</label>
                                    <input type="text" id="edit_employability" name="employability" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mobility Program</label>
                                    <input type="number" id="edit_mobility_program" name="mobility_program" min="0" required
                                           class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" onclick="closeModal('editKpiGoalModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update KPI Goal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function editGoal(id) {
            fetch(`/admin-kpi-goal/${id}/edit`, {
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
                // Set form values
                document.getElementById('edit_kpi_goal_id').value = data.id;
                document.getElementById('edit_cgpa').value = data.cgpa;
                document.getElementById('edit_leadership').value = data.leadership;

                // Activities
                document.getElementById('edit_faculty_activity').value = data.faculty_activity;
                document.getElementById('edit_university_activity').value = data.university_activity;
                document.getElementById('edit_national_activity').value = data.national_activity;
                document.getElementById('edit_international_activity').value = data.international_activity;

                // Competitions
                document.getElementById('edit_faculty_competition').value = data.faculty_competition;
                document.getElementById('edit_university_competition').value = data.university_competition;
                document.getElementById('edit_national_competition').value = data.national_competition;
                document.getElementById('edit_international_competition').value = data.international_competition;

                // Other metrics
                document.getElementById('edit_graduate_on_time').value = data.graduate_on_time;
                document.getElementById('edit_professional_certification').value = data.professional_certification;
                document.getElementById('edit_employability').value = data.employability;
                document.getElementById('edit_mobility_program').value = data.mobility_program;

                // Set form action and show modal
                document.getElementById('editKpiGoalForm').action = `/admin-kpi-goal/${id}`;
                document.getElementById('editKpiGoalModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching KPI goal data');
            });
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            if (event.target.classList.contains('fixed')) {
                event.target.classList.add('hidden');
            }
        }
    </script>
</x-layouts.app>


