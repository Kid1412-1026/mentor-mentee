<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
        <!-- Add Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <!-- Add SweetAlert2 -->
        <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @include('components.notifications-script')
        @vite('resources/js/alerts.js')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-r border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="mr-5 flex items-center space-x-2" wire:navigate>
                <x-app-logo />
            </a>

            <flux:navlist variant="outline">
                @if(auth()->user()->role === 'admin')
                    <flux:navlist.group :heading="__('Admin')" class="grid">
                        <flux:navlist.item icon="home" :href="route('admin.dashboard')" :current="request()->routeIs('admin.dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="book-open" :href="route('admin.course.index')" :current="request()->routeIs('admin.course.index')" wire:navigate>{{ __('Manage Course') }}</flux:navlist.item>
                        <flux:navlist.item icon="briefcase" :href="route('admin.career')" :current="request()->routeIs('admin.career')" wire:navigate>{{ __('Career') }}</flux:navlist.item>
                        <flux:navlist.item icon="document" :href="route('admin.report')" :current="request()->routeIs('admin.report')" wire:navigate>{{ __('Report') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('admin.mentor')" :current="request()->routeIs('admin.mentor')" wire:navigate>{{ __('Mentor') }}</flux:navlist.item>
                        <flux:navlist.item icon="newspaper" :href="route('admin.news')" :current="request()->routeIs('admin.news')" wire:navigate>{{ __('News') }}</flux:navlist.item>
                        <flux:navlist.item icon="star" :href="route('admin.kpigoal')" :current="request()->routeIs('admin.kpigoal')" wire:navigate>{{ __('KPI Goal') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif

                @if(auth()->user()->role === 'student')
                    <flux:navlist.group :heading="__('Student')" class="grid">
                        <flux:navlist.item icon="home" :href="route('student.dashboard')" :current="request()->routeIs('student.dashboard')" wire:navigate>{{ __('Dashboard') }}</flux:navlist.item>
                        <flux:navlist.item icon="academic-cap" :href="route('student.activity')" :current="request()->routeIs('student.activity')" wire:navigate>{{ __('Activity') }}</flux:navlist.item>
                        <flux:navlist.item icon="bolt" :href="route('student.challenge')" :current="request()->routeIs('student.challenge')" wire:navigate>{{ __('Challenge') }}</flux:navlist.item>
                        <flux:navlist.item icon="users" :href="route('student.mentor')" :current="request()->routeIs('student.mentor')" wire:navigate>{{ __('Mentor') }}</flux:navlist.item>
                        <flux:navlist.item icon="chart-bar" :href="route('student.kpi')" :current="request()->routeIs('student.kpi')" wire:navigate>{{ __('KPI') }}</flux:navlist.item>
                        <flux:navlist.item icon="briefcase" :href="route('student.career')" :current="request()->routeIs('student.career')" wire:navigate>{{ __('Career') }}</flux:navlist.item>
                        <flux:navlist.item icon="book-open" :href="route('student.course')" :current="request()->routeIs('student.course')" wire:navigate>{{ __('Course') }}</flux:navlist.item>
                    </flux:navlist.group>
                @endif
            </flux:navlist>

            <flux:spacer />

            <flux:navlist variant="outline">
                <flux:navlist.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit" target="_blank">
                {{ __('Repository') }}
                </flux:navlist.item>

                <flux:navlist.item icon="book-open-text" href="https://laravel.com/docs/starter-kits" target="_blank">
                {{ __('Documentation') }}
                </flux:navlist.item>
            </flux:navlist>

            <!-- Desktop User Menu -->
            <flux:dropdown position="bottom" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevrons-up-down"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="user" wire:navigate>
                            {{ __('My Profile') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-left text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span
                                        class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white"
                                    >
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-left text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>{{ __('Settings') }}</flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                            {{ __('Log Out') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
        @stack('scripts')
    </body>
</html>
