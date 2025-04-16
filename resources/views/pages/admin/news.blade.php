<x-layouts.app :title="__('Manage Announcements')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="overflow-x-auto bg-white dark:bg-zinc-900 shadow-md dark:shadow-zinc-800/30 rounded-lg">
            <!-- Add Announcement Section -->
            <div class="p-6"
                 x-data="{ isAddingAnnouncement: false }">
                <!-- Toggle Button -->
                <div class="flex justify-end mb-4">
                    <button @click="isAddingAnnouncement = !isAddingAnnouncement"
                            class="inline-flex items-center justify-center p-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <x-flux::icon name="plus" class="size-5" />
                        <span class="sr-only">Add Announcement</span>
                    </button>
                </div>

                <!-- Expandable Form Section -->
                <div x-show="isAddingAnnouncement"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform -translate-y-2"
                     x-transition:enter-end="opacity-100 transform translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 transform translate-y-0"
                     x-transition:leave-end="opacity-0 transform -translate-y-2"
                     class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700 p-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add New Announcement</h3>
                    <form action="{{ route('admin.news.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                                <input type="text" name="title" required
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea name="description" required rows="4"
                                          class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachment</label>
                                <input type="file" name="file"
                                       class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="flex justify-end gap-3 mt-4">
                            <button type="button" @click="isAddingAnnouncement = false"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                Add Announcement
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <table class="min-w-full table-auto">
                <thead class="bg-gray-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Attachment</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-zinc-900 divide-y divide-gray-200 dark:divide-zinc-700">
                    @forelse ($announcements as $announcement)
                        <tr class="hover:bg-gray-50 dark:hover:bg-zinc-800/50 transition-colors">
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">{{ $announcement->title }}</td>
                            <td class="px-6 py-4 text-gray- 900 dark:text-gray-300">
                                {{ Str::limit($announcement->description, 100) }}
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                @if ($announcement->file)
                                    <a href="{{ asset('storage/' . $announcement->file) }}"
                                       class="text-indigo-600 dark:text-indigo-400 hover:underline"
                                       target="_blank">
                                        View Attachment
                                    </a>
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">No attachment</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-gray-900 dark:text-gray-300">
                                {{ \Carbon\Carbon::parse($announcement->created_at)->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="editAnnouncement({{ $announcement->id }})"
                                        class="inline-flex items-center justify-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3 transition-colors">
                                    <x-flux::icon name="pencil" class="size-5" />
                                    <span class="sr-only">Edit</span>
                                </button>
                                <button onclick="deleteAnnouncement({{ $announcement->id }})"
                                        class="inline-flex items-center justify-center text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                    <x-flux::icon name="trash" class="size-5" />
                                    <span class="sr-only">Delete</span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                No announcements found
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>

    <!-- Edit Announcement Modal -->
    <div id="editAnnouncementModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-zinc-900">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Edit Announcement</h3>
                <form id="editAnnouncementForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_announcement_id" name="id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" id="edit_title" name="title" required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea id="edit_description" name="description" required rows="4"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500"></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attachment</label>
                        <input type="file" name="file"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-zinc-700 rounded-md focus:outline-none focus:ring-1 focus:ring-indigo-500">
                    </div>
                    <div class="flex justify-end gap-3">
                        <button type="button" onclick="closeModal('editAnnouncementModal')"
                                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                            Update Announcement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add this hidden form for delete operation -->
    <form id="deleteAnnouncementForm" class="hidden" method="POST">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openModal() {
            document.getElementById('addAnnouncementModal').classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function editAnnouncement(id) {
            fetch(`/admin-news/${id}/edit`, {
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
                document.getElementById('edit_announcement_id').value = data.id;
                document.getElementById('edit_title').value = data.title;
                document.getElementById('edit_description').value = data.description;
                document.getElementById('editAnnouncementForm').action = `/admin-news/${data.id}`;
                document.getElementById('editAnnouncementModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error fetching announcement data');
            });
        }

        function deleteAnnouncement(id) {
            if (confirm('Are you sure you want to delete this announcement?')) {
                const form = document.getElementById('deleteAnnouncementForm');
                form.action = `/admin-news/${id}`;

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
                        alert('Error deleting announcement');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error deleting announcement');
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







