<x-layouts.no-auth>
    <div class="flex h-full w-full flex-1 flex-col gap-4">
        <div class="bg-white shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-lg p-6">
            <h1 class="text-4xl font-bold text-[#1b1b18] mb-4 text-center">Announcements</h1>

            @if ($announcements->isEmpty())
                <p class="text-xl text-[#706f6c] text-center">No announcements available.</p>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($announcements as $announcement)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden border border-[#e3e3e0]">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-[#1b1b18] mb-2">
                                    {{ $announcement->title }}
                                </h3>

                                <div class="text-sm text-[#706f6c] mb-4">
                                    {{ $announcement->description }}
                                </div>

                                <div class="flex items-center justify-between mt-4 pt-4 border-t border-[#e3e3e0]">
                                    <div class="text-sm text-[#706f6c]">
                                        {{ $announcement->created_at }}
                                    </div>

                                    @php
                                        $filePath = asset('uploads/' . $announcement->file);
                                        $fileExtension = pathinfo($announcement->file, PATHINFO_EXTENSION);
                                    @endphp

                                    <div class="flex items-center">
                                        @if (!empty($announcement->newsfile))
                                            @if (in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                <img src="{{ $filePath }}" alt="Attachment" class="w-24 h-auto rounded-lg">
                                            @elseif ($fileExtension === 'pdf')
                                                <a href="{{ $filePath }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-[#f53003] hover:underline">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    View PDF
                                                </a>
                                            @else
                                                <a href="{{ $filePath }}"
                                                   target="_blank"
                                                   class="inline-flex items-center text-[#f53003] hover:underline">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                                    </svg>
                                                    Download File
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-sm text-[#706f6c]">No attachment</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.no-auth>

