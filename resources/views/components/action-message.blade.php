@props([
    'on' => 'saved',
])

<div x-data="{ shown: false }"
     x-init="
        $watch('$store.notifications.{{ $on }}', value => {
            if (value) {
                shown = true;
                setTimeout(() => {
                    shown = false;
                    $store.notifications.{{ $on }} = false;
                }, 2000);
            }
        })
     "
     x-show="shown"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     class="fixed inset-0 flex items-center justify-center z-50"
     style="display: none;"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-black/25 dark:bg-black/50"></div>

    <!-- Popup Content -->
    <div class="relative bg-white dark:bg-zinc-800 rounded-lg shadow-xl p-6 max-w-sm w-full mx-4 text-center">
        <div class="mb-4">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                <svg class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ $slot->isEmpty() ? __('Saved successfully.') : $slot }}
        </h3>
    </div>
</div>

