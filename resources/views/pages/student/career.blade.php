<x-layouts.app :title="__('Careers')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($careers as $career)
                <div class="bg-white dark:bg-zinc-900 rounded-lg shadow-md dark:shadow-zinc-800/30 overflow-hidden">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                            {{ $career->title }}
                        </h3>

                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ Str::limit($career->description, 150) }}
                        </div>

                        <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-zinc-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $career->created_at }}
                            </div>

                            @if ($career->file)
                                <a href="{{ asset('storage/' . $career->file) }}"
                                   class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:underline"
                                   target="_blank">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    View Attachment
                                </a>
                            @else
                                <span class="text-sm text-gray-500 dark:text-gray-400">
                                    No attachment
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="text-center p-6 bg-white dark:bg-zinc-900 rounded-lg shadow-md dark:shadow-zinc-800/30">
                        <p class="text-gray-500 dark:text-gray-400">No careers found</p>
                    </div>
                </div>
            @endforelse
        </div>

        <div class="mt-4">
            {{ $careers->links() }}
        </div>
    </div>
</x-layouts.app>



