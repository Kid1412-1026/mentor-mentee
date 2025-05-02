
<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Create an account')" :description="__('Enter your details below to create your account')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Role Selection -->
        <flux:select
            wire:model.live="role"
            :label="__('Register as')"
            required
        >
            <option value="">Select role</option>
            <option value="student">Student</option>
            <option value="admin">Admin</option>
        </flux:select>

        <!-- Name -->
        <flux:input
            wire:model="name"
            :label="__('Name')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Full name')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            :label="__('Email address')"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Student-specific fields -->
        @if($role === 'student')
            <div class="space-y-1">  {{-- Added a wrapper with spacing --}}
                <flux:input
                    wire:model="matric_no"
                    :label="__('Matric Number')"
                    type="text"
                    required
                    maxlength="15"
                    :placeholder="__('Enter matric number')"
                />

                {{-- Single error message display --}}
                @if ($errors->has('matric_no'))  {{-- Changed to @if instead of @error --}}
                    <div class="mt-1 text-sm text-red-600 dark:text-red-400">
                        @if($errors->first('matric_no') === 'This matric number is already registered. Please login instead.')
                            <div class="mt-1">
                                <a href="{{ route('login') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ __('Login instead?') }}
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <flux:select
                wire:model="programme_id"
                :label="__('Programme')"
                required
            >
                <option value="">Select programme</option>
                @foreach($programmes as $programme)
                    <option value="{{ $programme->id }}">{{ $programme->code }} - {{ $programme->name }}</option>
                @endforeach
            </flux:select>

            <flux:input
                wire:model="intake"
                :label="__('Intake')"
                type="number"
                required
                :placeholder="__('Enter intake year')"
            />
        @endif

        <!-- Admin-specific fields -->
        @if($role === 'admin')
            <flux:input
                wire:model="faculty"
                :label="__('Faculty')"
                type="text"
                required
                maxlength="255"
                :placeholder="__('Enter faculty name')"
            />

            <flux:input
                wire:model="pose"
                :label="__('Position')"
                type="text"
                required
                maxlength="100"
                :placeholder="__('Enter position')"
            />
        @endif

        <!-- Password -->
        <flux:input
            wire:model="password"
            :label="__('Password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Password')"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirm password')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirm password')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Create account') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Already have an account?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Log in') }}</flux:link>
    </div>
</div>






